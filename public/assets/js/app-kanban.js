
/**
 * App Kanban - Optimized & Organized
 * - Modular utilities and API layer
 * - Safer DOM updates with escaping
 * - Consistent data-attributes handling
 * - Event delegation to reduce listeners
 * - Reusable render helpers
 * - Preserves all current features
 */

'use strict';

(async function () {
  // -----------------------------
  // Utilities
  // -----------------------------
  const qs = (sel, ctx = document) => ctx.querySelector(sel);
  const qsa = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));
  const on = (el, evt, selOrHandler, handler) => {
    // Delegated vs direct
    if (typeof selOrHandler === 'function') {
      el.addEventListener(evt, selOrHandler);
    } else {
      el.addEventListener(evt, (e) => {
        const target = e.target.closest(selOrHandler);
        if (target && el.contains(target)) handler(e, target);
      });
    }
  };
  const toInt = (val, def = 0) => {
    const n = parseInt(val, 10);
    return Number.isFinite(n) ? n : def;
  };
  const escapeHTML = (str) => {
    if (str === null || str === undefined) return '';
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  };
  const safeAttr = (el, name, fallback = '') => el?.getAttribute?.(name) ?? fallback;
  const initTooltips = (ctx = document) => {
    qsa('[data-bs-toggle="tooltip"]', ctx).forEach((t) => new bootstrap.Tooltip(t));
  };

  // -----------------------------
  // Board Order Persistence (localStorage with TTL)
  // -----------------------------
  const debounce = (fn, wait = 300) => {
    let t;
    return (...args) => {
      clearTimeout(t);
      t = setTimeout(() => fn(...args), wait);
    };
  };

  const BOARD_ORDER = {
    key: 'kanban_board_order',
    ttl: 1000 * 60 * 60 * 4, // 4 hours
    save(order) {
      try {
        localStorage.setItem(this.key, JSON.stringify({ order, ts: Date.now() }));
      } catch (_) {}
    },
    load() {
      try {
        const raw = localStorage.getItem(this.key);
        if (!raw) return null;
        const parsed = JSON.parse(raw);
        if (!parsed || !Array.isArray(parsed.order) || typeof parsed.ts !== 'number') {
          localStorage.removeItem(this.key);
          return null;
        }
        if (Date.now() - parsed.ts > this.ttl) {
          localStorage.removeItem(this.key);
          return null;
        }
        return parsed.order;
      } catch (_) {
        return null;
      }
    },
    clear() {
      try { localStorage.removeItem(this.key); } catch (_) {}
    }
  };

  const getCurrentBoardOrder = () => qsa('.kanban-board', kanbanContainer).map(b => b.getAttribute('data-id')).filter(Boolean);

  const applyBoardOrder = (order) => {
    if (!kanbanContainer || !Array.isArray(order)) return;
    const boardsEls = qsa('.kanban-board', kanbanContainer);
    const map = new Map(boardsEls.map(el => [el.getAttribute('data-id'), el]));
    order.forEach((id) => {
      const el = map.get(id);
      if (el) {
        kanbanContainer.insertBefore(el, kanbanAddNewBoard || null);
        map.delete(id);
      }
    });
    map.forEach((el) => kanbanContainer.insertBefore(el, kanbanAddNewBoard || null));
  };

  const persistCurrentBoardOrder = () => {
    const order = getCurrentBoardOrder();
    if (order && order.length) BOARD_ORDER.save(order);
  };

  const observeBoardsOrder = () => {
    if (!kanbanContainer) return;
    const handler = debounce(() => persistCurrentBoardOrder(), 200);
    const observer = new MutationObserver(() => handler());
    observer.observe(kanbanContainer, { childList: true });
  };

  // -----------------------------
  // Constants / Elements
  // -----------------------------
  const baseUrl = window.location.origin;
  const csrfToken = qs('meta[name="csrf-token"]')?.getAttribute('content');

  const kanbanSidebar = qs('.kanban-update-item-sidebar');
  const kanbanWrapper = qs('.kanban-wrapper');
  const kanbanContainer = qs('.kanban-container');
  const commentEditor = qs('.comment-editor');
  const kanbanAddNewBoard = qs('.kanban-add-new-board');
  const kanbanAddNewInput = qsa('.kanban-add-board-input');
  const kanbanAddBoardBtn = qs('.kanban-add-board-btn');
  const datePickerEl = qs('#deadline');

  // jQuery-dependent
  const $select2 = window.$ ? window.$('.select2') : null; // select2 requires jQuery

  // Offcanvas
  const kanbanOffcanvas = kanbanSidebar ? new bootstrap.Offcanvas(kanbanSidebar) : null;
  // Feature flag: temporarily disable opening sidebar on item click
  const OPEN_SIDEBAR_ON_ITEM_CLICK = false;

  // -----------------------------
  // API Client
  // -----------------------------
  const api = (() => {
    const headers = () => ({
      'Content-Type': 'application/json',
      Accept: 'application/json',
      'X-CSRF-TOKEN': csrfToken || ''
    });

    const request = async (method, url, body) => {
      const res = await fetch(url, {
        method,
        headers: headers(),
        body: body ? JSON.stringify(body) : undefined
      });
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const data = await res.json().catch(() => ({}));
      if (data && data.success === false) throw new Error(data.message || 'Unexpected API error');
      return data;
    };

    return {
      fetchBoards: () => request('GET', `${baseUrl}/api/kanban/data`),
      createTask: (payload) => request('POST', `${baseUrl}/api/kanban/tasks`, payload),
      updateTaskStatus: (task_id, new_status) => request('PUT', `${baseUrl}/api/kanban/tasks/status`, { task_id, new_status }),
      deleteTask: (task_id) => request('DELETE', `${baseUrl}/api/kanban/tasks`, { task_id })
    };
  })();

  // -----------------------------
  // 3rd-party Initializers
  // -----------------------------
  const initFlatpickr = () => {
    if (!datePickerEl) return;
    const options = {
      monthSelectorType: 'static',
      altInput: true,
      altFormat: 'j F, Y',
      dateFormat: 'Y-m-d'
    };
    if (typeof window.flatpickr === 'function') {
      window.flatpickr(datePickerEl, options);
    } else if (datePickerEl.flatpickr) {
      datePickerEl.flatpickr(options);
    }
  };

  const initSelect2 = () => {
    if (!$select2 || !$select2.length) return;

    function renderLabels(option) {
      if (!option.id) return option.text;
      const color = window.$(option.element).data('color') || 'bg-secondary';
      return `<div class='badge ${color} rounded-pill'> ${escapeHTML(option.text)} </div>`;
    }

    $select2.each(function () {
      const $this = window.$(this);
      $this
        .wrap("<div class='position-relative'></div>")
        .select2({
          placeholder: 'Select Label',
          dropdownParent: $this.parent(),
          templateResult: renderLabels,
          templateSelection: renderLabels,
          escapeMarkup: (es) => es
        });
    });
  };

  const initQuill = () => {
    if (!commentEditor) return;
    new Quill(commentEditor, {
      modules: { toolbar: '.comment-toolbar' },
      placeholder: 'Write a Comment... ',
      theme: 'snow'
    });
  };

  // -----------------------------
  // Render Helpers
  // -----------------------------
  const renderBoardDropdown = () => `
    <div class='dropdown'>
      <i class='dropdown-toggle ti ti-dots-vertical cursor-pointer' id='board-dropdown' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'></i>
      <div class='dropdown-menu dropdown-menu-end' aria-labelledby='board-dropdown'>
        <a class='dropdown-item delete-board' href='javascript:void(0)'>
          <i class='ti ti-trash ti-xs' me-1></i>
          <span class='align-middle'>Delete</span>
        </a>
        <a class='dropdown-item' href='javascript:void(0)'>
          <i class='ti ti-edit ti-xs' me-1></i>
          <span class='align-middle'>Rename</span>
        </a>
        <a class='dropdown-item' href='javascript:void(0)'>
          <i class='ti ti-archive ti-xs' me-1></i>
          <span class='align-middle'>Archive</span>
        </a>
      </div>
    </div>`;

  const renderDropdown = (taskEl) => {
    const historyUrl = safeAttr(taskEl, 'data-task-history-url', '#');
    const taskShowUrl = safeAttr(taskEl, 'data-task-show-url', '#');
    return `
      <div class='dropdown kanban-tasks-item-dropdown'>
        <i class='dropdown-toggle ti ti-dots-vertical' id='kanban-tasks-item-dropdown' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'></i>
        <div class='dropdown-menu dropdown-menu-end' aria-labelledby='kanban-tasks-item-dropdown'>
          <a class='dropdown-item' href='${historyUrl}'>Task History</a>
          <a class='dropdown-item' href='${taskShowUrl}'>Show Task</a>
          <a class='dropdown-item delete-task' href='javascript:void(0)'>Delete Task</a>
        </div>
      </div>`;
  };

  const renderHeader = (color, text, subTasks, taskEl) => {
    const c = escapeHTML(color || 'secondary');
    const t = escapeHTML(text || '');
    const sub = toInt(subTasks, 0);
    const subHTML = sub > 0
      ? `<span class='badge rounded-pill bg-info text-dark ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Total Sub-tasks: ${sub}'>${sub}</span>`
      : '';
    return `
      <div class='d-flex justify-content-between flex-wrap align-items-center mb-2 pb-1'>
        <div class='item-badges'>
          <div class='badge rounded-pill bg-label-${c}'>${t}</div>
          ${subHTML}
        </div>
        ${renderDropdown(taskEl)}
      </div>`;
  };

  const renderParentTask = (parentId, parentTitle) => {
    if (!parentId || !parentTitle) return '';
    return `
      <div class='parent-task-info mb-2'>
        <small class='text-muted d-flex align-items-center'>
          <i class='ti ti-arrow-up-right ti-xs me-1'></i>
          <span>Sub-task of: <strong>${escapeHTML(parentTitle)}</strong></span>
        </small>
      </div>`;
  };

  const renderTaskCreator = (createdBy) => {
    if (!createdBy) return '';
    return `
      <div class='task-creator mb-2'>
        <small class='text-muted d-flex align-items-center'>
          <i class='ti ti-user-plus ti-xs me-1'></i>
          <span>Assigned by <strong>${escapeHTML(createdBy)}</strong></span>
        </small>
      </div>`;
  };

  const resolveProgressStyle = (progress, boardId) => {
    let progressColor = 'bg-success';
    let progressText = 'Progress';
    const p = toInt(progress, 0);
    const id = boardId || '';

    if (id.includes('cancelled')) {
      progressColor = 'bg-secondary';
      progressText = 'Cancelled at';
    } else if (id.includes('completed')) {
      progressColor = 'bg-success';
      progressText = 'Completed';
    } else if (id.includes('running')) {
      progressColor = p < 50 ? 'bg-warning' : 'bg-info';
      progressText = 'Running';
    } else if (id.includes('activated')) {
      progressColor = p < 30 ? 'bg-danger' : 'bg-warning';
      progressText = 'Started';
    } else {
      if (p < 30) progressColor = 'bg-danger';
      else if (p < 70) progressColor = 'bg-warning';
    }

    return { progressColor, progressText, p };
  };

  const renderProgressBar = (progress, boardId) => {
    const { progressColor, progressText, p } = resolveProgressStyle(progress, boardId);
    return `
      <div class='task-progress mb-2'>
        <div class='d-flex justify-content-between align-items-center mb-1'>
          <small class='text-muted fw-medium'>${progressText}</small>
          <small class='text-muted'>${p}%</small>
        </div>
        <div class='progress' style='height: 4px;'>
          <div class='progress-bar ${progressColor}' role='progressbar' style='width: ${p}%' aria-valuenow='${p}' aria-valuemin='0' aria-valuemax='100'></div>
        </div>
      </div>`;
  };

  const renderDescription = (description) => {
    if (!description) return '';
    const words = String(description).trim().split(/\s+/);
    const isLong = words.length > 100;
    const shortDesc = isLong ? `${words.slice(0, 100).join(' ')}...` : description;
    return `
      <div class='kanban-description text-muted small mb-2'>
        <p class='mb-0' style='font-size: 0.75rem; line-height: 1.2;'>${escapeHTML(shortDesc)}</p>
      </div>`;
  };

  const renderAvatar = (images, pullUp, size, margin, members) => {
    const transition = pullUp ? ' pull-up' : '';
    const sz = size ? `avatar-${size}` : '';
    const memberArr = members === undefined ? [] : Array.isArray(members) ? members : String(members).split(',');

    if (!images || (Array.isArray(images) && images.length === 0)) return '';

    const imageArray = Array.isArray(images) ? images : String(images).split(',');

    return imageArray
      .map((img, index, arr) => {
        const m = margin && index !== arr.length - 1 ? ` me-${margin}` : '';
        const memberName = escapeHTML(memberArr[index] || 'User');
        const isInitials = img && img.length <= 3 && !String(img).includes('http') && !String(img).includes('/') && !String(img).includes('.');
        if (isInitials) {
          return `
            <div class='avatar ${sz}${m}' data-bs-toggle='tooltip' data-bs-placement='top' title='${memberName}'>
              <span class='avatar-initial rounded-circle bg-label-primary${transition}'>${escapeHTML(img)}</span>
            </div>`;
        }
        const imageSrc = String(img).includes('http') ? img : `${baseUrl}/storage/avatars/${img}`;
        const fallback = `${baseUrl}/assets/img/avatars/default-avatar.png`;
        return `
          <div class='avatar ${sz}${m}' data-bs-toggle='tooltip' data-bs-placement='top' title='${memberName}'>
            <img src='${escapeHTML(imageSrc)}' alt='Avatar' class='rounded-circle${transition}' onerror="this.src='${fallback}'">
          </div>`;
      })
      .join(' ');
  };

  const renderFooter = (attachments, comments, assigned, members, deadline) => {
    const a = escapeHTML(attachments || '0');
    const c = escapeHTML(comments || '0');
    const d = deadline ?? 'null';
    const attachmentTooltip = a === '1' ? '1 attachment' : `${a} attachments`;
    const commentTooltip = c === '1' ? '1 comment' : `${c} comments`;
    const dueDateTooltip = `Deadline: ${escapeHTML(d)}`;

    return `
      <div class='enhanced-footer'>
        <div class='d-flex justify-content-between align-items-center flex-wrap mt-2 pt-1'>
          <div class='d-flex flex-wrap'>
            <span class='d-flex align-items-center me-2 mb-1' data-bs-toggle='tooltip' data-bs-placement='top' title='${attachmentTooltip}'>
              <i class='ti ti-paperclip ti-xs me-1 text-muted'></i>
              <small class='text-muted'>${a}</small>
            </span>
            <span class='d-flex align-items-center me-2 mb-1' data-bs-toggle='tooltip' data-bs-placement='top' title='${commentTooltip}'>
              <i class='ti ti-message-dots ti-xs me-1 text-muted'></i>
              <small class='text-muted'>${c}</small>
            </span>
            ${d !== 'null' ? `
            <span class='d-flex align-items-center me-2 mb-1' data-bs-toggle='tooltip' data-bs-placement='top' title='${dueDateTooltip}'>
              <i class='ti ti-calendar ti-xs me-1 text-muted'></i>
              <small class='text-muted'>${escapeHTML(d)}</small>
            </span>` : ''}
          </div>
          <div class='avatar-group d-flex align-items-center assigned-avatar'>
            ${renderAvatar(assigned, true, 'xs', null, members)}
          </div>
        </div>
      </div>`;
  };

  const buildItemContent = (el) => {
    // Extract attributes
    const imgName = safeAttr(el, 'data-image');
    const imgHTML = imgName
      ? `<img class='img-fluid rounded mb-2' src='${baseUrl}/storage/images/${escapeHTML(imgName)}'>`
      : '';

    const description = safeAttr(el, 'data-description');
    const progress = safeAttr(el, 'data-progress', '0');
    const createdBy = safeAttr(el, 'data-created-by');

    // support both kebab and snake case for parent attributes
    const parentId = safeAttr(el, 'data-parent-id') || safeAttr(el, 'data-parent_id');
    const parentTitle = safeAttr(el, 'data-parent-title') || safeAttr(el, 'data-parent_title');

    const badge = safeAttr(el, 'data-badge');
    const badgeText = safeAttr(el, 'data-badge-text');
    const subTasks = safeAttr(el, 'data-sub_tasks') || safeAttr(el, 'data-sub-tasks') || '0';
    const taskShowUrl = safeAttr(el, 'data-task-show-url', '#');

    const titleText = el.querySelector('.kanban-text')?.textContent || el.textContent || '';
    const titleHTML = `
      <div class='kanban-text fw-medium mb-1'>
        <a href="${taskShowUrl}" class="text-decoration-none" draggable="false">
          ${escapeHTML(titleText)}
        </a>
      </div>
    `;

    let html = '';
    if (badge && badgeText) html += renderHeader(badge, badgeText, subTasks, el);
    html += imgHTML;
    html += titleHTML;
    html += renderParentTask(parentId, parentTitle);
    html += renderTaskCreator(createdBy);
    html += renderDescription(description);

    const boardId = el.closest('.kanban-board')?.getAttribute('data-id') || '';
    html += renderProgressBar(progress, boardId);

    return html;
  };

  const enhanceItemElement = (el) => {
    // Replace content
    const content = buildItemContent(el);
    el.textContent = '';
    el.insertAdjacentHTML('afterbegin', content);

    if (
      el.getAttribute('data-comments') !== null ||
      el.getAttribute('data-deadline') !== null ||
      el.getAttribute('data-assigned') !== null
    ) {
      el.insertAdjacentHTML(
        'beforeend',
        renderFooter(
          safeAttr(el, 'data-attachments'),
          safeAttr(el, 'data-comments'),
          safeAttr(el, 'data-assigned'),
          safeAttr(el, 'data-members'),
          safeAttr(el, 'data-deadline')
        )
      );
    }

    initTooltips(el);
  };

  // -----------------------------
  // Load Boards
  // -----------------------------
  let boards;
  try {
    boards = await api.fetchBoards();
  } catch (err) {
    console.error('Error loading kanban data:', err);
    boards = [
      { id: 'board-activated', title: 'Activated', item: [] },
      { id: 'board-running', title: 'Running', item: [] },
      { id: 'board-completed', title: 'Completed', item: [] },
      { id: 'board-cancelled', title: 'Cancelled', item: [] }
    ];
  }

  // -----------------------------
  // Initialize 3rd-party widgets
  // -----------------------------
  initFlatpickr();
  initSelect2();
  initQuill();

  // -----------------------------
  // Init Kanban
  // -----------------------------
  const kanban = new jKanban({
    element: '.kanban-wrapper',
    gutter: '15px',
    widthBoard: '300px',
    dragItems: true,
    boards: boards,
    dragBoards: true,
    addItemButton: true,

    click: function (el) {
      if (!OPEN_SIDEBAR_ON_ITEM_CLICK) return;
      if (!kanbanSidebar || !kanbanOffcanvas) return;

      const title = el.getAttribute('data-eid')
        ? el.querySelector('.kanban-text')?.textContent || ''
        : el.textContent || '';

      const date = safeAttr(el, 'data-deadline');
      const dateObj = new Date();
      const year = dateObj.getFullYear();
      const dateToUse = date
        ? `${date}, ${year}`
        : `${dateObj.getDate()} ${dateObj.toLocaleString('en', { month: 'long' })}, ${year}`;

      const label = safeAttr(el, 'data-badge-text');
      const avatars = safeAttr(el, 'data-assigned');
      const description = safeAttr(el, 'data-description');

      kanbanOffcanvas.show();

      const titleEl = qs('#title', kanbanSidebar);
      const descEl = qs('#description', kanbanSidebar);
      const deadlineEl = qs('#deadline', kanbanSidebar);

      if (titleEl) titleEl.value = title;
      if (descEl) descEl.value = description;
      if (deadlineEl) {
        const fp = deadlineEl._flatpickr;
        if (fp) fp.setDate(dateToUse, true, 'j F, Y');
        else if (deadlineEl.nextSibling && deadlineEl.nextSibling.nodeName === 'INPUT') deadlineEl.nextSibling.value = dateToUse; // altInput fallback
      }

      if (window.$) {
        window.$('.kanban-update-item-sidebar .select2').val(label).trigger('change');
      }

      const assignedWrap = qs('.assigned', kanbanSidebar);
      if (assignedWrap) {
        assignedWrap.innerHTML = '';
        assignedWrap.insertAdjacentHTML(
          'afterbegin',
          `${renderAvatar(avatars, false, 'xs', '1', el.getAttribute('data-members'))}
           <div class='avatar avatar-xs ms-1'>
             <span class='avatar-initial rounded-circle bg-label-secondary'><i class='ti ti-plus ti-xs text-heading'></i></span>
           </div>`
        );
        initTooltips(assignedWrap);
      }
    },

    dropEl: async function (el, target, source) {
      try {
        const taskId = safeAttr(el, 'data-eid');
        const newBoardId = target?.parentNode?.getAttribute('data-id');
        if (!taskId || !newBoardId) return;
        await api.updateTaskStatus(taskId, newBoardId);
        Swal.fire({ icon: 'success', title: 'Task status updated', toast: true, position: 'top-end', timer: 2000, showConfirmButton: false });
      } catch (error) {
        console.error('Failed to update task status:', error);
        if (source) source.appendChild(el); // revert
      }
    },

    buttonClick: function (el, boardId) {
      const addNew = document.createElement('form');
      addNew.className = 'new-item-form';
      addNew.innerHTML = `
        <div class="mb-3">
          <textarea class="form-control add-new-item" rows="2" placeholder="Add Task Title" autofocus required></textarea>
        </div>
        <div class="mb-3">
          <textarea class="form-control add-new-description" rows="1" placeholder="Task Description (Optional)"></textarea>
        </div>
        <div class="mb-3">
          <button type="submit" class="btn btn-primary btn-sm me-2">Add Task</button>
          <button type="button" class="btn btn-label-secondary btn-sm cancel-add-item">Cancel</button>
        </div>`;

      kanban.addForm(boardId, addNew);

      on(addNew, 'submit', async (e) => {
        e.preventDefault();
        const titleInput = addNew.querySelector('.add-new-item');
        const descriptionInput = addNew.querySelector('.add-new-description');
        const submitBtn = addNew.querySelector('button[type="submit"]');
        if (!titleInput || !submitBtn) return;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Adding...';

        try {
          const payload = { title: titleInput.value, board_id: boardId, description: descriptionInput?.value || '' };
          const result = await api.createTask(payload);
          const newTask = result?.task || result; // support both {task} or direct task

          // Add element to Kanban
          kanban.addElement(boardId, { title: `<span class='kanban-text'>${escapeHTML(newTask.title || payload.title)}</span>`, id: newTask.id });

          // Enhance the added element
          const newTaskElement = qs(`[data-eid="${newTask.id}"]`);
          if (newTaskElement) {
            // Set attributes
            newTaskElement.setAttribute('data-description', newTask.description || '');
            newTaskElement.setAttribute('data-progress', String(newTask.progress ?? '0'));
            newTaskElement.setAttribute('data-created-by', newTask['created-by'] || '');
            newTaskElement.setAttribute('data-badge-text', newTask['badge-text'] || 'Medium');
            newTaskElement.setAttribute('data-badge', newTask.badge || 'warning');
            newTaskElement.setAttribute('data-deadline', newTask['deadline'] || '');
            newTaskElement.setAttribute('data-attachments', String(newTask.attachments ?? '0'));
            newTaskElement.setAttribute('data-comments', String(newTask.comments ?? '0'));

            // Normalize arrays to comma-separated
            const assigned = Array.isArray(newTask.assigned) ? newTask.assigned.join(',') : (newTask.assigned || '');
            const members = Array.isArray(newTask.members) ? newTask.members.join(',') : (newTask.members || '');
            newTaskElement.setAttribute('data-assigned', assigned);
            newTaskElement.setAttribute('data-members', members);

            // Routes
            if (newTask['task-history-url']) newTaskElement.setAttribute('data-task-history-url', newTask['task-history-url']);
            if (newTask['task-show-url']) newTaskElement.setAttribute('data-task-show-url', newTask['task-show-url']);

            // Parent info
            if (newTask.parent_id) {
              newTaskElement.setAttribute('data-parent-id', newTask.parent_id);
              if (newTask.parent_title) newTaskElement.setAttribute('data-parent-title', newTask.parent_title);
            }

            enhanceItemElement(newTaskElement);
          }

          addNew.remove();
        } catch (error) {
          console.error('Failed to create task:', error);
          alert('Failed to create task. Please try again.');
          submitBtn.disabled = false;
          submitBtn.textContent = 'Add Task';
        }
      });

      on(addNew, 'click', '.cancel-add-item', () => addNew.remove());
    }
  });

  // Restore board order and observe changes
  const storedOrder = BOARD_ORDER.load();
  if (storedOrder) applyBoardOrder(storedOrder);
  observeBoardsOrder();

  // -----------------------------
  // Scrollbar
  // -----------------------------
  if (kanbanWrapper) new PerfectScrollbar(kanbanWrapper);

  // -----------------------------
  // Enhance all initial items
  // -----------------------------
  qsa('.kanban-item').forEach(enhanceItemElement);
  initTooltips();

  // -----------------------------
  // Title boards editable + dropdown
  // -----------------------------
  qsa('.kanban-title-board').forEach((elem) => {
    elem.addEventListener('mouseenter', function () { this.contentEditable = 'true'; });
    // elem.insertAdjacentHTML('afterend', renderBoardDropdown());
  });

  // -----------------------------
  // Event Delegation
  // -----------------------------
  // Stop opening offcanvas when interacting with the dropdown region
  // Use capture phase to intercept before jKanban item click handler
  document.addEventListener('click', function (e) {
    if (e.target && e.target.closest('.kanban-tasks-item-dropdown')) {
      e.stopPropagation();
    }
  }, true);
  // Fallback bubbling stops
  on(document, 'click', '.kanban-tasks-item-dropdown', (e) => { e.stopPropagation(); });
  on(document, 'click', '.kanban-tasks-item-dropdown *', (e) => { e.stopPropagation(); });
  // Allow anchor clicks inside title to navigate and not trigger item click
  on(document, 'click', '.kanban-text a', (e, target) => { 
    e.stopPropagation(); 
    e.preventDefault();
    const url = target.getAttribute('href');
    if (url && url !== '#') {
      window.location.href = url;
    }
  });

  // Delete task (existing + newly added)
  on(document, 'click', '.delete-task', async (e, target) => {
    e.preventDefault();
    e.stopPropagation();
    const item = target.closest('.kanban-item');
    const taskId = item?.getAttribute('data-eid');
    if (!taskId) return;

    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      customClass: { confirmButton: 'btn btn-primary me-2', cancelButton: 'btn btn-label-secondary' },
      buttonsStyling: false,
      showLoaderOnConfirm: true,
      preConfirm: async () => {
        try { await api.deleteTask(taskId); }
        catch (error) { Swal.showValidationMessage('Delete failed: ' + (error.message || 'Unknown error')); }
      }
    }).then((result) => {
      if (result.isConfirmed) {
        kanban.removeElement(taskId);
        Swal.fire({ icon: 'success', title: 'Deleted!', text: 'Task has been deleted.', customClass: { confirmButton: 'btn btn-success' } });
      }
    });
  });

  // Delete board (existing + newly added)
  on(document, 'click', '.delete-board', (e, target) => {
    e.preventDefault();
    const id = target.closest('.kanban-board')?.getAttribute('data-id');
    if (id) { kanban.removeBoard(id); persistCurrentBoardOrder(); }
  });

  // -----------------------------
  // Add Board UI controls
  // -----------------------------
  if (kanbanAddBoardBtn) {
    on(kanbanAddBoardBtn, 'click', () => {
      kanbanAddNewInput.forEach((el) => { el.value = ''; el.classList.toggle('d-none'); });
    });
  }

  if (kanbanContainer && kanbanAddNewBoard) kanbanContainer.appendChild(kanbanAddNewBoard);

  if (kanbanAddNewBoard) {
    on(kanbanAddNewBoard, 'submit', function (e) {
      e.preventDefault();
      const value = this.querySelector('.form-control')?.value?.trim();
      if (!value) return;
      const id = value.replace(/\s+/g, '-').toLowerCase();

      kanban.addBoards([{ id, title: escapeHTML(value) }]);

      const lastBoard = document.querySelectorAll('.kanban-board:last-child')[0];
      if (lastBoard) {
        const header = lastBoard.querySelector('.kanban-title-board');
        if (header) {
          // header.insertAdjacentHTML('afterend', renderBoardDropdown());
          header.addEventListener('mouseenter', function () { this.contentEditable = 'true'; });
        }
      }

      kanbanAddNewInput.forEach((el) => el.classList.add('d-none'));
      if (kanbanContainer) kanbanContainer.appendChild(kanbanAddNewBoard);
      persistCurrentBoardOrder();
    });
  }

  // -----------------------------
  // Offcanvas lifecycle
  // -----------------------------
  if (kanbanSidebar) {
    on(kanbanSidebar, 'hidden.bs.offcanvas', () => {
      const firstP = kanbanSidebar.querySelector('.ql-editor')?.firstElementChild;
      if (firstP) firstP.innerHTML = '';
    });

    on(kanbanSidebar, 'shown.bs.offcanvas', () => initTooltips(kanbanSidebar));
  }
})();