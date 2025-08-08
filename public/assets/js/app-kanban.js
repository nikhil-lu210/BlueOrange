
/**
 * Enhanced App Kanban - Laravel Integration with Parent Task Support
 */

'use strict';

(async function () {
  let boards;
  const kanbanSidebar = document.querySelector('.kanban-update-item-sidebar'),
    kanbanWrapper = document.querySelector('.kanban-wrapper'),
    commentEditor = document.querySelector('.comment-editor'),
    kanbanAddNewBoard = document.querySelector('.kanban-add-new-board'),
    kanbanAddNewInput = [].slice.call(document.querySelectorAll('.kanban-add-board-input')),
    kanbanAddBoardBtn = document.querySelector('.kanban-add-board-btn'),
    datePicker = document.querySelector('#deadline'),
    select2 = $('.select2'), // ! Using jquery vars due to select2 jQuery dependency
    baseUrl = window.location.origin, // Get base URL
    csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  // Init kanban Offcanvas
  const kanbanOffcanvas = new bootstrap.Offcanvas(kanbanSidebar);

  // Get kanban data from Laravel API
  try {
    const kanbanResponse = await fetch(`${baseUrl}/api/kanban/data`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      }
    });
    
    if (!kanbanResponse.ok) {
      throw new Error(`HTTP error! status: ${kanbanResponse.status}`);
    }
    
    boards = await kanbanResponse.json();
    // console.log('Kanban data loaded:', boards);
  } catch (error) {
    console.error('Error loading kanban data:', error);
    // Fallback to empty boards
    boards = [
      { id: 'board-activated', title: 'Activated', item: [] },
      { id: 'board-running', title: 'Running', item: [] },
      { id: 'board-completed', title: 'Completed', item: [] },
      { id: 'board-cancelled', title: 'Cancelled', item: [] }
    ];
  }

  // datepicker init
  if (datePicker) {
    datePicker.flatpickr({
      monthSelectorType: 'static',
      altInput: true,
      altFormat: 'j F, Y',
      dateFormat: 'Y-m-d'
    });
  }

  // select2
  if (select2.length) {
    function renderLabels(option) {
      if (!option.id) {
        return option.text;
      }
      var $badge = "<div class='badge " + $(option.element).data('color') + " rounded-pill'> " + option.text + '</div>';
      return $badge;
    }

    select2.each(function () {
      var $this = $(this);
      $this.wrap("<div class='position-relative'></div>").select2({
        placeholder: 'Select Label',
        dropdownParent: $this.parent(),
        templateResult: renderLabels,
        templateSelection: renderLabels,
        escapeMarkup: function (es) {
          return es;
        }
      });
    });
  }

  // Comment editor
  if (commentEditor) {
    new Quill(commentEditor, {
      modules: {
        toolbar: '.comment-toolbar'
      },
      placeholder: 'Write a Comment... ',
      theme: 'snow'
    });
  }

  // Render board dropdown
  function renderBoardDropdown() {
    return (
      "<div class='dropdown'>" +
      "<i class='dropdown-toggle ti ti-dots-vertical cursor-pointer' id='board-dropdown' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'></i>" +
      "<div class='dropdown-menu dropdown-menu-end' aria-labelledby='board-dropdown'>" +
      "<a class='dropdown-item delete-board' href='javascript:void(0)'> <i class='ti ti-trash ti-xs' me-1></i> <span class='align-middle'>Delete</span></a>" +
      "<a class='dropdown-item' href='javascript:void(0)'><i class='ti ti-edit ti-xs' me-1></i> <span class='align-middle'>Rename</span></a>" +
      "<a class='dropdown-item' href='javascript:void(0)'><i class='ti ti-archive ti-xs' me-1></i> <span class='align-middle'>Archive</span></a>" +
      '</div>' +
      '</div>'
    );
  }
  
  // Render item dropdown
  function renderDropdown(taskId) {
    const itemEl = document.querySelector(`[data-eid="${taskId}"]`);
    console.log(taskId);
    const historyUrl = itemEl?.getAttribute('data-task-history-url') || '#';
    const taskShowUrl = itemEl?.getAttribute('data-task-show-url') || '#';
    return (
      "<div class='dropdown kanban-tasks-item-dropdown'>" +
      "<i class='dropdown-toggle ti ti-dots-vertical' id='kanban-tasks-item-dropdown' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'></i>" +
      "<div class='dropdown-menu dropdown-menu-end' aria-labelledby='kanban-tasks-item-dropdown'>" +
      "<a class='dropdown-item' href='" + historyUrl + "'>Task History</a>" +
      "<a class='dropdown-item' href='" + taskShowUrl + "'>Show Task</a>" +
      "<a class='dropdown-item delete-task' href='javascript:void(0)'>Delete Task</a>" +
      '</div>' +
      '</div>'
    );
  }
  
  // Render header with priority badge
  function renderHeader(color, text, subTasks, taskId) {
    console.log('render header  '+taskId);
    return (
      "<div class='d-flex justify-content-between flex-wrap align-items-center mb-2 pb-1'>" +
        "<div class='item-badges'> " +
          "<div class='badge rounded-pill bg-label-" +
            color +
            "'>" +
            text +
          "</div>" +
          (subTasks
          ? "<span class='badge rounded-pill bg-info text-dark ms-1' " +
              "data-bs-toggle='tooltip' data-bs-placement='top' title='Total Sub-tasks: " + subTasks + "'>" +
              subTasks +
            "</span>"
          : '') +
        "</div>" +
        renderDropdown(taskId) +
      "</div>"
    );
  }


  // NEW: Render Parent Task Info
  function renderParentTask(parentId, parentTitle) {
    if (!parentId || !parentTitle) return '';
    return (
      "<div class='parent-task-info mb-2'>" +
      "<small class='text-muted d-flex align-items-center'>" +
      "<i class='ti ti-arrow-up-right ti-xs me-1'></i>" +
      "<span>Sub-task of: <strong>" + parentTitle + "</strong></span>" +
      "</small>" +
      "</div>"
    );
  }

  // NEW: Render Task Creator
  function renderTaskCreator(createdBy) {
    if (!createdBy) return '';
    return (
      "<div class='task-creator mb-2'>" +
      "<small class='text-muted d-flex align-items-center'>" +
      "<i class='ti ti-user-plus ti-xs me-1'></i>" +
      "<span>Assigned by <strong>" + createdBy + "</strong></span>" +
      "</small>" +
      "</div>"
    );
  }

  // NEW: Render Progress Bar with board-specific styling
  function renderProgressBar(progress, boardId) {
    if (progress === undefined || progress === null) progress = 0;
    
    // Determine progress bar color based on board status and percentage
    let progressColor = 'bg-success';
    let progressText = 'Progress';
    
    if (boardId && boardId.includes('cancelled')) {
      progressColor = 'bg-secondary';
      progressText = 'Cancelled at';
    } else if (boardId && boardId.includes('completed')) {
      progressColor = 'bg-success';
      progressText = 'Completed';
    } else if (boardId && boardId.includes('running')) {
      progressColor = progress < 50 ? 'bg-warning' : 'bg-info';
      progressText = 'Running';
    } else if (boardId && boardId.includes('activated')) {
      progressColor = progress < 30 ? 'bg-danger' : 'bg-warning';
      progressText = 'Started';
    } else {
      // Default color logic
      if (progress < 30) {
        progressColor = 'bg-danger';
      } else if (progress < 70) {
        progressColor = 'bg-warning';
      }
    }
    
    return (
      "<div class='task-progress mb-2'>" +
      "<div class='d-flex justify-content-between align-items-center mb-1'>" +
      "<small class='text-muted fw-medium'>" + progressText + "</small>" +
      "<small class='text-muted'>" + progress + "%</small>" +
      "</div>" +
      "<div class='progress' style='height: 4px;'>" +
      "<div class='progress-bar " + progressColor + "' role='progressbar' style='width: " + progress + "%' " +
      "aria-valuenow='" + progress + "' aria-valuemin='0' aria-valuemax='100'></div>" +
      "</div>" +
      "</div>"
    );
  }

  // Render description
  function renderDescription(description) {
    if (!description) return '';

    const words = description.trim().split(/\s+/);
    const isLong = words.length > 100;
    const shortDescription = isLong ? words.slice(0, 100).join(' ') + '...' : description;

    return (
      "<div class='kanban-description text-muted small mb-2'>" +
      "<p class='mb-0' style='font-size: 0.75rem; line-height: 1.2;'>" + shortDescription + "</p>" +
      "</div>"
    );
  }


  // Render avatar (updated to handle different avatar sources)
  function renderAvatar(images, pullUp, size, margin, members) {
    var $transition = pullUp ? ' pull-up' : '',
      $size = size ? 'avatar-' + size + '' : '',
      member = members == undefined ? [] : (Array.isArray(members) ? members : members.split(','));

    if (!images || images.length === 0) return '';

    const imageArray = Array.isArray(images) ? images : images.split(',');

    return imageArray
      .map(function (img, index, arr) {
        var $margin = margin && index !== arr.length - 1 ? ' me-' + margin + '' : '';
        var memberName = member[index] || 'User';

        // Check if it's initials (2 characters and no URL indicators)
        if (img.length <= 3 && !img.includes('http') && !img.includes('/') && !img.includes('.')) {
          // Render initials avatar
          return (
            "<div class='avatar " +
            $size +
            $margin +
            "'" +
            "data-bs-toggle='tooltip' data-bs-placement='top'" +
            "title='" +
            memberName +
            "'" +
            '>' +
            "<span class='avatar-initial rounded-circle bg-label-primary " +
            $transition +
            "'>" + img + "</span>" +
            '</div>'
          );
        } else {
          // Handle image URL
          var imageSrc = img.includes('http') ? img : `${baseUrl}/storage/avatars/${img}`;
          
          return (
            "<div class='avatar " +
            $size +
            $margin +
            "'" +
            "data-bs-toggle='tooltip' data-bs-placement='top'" +
            "title='" +
            memberName +
            "'" +
            '>' +
            "<img src='" +
            imageSrc +
            "' alt='Avatar' class='rounded-circle " +
            $transition +
            "' onerror=\"this.src='" + baseUrl + "/assets/img/avatars/default-avatar.png'\">" +
            '</div>'
          );
        }
      })
      .join(' ');
  }

  // ENHANCED: Render footer with detailed tooltips
  function renderFooter(attachments, comments, assigned, members, deadline) {
    // Default values
    attachments = attachments || '0';
    comments = comments || '0';
    deadline = deadline || 'null';

    // Create tooltip texts
    const attachmentTooltip = attachments === '1' ? '1 attachment' : attachments + ' attachments';
    const commentTooltip = comments === '1' ? '1 comment' : comments + ' comments';
    const dueDateTooltip = 'Deadline: ' + deadline;

    // Start building the footer
    let footerHTML = `
      <div class='enhanced-footer'>
        <div class='d-flex justify-content-between align-items-center flex-wrap mt-2 pt-1'>
          <div class='d-flex flex-wrap'>
            <span class='d-flex align-items-center me-2 mb-1' data-bs-toggle='tooltip' data-bs-placement='top' title='${attachmentTooltip}'>
              <i class='ti ti-paperclip ti-xs me-1 text-muted'></i>
              <small class='text-muted'>${attachments}</small>
            </span>
            <span class='d-flex align-items-center me-2 mb-1' data-bs-toggle='tooltip' data-bs-placement='top' title='${commentTooltip}'>
              <i class='ti ti-message-dots ti-xs me-1 text-muted'></i>
              <small class='text-muted'>${comments}</small>
            </span>`;

    // Conditionally add due date if it's not 'null'
    if (deadline !== 'null') {
      footerHTML += `
            <span class='d-flex align-items-center me-2 mb-1' data-bs-toggle='tooltip' data-bs-placement='top' title='${dueDateTooltip}'>
              <i class='ti ti-calendar ti-xs me-1 text-muted'></i>
              <small class='text-muted'>${deadline}</small>
            </span>`;
    }

    // Add avatars section and closing tags
    footerHTML += `
          </div>
          <div class='avatar-group d-flex align-items-center assigned-avatar'>
            ${renderAvatar(assigned, true, 'xs', null, members)}
          </div>
        </div>
      </div>`;

    return footerHTML;
  }


  // API Helper Functions
  async function createTaskAPI(title, boardId, description = '') {
    try {
      const response = await fetch(`${baseUrl}/api/kanban/tasks`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
          title: title,
          board_id: boardId,
          description: description
        })
      });

      const result = await response.json();
      if (!result.success) {
        throw new Error(result.message);
      }
      return result.task;
    } catch (error) {
      console.error('Error creating task:', error);
      throw error;
    }
  }

  async function updateTaskStatusAPI(taskId, newStatus) {
    try {
      const response = await fetch(`${baseUrl}/api/kanban/tasks/status`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
          task_id: taskId,
          new_status: newStatus
        })
      });

      const result = await response.json();
      if (!result.success) {
        throw new Error(result.message);
      }
      return result;
    } catch (error) {
      console.error('Error updating task status:', error);
      throw error;
    }
  }

  async function deleteTaskAPI(taskId) {
    try {
      const response = await fetch(`${baseUrl}/api/kanban/tasks`, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
          task_id: taskId
        })
      });

      const result = await response.json();
      if (!result.success) {
        throw new Error(result.message);
      }
      return result;
    } catch (error) {
      console.error('Error deleting task:', error);
      throw error;
    }
  }
  
  // Init kanban
  const kanban = new jKanban({
    element: '.kanban-wrapper',
    gutter: '15px',
    widthBoard: '300px', // Increased width for better content display
    dragItems: true,
    boards: boards,
    dragBoards: true,
    addItemButton: true,
    
    // Handle item click
    click: function (el) {
      let element = el;
      let title = element.getAttribute('data-eid')
          ? element.querySelector('.kanban-text').textContent
          : element.textContent,
        date = element.getAttribute('data-deadline'),
        dateObj = new Date(),
        year = dateObj.getFullYear(),
        dateToUse = date
          ? date + ', ' + year
          : dateObj.getDate() + ' ' + dateObj.toLocaleString('en', { month: 'long' }) + ', ' + year,
        label = element.getAttribute('data-badge-text'),
        avatars = element.getAttribute('data-assigned');

      let description = element.getAttribute('data-description') || '';

      // Show kanban offcanvas
      kanbanOffcanvas.show();

      // To get data on sidebar
      kanbanSidebar.querySelector('#title').value = title;
      kanbanSidebar.querySelector('#description').value = description;
      kanbanSidebar.querySelector('#deadline').nextSibling.value = dateToUse;

      // ! Using jQuery method to get sidebar due to select2 dependency
      $('.kanban-update-item-sidebar').find(select2).val(label).trigger('change');

      // Remove & Update assigned
      kanbanSidebar.querySelector('.assigned').innerHTML = '';
      kanbanSidebar
        .querySelector('.assigned')
        .insertAdjacentHTML(
          'afterbegin',
          renderAvatar(avatars, false, 'xs', '1', el.getAttribute('data-members')) +
            "<div class='avatar avatar-xs ms-1'>" +
            "<span class='avatar-initial rounded-circle bg-label-secondary'><i class='ti ti-plus ti-xs text-heading'></i></span>" +
            '</div>'
        );
    },

    // Handle drag and drop
    dropEl: async function (el, target, source, sibling) {
      try {
        const taskId = el.getAttribute('data-eid');
        const newBoardId = target.parentNode.getAttribute('data-id');

        await updateTaskStatusAPI(taskId, newBoardId);
        console.log('Task status updated successfully');
        // Show success toast
        Swal.fire({
          icon: 'success',
          title: 'Task status updated',
          toast: true,
          position: 'top-end',
          timer: 2000,
          showConfirmButton: false
        });
      } catch (error) {
        console.error('Failed to update task status:', error);
        // Revert the move if API call fails
        source.appendChild(el);
      }
    },

    // Handle add new task
    buttonClick: function (el, boardId) {
      const addNew = document.createElement('form');
      addNew.setAttribute('class', 'new-item-form');
      addNew.innerHTML =
        '<div class="mb-3">' +
        '<textarea class="form-control add-new-item" rows="2" placeholder="Add Task Title" autofocus required></textarea>' +
        '</div>' +
        '<div class="mb-3">' +
        '<textarea class="form-control add-new-description" rows="1" placeholder="Task Description (Optional)"></textarea>' +
        '</div>' +
        '<div class="mb-3">' +
        '<button type="submit" class="btn btn-primary btn-sm me-2">Add Task</button>' +
        '<button type="button" class="btn btn-label-secondary btn-sm cancel-add-item">Cancel</button>' +
        '</div>';
      kanban.addForm(boardId, addNew);

      addNew.addEventListener('submit', async function (e) {
        e.preventDefault();
        
        const titleInput = e.target.querySelector('.add-new-item');
        const descriptionInput = e.target.querySelector('.add-new-description');
        const submitBtn = e.target.querySelector('button[type="submit"]');
        
        // Disable submit button and show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Adding...';

        try {
          // Create task via API
          const newTask = await createTaskAPI(
            titleInput.value,
            boardId,
            descriptionInput.value
          );

          // Add task to kanban board
          kanban.addElement(boardId, {
            title: "<span class='kanban-text'>" + newTask.title + '</span>',
            id: newTask.id
          });

          // Get the newly added element and enhance it
          const newTaskElement = document.querySelector(`[data-eid="${newTask.id}"]`);
          if (newTaskElement) {
            // Set all data attributes
            newTaskElement.setAttribute('data-description', newTask.description || '');
            newTaskElement.setAttribute('data-progress', newTask.progress || '0');
            newTaskElement.setAttribute('data-created-by', newTask['created-by'] || '');
            newTaskElement.setAttribute('data-badge-text', newTask['badge-text'] || 'Medium');
            newTaskElement.setAttribute('data-badge', newTask.badge || 'warning');
            newTaskElement.setAttribute('data-deadline', newTask['deadline'] || '');
            newTaskElement.setAttribute('data-attachments', newTask.attachments || '0');
            newTaskElement.setAttribute('data-comments', newTask.comments || '0');
            newTaskElement.setAttribute('data-assigned', newTask.assigned ? newTask.assigned.join(',') : '');
            newTaskElement.setAttribute('data-members', newTask.members ? newTask.members.join(',') : '');
            // Routes for Task History and Task Show
            newTaskElement.setAttribute('data-task-history-url', newTask['task-history-url'] || '');
            newTaskElement.setAttribute('data-task-show-url', newTask['task-show-url'] || '');
            
            if (newTask.parent_id) {
              newTaskElement.setAttribute('data-parent-id', newTask.parent_id);
              newTaskElement.setAttribute('data-parent-title', newTask.parent_title || '');
            }

            // Rebuild the task content
            newTaskElement.innerHTML = '';
            
            let cardContent = '';
            
            // Header with badge and dropdown
            if (newTask.badge && newTask['badge-text']) {
              cardContent += renderHeader(newTask.badge, newTask['badge-text'], newTask.sub_tasks, newTask.id);
            }
            
            // Title
            cardContent += "<div class='kanban-text fw-medium mb-1'>" + newTask.title + '</div>';
            
            // Parent Task Info
            if (newTask.parent_id && newTask.parent_title) {
              cardContent += renderParentTask(newTask.parent_id, newTask.parent_title);
            }
            
            // Task Creator
            cardContent += renderTaskCreator(newTask['created-by']);
            
            // Description
            cardContent += renderDescription(newTask.description);
            
            // Progress Bar
            cardContent += renderProgressBar(newTask.progress, boardId);
            
            // Insert content
            newTaskElement.insertAdjacentHTML('afterbegin', cardContent);
            
            // Footer
            newTaskElement.insertAdjacentHTML(
              'beforeend',
              renderFooter(
                newTask.attachments,
                newTask.comments,
                newTask.assigned,
                newTask.members,
                newTask['deadline']
              )
            );

            // Add event listeners for dropdowns
            const dropdown = newTaskElement.querySelector('.kanban-tasks-item-dropdown');
            if (dropdown) {
              dropdown.addEventListener('click', function (e) {
                e.stopPropagation();
              });
            }

            // Add delete functionality
            const deleteBtn = newTaskElement.querySelector('.delete-task');
            if (deleteBtn) {
              deleteBtn.addEventListener('click', async function (e) {
                e.preventDefault(); e.stopPropagation();
                Swal.fire({
                  title: 'Are you sure?', text: "You won't be able to revert this!",
                  icon: 'warning', showCancelButton: true, confirmButtonText: 'Yes, delete it!',
                  customClass: { confirmButton: 'btn btn-primary me-2', cancelButton: 'btn btn-label-secondary' },
                  buttonsStyling: false, showLoaderOnConfirm: true,
                  preConfirm: async () => { try { await deleteTaskAPI(newTask.id); } catch (error) {
                    Swal.showValidationMessage('Delete failed: ' + (error.message || 'Unknown error'));
                  } }
                }).then((result) => {
                  if (result.isConfirmed) {
                    kanban.removeElement(newTask.id);
                    Swal.fire({ icon: 'success', title: 'Deleted!', text: 'Task has been deleted.',
                      customClass: { confirmButton: 'btn btn-success' } });
                  }
                });
              });
            }

            // Re-initialize tooltips for new task
            const tooltips = newTaskElement.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltips.forEach(function (tooltip) {
              new bootstrap.Tooltip(tooltip);
            });
          }

          // Remove the form
          addNew.remove();

        } catch (error) {
          console.error('Failed to create task:', error);
          alert('Failed to create task. Please try again.');
          
          // Re-enable submit button
          submitBtn.disabled = false;
          submitBtn.innerHTML = 'Add Task';
        }
      });

      // Remove form on clicking cancel button
      addNew.querySelector('.cancel-add-item').addEventListener('click', function (e) {
        addNew.remove();
      });
    }
  });

  // Kanban Wrapper scrollbar
  if (kanbanWrapper) {
    new PerfectScrollbar(kanbanWrapper);
  }

  const kanbanContainer = document.querySelector('.kanban-container'),
    kanbanTitleBoard = [].slice.call(document.querySelectorAll('.kanban-title-board')),
    kanbanItem = [].slice.call(document.querySelectorAll('.kanban-item'));

  // ENHANCED: Render custom items with all new features
  if (kanbanItem) {
    kanbanItem.forEach(function (el) {
      const element = "<div class='kanban-text fw-medium mb-1'>" + el.textContent + '</div>';
      let img = '';
      if (el.getAttribute('data-image') !== null) {
        img =
          "<img class='img-fluid rounded mb-2' src='" +
          baseUrl +
          '/storage/images/' +
          el.getAttribute('data-image') +
          "'>";
      }
      
      // Get all data attributes
      const description = el.getAttribute('data-description') || '';
      const progress = el.getAttribute('data-progress') || '0';
      const createdBy = el.getAttribute('data-created-by') || '';
      const parentId = el.getAttribute('data-parent_id') || '';
      const parentTitle = el.getAttribute('data-parent_title') || '';
      
      el.textContent = '';
      
      // Build the complete card content
      let cardContent = '';
      
      // Header with badge and dropdown
      if (el.getAttribute('data-badge') !== undefined && el.getAttribute('data-badge-text') !== undefined) {
        cardContent += renderHeader(el.getAttribute('data-badge'), el.getAttribute('data-badge-text'), el.getAttribute('data-sub_tasks'), el.getAttribute('data-eid'));
      }
      
      // Image if exists
      cardContent += img;
      
      // Title
      cardContent += element;
      
      // Parent Task Info
      cardContent += renderParentTask(parentId, parentTitle);
      
      // Task Creator
      cardContent += renderTaskCreator(createdBy);
      
      // Description
      cardContent += renderDescription(description);
      
      // Progress Bar with board context
      cardContent += renderProgressBar(progress, el.closest('.kanban-board')?.getAttribute('data-id'));
      
      // Insert all content
      el.insertAdjacentHTML('afterbegin', cardContent);
      
      // Footer with enhanced tooltips
      if (
        el.getAttribute('data-comments') !== undefined ||
        el.getAttribute('data-deadline') !== undefined ||
        el.getAttribute('data-assigned') !== undefined
      ) {
        el.insertAdjacentHTML(
          'beforeend',
          renderFooter(
            el.getAttribute('data-attachments'),
            el.getAttribute('data-comments'),
            el.getAttribute('data-assigned'),
            el.getAttribute('data-members'),
            el.getAttribute('data-deadline')
          )
        );
      }
    });
  }

  // Enhanced Custom CSS for better styling with board-specific colors
  const customCSS = `
    <style>
      .kanban-item {
        background: #fff;
        border: 1px solid rgba(67, 89, 113, 0.1);
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
        margin-bottom: 12px;
        padding: 16px;
      }
      
      .kanban-item:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-1px);
      }
      
      /* Board-specific styling */
      .kanban-board[data-id*="activated"] {
        background: rgba(255, 62, 29, 0.05);
        border-left: 3px solid #ff3e1d;
      }
      
      .kanban-board[data-id*="running"] {
        background: rgba(13, 110, 253, 0.05);
        border-left: 3px solid #0d6efd;
      }
      
      .kanban-board[data-id*="completed"] {
        background: rgba(25, 135, 84, 0.05);
        border-left: 3px solid #198754;
      }
      
      .kanban-board[data-id*="cancelled"] {
        background: rgba(108, 117, 125, 0.05);
        border-left: 3px solid #6c757d;
      }
      
      /* Board-specific item styling */
      .kanban-board[data-id*="activated"] .kanban-item {
        border-left: 2px solid rgba(255, 62, 29, 0.3);
      }
      
      .kanban-board[data-id*="running"] .kanban-item {
        border-left: 2px solid rgba(13, 110, 253, 0.3);
      }
      
      .kanban-board[data-id*="completed"] .kanban-item {
        border-left: 2px solid rgba(25, 135, 84, 0.3);
      }
      
      .kanban-board[data-id*="cancelled"] .kanban-item {
        border-left: 2px solid rgba(108, 117, 125, 0.3);
        opacity: 0.8;
      }
      
      .task-creator small, .parent-task-info small {
        font-size: 0.7rem;
      }
      
      .parent-task-info {
        background: rgba(13, 110, 253, 0.1);
        border-radius: 4px;
        padding: 4px 8px;
      }
      
      .task-progress .progress {
        border-radius: 2px;
        background-color: rgba(67, 89, 113, 0.1);
      }
      
      .task-progress .progress-bar {
        transition: width 0.3s ease;
      }
      
      .enhanced-footer {
        border-top: 1px solid rgba(67, 89, 113, 0.05);
        margin-top: 12px;
        padding-top: 8px;
      }
      
      .enhanced-footer small {
        font-size: 0.7rem;
        font-weight: 500;
      }
      
      .kanban-text {
        font-size: 0.875rem;
        line-height: 1.3;
        color: #566a7f;
      }
      
      .kanban-description p {
        color: #8592a3;
      }
      
      .kanban-board {
        border-radius: 8px;
        padding: 16px 12px;
      }
      
      .kanban-title-board {
        font-weight: 600;
        color: #566a7f;
        margin-bottom: 16px;
        font-size: 1rem;
      }
      
      /* Status-specific board titles */
      .kanban-board[data-id*="activated"] .kanban-title-board {
        color: #ff3e1d;
      }
      
      .kanban-board[data-id*="running"] .kanban-title-board {
        color: #0d6efd;
      }
      
      .kanban-board[data-id*="completed"] .kanban-title-board {
        color: #198754;
      }
      
      .kanban-board[data-id*="cancelled"] .kanban-title-board {
        color: #6c757d;
      }
      
      .new-item-form {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 12px;
      }
      
      .new-item-form .form-control {
        border: 1px solid #dee2e6;
        font-size: 0.875rem;
      }
      
      .spinner-border-sm {
        width: 0.875rem;
        height: 0.875rem;
      }
    </style>
  `;
  
  // Inject custom CSS
  document.head.insertAdjacentHTML('beforeend', customCSS);

  // To initialize tooltips for rendered items
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // prevent sidebar to open onclick dropdown buttons of tasks
  const tasksItemDropdown = [].slice.call(document.querySelectorAll('.kanban-tasks-item-dropdown'));
  if (tasksItemDropdown) {
    tasksItemDropdown.forEach(function (e) {
      e.addEventListener('click', function (el) {
        el.stopPropagation();
      });
    });
  }

  // Add delete functionality to existing tasks
  const deleteTaskButtons = document.querySelectorAll('.delete-task');
  deleteTaskButtons.forEach(function(btn) {
    btn.addEventListener('click', async function(e) {
      e.preventDefault(); e.stopPropagation();
      const taskId = this.closest('.kanban-item').getAttribute('data-eid');
      Swal.fire({
        title: 'Are you sure?', text: "You won't be able to revert this!",
        icon: 'warning', showCancelButton: true, confirmButtonText: 'Yes, delete it!',
        customClass: { confirmButton: 'btn btn-primary me-2', cancelButton: 'btn btn-label-secondary' },
        buttonsStyling: false, showLoaderOnConfirm: true,
        preConfirm: async () => { try { await deleteTaskAPI(taskId); } catch (error) {
          Swal.showValidationMessage('Delete failed: ' + (error.message || 'Unknown error'));
        } }
      }).then((result) => {
        if (result.isConfirmed) {
          kanban.removeElement(taskId);
          Swal.fire({ icon: 'success', title: 'Deleted!', text: 'Task has been deleted.',
            customClass: { confirmButton: 'btn btn-success' } });
        }
      });
    });
  });

  // Toggle add new input and actions add-new-btn
  if (kanbanAddBoardBtn) {
    kanbanAddBoardBtn.addEventListener('click', () => {
      kanbanAddNewInput.forEach(el => {
        el.value = '';
        el.classList.toggle('d-none');
      });
    });
  }

  // Render add new inline with boards
  if (kanbanContainer) {
    kanbanContainer.appendChild(kanbanAddNewBoard);
  }

  // Makes kanban title editable for rendered boards
  if (kanbanTitleBoard) {
    kanbanTitleBoard.forEach(function (elem) {
      elem.addEventListener('mouseenter', function () {
        this.contentEditable = 'true';
      });

      // Appends delete icon with title
      elem.insertAdjacentHTML('afterend', renderBoardDropdown());
    });
  }

  // To delete Board for rendered boards
  const deleteBoards = [].slice.call(document.querySelectorAll('.delete-board'));
  if (deleteBoards) {
    deleteBoards.forEach(function (elem) {
      elem.addEventListener('click', function () {
        const id = this.closest('.kanban-board').getAttribute('data-id');
        kanban.removeBoard(id);
      });
    });
  }

  // Cancel btn add new input
  const cancelAddNew = document.querySelector('.kanban-add-board-cancel-btn');
  if (cancelAddNew) {
    cancelAddNew.addEventListener('click', function () {
      kanbanAddNewInput.forEach(el => {
        el.classList.toggle('d-none');
      });
    });
  }

  // Add new board (if needed)
  if (kanbanAddNewBoard) {
    kanbanAddNewBoard.addEventListener('submit', function (e) {
      e.preventDefault();
      const thisEle = this,
        value = thisEle.querySelector('.form-control').value,
        id = value.replace(/\s+/g, '-').toLowerCase();
      kanban.addBoards([
        {
          id: id,
          title: value
        }
      ]);

      // Additional board setup code...
      const kanbanBoardLastChild = document.querySelectorAll('.kanban-board:last-child')[0];
      if (kanbanBoardLastChild) {
        const header = kanbanBoardLastChild.querySelector('.kanban-title-board');
        header.insertAdjacentHTML('afterend', renderBoardDropdown());

        kanbanBoardLastChild.querySelector('.kanban-title-board').addEventListener('mouseenter', function () {
          this.contentEditable = 'true';
        });
      }

      const deleteNewBoards = kanbanBoardLastChild.querySelector('.delete-board');
      if (deleteNewBoards) {
        deleteNewBoards.addEventListener('click', function () {
          const id = this.closest('.kanban-board').getAttribute('data-id');
          kanban.removeBoard(id);
        });
      }

      if (kanbanAddNewInput) {
        kanbanAddNewInput.forEach(el => {
          el.classList.add('d-none');
        });
      }

      if (kanbanContainer) {
        kanbanContainer.appendChild(kanbanAddNewBoard);
      }
    });
  }

  // Clear comment editor on close
  kanbanSidebar.addEventListener('hidden.bs.offcanvas', function () {
    kanbanSidebar.querySelector('.ql-editor').firstElementChild.innerHTML = '';
  });

  // Re-init tooltip when offcanvas opens(Bootstrap bug)
  if (kanbanSidebar) {
    kanbanSidebar.addEventListener('shown.bs.offcanvas', function () {
      const tooltipTriggerList = [].slice.call(kanbanSidebar.querySelectorAll('[data-bs-toggle="tooltip"]'));
      tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });
    });
  }
})();






