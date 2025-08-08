// DOM Elements
const taskModal = document.getElementById('taskModal');
const closeModal = document.querySelector('.close');
const taskForm = document.getElementById('taskForm');
let currentColumnId = 'todo';

// State
let tasks = JSON.parse(localStorage.getItem('tasks')) || [];
let draggedTask = null;

document.addEventListener('DOMContentLoaded', () => {
    setupEventListeners();
    initializeTasks();
});

function generateId() {
    return Date.now().toString();
}

function initializeTasks() {
    tasks.forEach(task => {
        addTaskToDOM(task);
    });
    updateTaskCounts();
}

function handleAddColumnPrompt() {
    const title = prompt("Enter column title:");
    if (!title) return;
    const id = title.toLowerCase().replace(/\s+/g, '-');
    addColumn(title, id);
}

function addColumn(title, columnId) {
    const board = document.querySelector('.board');

    if (document.getElementById(columnId)) {
        alert('Column with this ID already exists!');
        return;
    }

    const column = document.createElement('div');
    column.className = 'column';
    column.id = columnId;

    column.innerHTML = `
        <div class="column-header">
            <div class="left">
                <h2>${title}</h2>
                <span class="task-count" id="${columnId}-count">0</span>
            </div>
            <div class="right">
                <button class="btn btn-light add-task-btn" data-column="${columnId}">
                    <i class="fas fa-plus"></i> Add Task
                </button>
            </div>
        </div>
        <div class="task-list" data-column="${columnId}"></div>
    `;

    board.appendChild(column);

    const taskList = column.querySelector('.task-list');
    taskList.addEventListener('dragover', handleDragOver);
    taskList.addEventListener('dragleave', handleDragLeave);
    taskList.addEventListener('drop', handleDrop);

    setupEventListeners(); // Rebind buttons
    updateTaskCounts();
}

function setupEventListeners() {
    // Modal open for all .add-task-btn
    document.querySelectorAll('.add-task-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            currentColumnId = btn.getAttribute('data-column-header') || 'todo';
            console.log(currentColumnId);
            taskModal.style.display = 'block';
        });
    });

    closeModal.addEventListener('click', () => {
        taskModal.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (e.target === taskModal) {
            taskModal.style.display = 'none';
        }
    });

    taskForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const title = document.getElementById('taskTitle').value;
        const description = document.getElementById('taskDescription').value;
        const priority = document.getElementById('taskPriority').value;

        const task = {
            id: generateId(),
            title,
            description,
            priority,
            column: currentColumnId
        };

        tasks.push(task);
        saveTasks();
        addTaskToDOM(task);
        updateTaskCounts();
        taskForm.reset();
        taskModal.style.display = 'none';
    });

    // Drag and Drop listeners
    document.querySelectorAll('.task-list').forEach(list => {
        list.addEventListener('dragover', handleDragOver);
        list.addEventListener('dragleave', handleDragLeave);
        list.addEventListener('drop', handleDrop);
    });
}

function addTaskToDOM(task) {
    
    const taskList = document.querySelector(`[data-column="${task.column}"]`);
    // if (!taskList) {
    //     console.warn(`Task list not found for column: ${task.column}`);
    //     return;
    // }

    const taskElement = document.createElement('div');
    taskElement.className = 'task-card';
    taskElement.draggable = true;
    taskElement.id = task.id;
    const parentTitle = task.parent && task.parent.title ? task.parent.title : '';
    taskElement.innerHTML = `
        <div class="card-header p-2 bg-white border-0">
            <span class="custom-badge">${task.sku}</span>
            <span class="badge text-white task-priority priority-${task.priority}">${task.priority}</span>
            <div class="dropdown float-end dropstart">
                <button class="btn btn-link p-0" aria-label="Task Action Options" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-ellipsis-vertical"></i> 
                </button>
                <ul class="dropdown-menu">
                    <li><a href="#" class="dropdown-item mb-0 edit" data-id="${task.id}">Edit</a></li>
                    <li><a href="#" class="dropdown-item mb-0 duplicate" data-id="${task.id}">Duplicate</a></li>
                    <li><a href="#" class="dropdown-item mb-0 delete-task" data-id="${task.id}">Delete</a></li>
                </ul>
            </div>
        </div>
        
        <div class="card-body px-2 py-0">

            <div class="col-md-12 col-lg-12 d-flex mt-3">
                <div id="progress-container" class="progress w-50 float-start" style="height:12px;">
                    <div id="progress-indicator" class="progress-bar bg-success" role="progressbar" 
                        style="width: ${task.percent}%;" 
                        aria-valuenow="${task.percent}" 
                        aria-valuemin="0" 
                        aria-valuemax="100" 
                        aria-label="projectProgress"></div>
                </div>
                <p class="float-start ms-2 small text-muted mb-0" id="progress-label">${task.percent}% Complete</p>
            </div>

            <h2 class="h6 fw-medium text-start btn p-0 m-0 task-title">${task.title}</h2>
            <p class="m-0 opacity-75 snippet">${task.description}</p>
            
            ${parentTitle ? `
                <span class="badge mt-3 priority-${task.priority} custom-tooltip">
                    ${parentTitle}
                    <span class="tooltip-text">Parent Task: ${parentTitle}</span>
                </span>` : ''
            }
                <div class="d-inline-block mt-3">
                    <span class="me-2">Assignees:</span>
                    <a href="#" class="badge assignee-cover border text-dark overlap custom-tooltip">
                        AJ
                        <span class="tooltip-text">AJ</span>
                    </a>
                    <a href="#" class="badge assignee-cover border text-dark overlap custom-tooltip">
                        CL
                        <span class="tooltip-text">CL</span>
                    </a>
                    <a href="#" class="badge assignee-cover border text-primary overlap border-primary" title="+4 more">+4</a>
                </div>

            <p class="m-0 mt-2 opacity-75 small text-dark"><i class='fa fa-calendar fa-sm me-1'></i> Due Date: 25</p>
        </div>
        <div class="card-footer p-2 bg-white border-0 small pt-3">
            Assigned by:
            
            <span class="badge assignee-cover border text-dark custom-tooltip">
                Rob
                <span class="tooltip-text">Assisgned by: Rob</span>
            </span>
            
        </div>
    `;

    // taskElement.innerHTML = `
    //     <div class="task-header">
    //         <span class="task-title">${task.title}</span>
    //         <span class="task-priority priority-${task.priority}">${task.priority}</span>
    //         <div class="dropdown">
    //             <button type="button" class="btn p-0" data-bs-toggle="dropdown" aria-expanded="true">
    //                 <i class="fas fa-ellipsis-v text-muted"></i>
    //             </button>
    //             <ul class="dropdown-menu dropdown-menu-end">
    //                 <li><a class="dropdown-item edit" href="#" data-id="${task.id}">Edit</a></li>
    //                 <li><a class="dropdown-item duplicate" href="#" data-id="${task.id}">Duplicate</a></li>
    //                 <li><a class="dropdown-item delete-task" href="#" data-id="${task.id}">Delete</a></li>
    //             </ul>
    //         </div>                    
    //     </div>
    //     <div class="task-description">${task.description}</div>
    // `;

    taskElement.addEventListener('dragstart', handleDragStart);
    taskElement.addEventListener('dragend', handleDragEnd);

    taskElement.querySelector('.delete-task').addEventListener('click', (e) => {
        e.preventDefault();
        const taskId = e.target.getAttribute('data-id');
        deleteTask(taskId);
    });
    taskList.appendChild(taskElement);
}

function handleDragStart(e) {
    draggedTask = this;
    this.classList.add('dragging');
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/plain', this.id);
}

function handleDragEnd() {
    this.classList.remove('dragging');
    draggedTask = null;
}

function handleDragOver(e) {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
    this.classList.add('drag-over');
}

function handleDragLeave() {
    this.classList.remove('drag-over');
}

function handleDrop(e) {
    e.preventDefault();
    this.classList.remove('drag-over');

    if (draggedTask) {
        const newColumn = this.getAttribute('data-column');
        const taskId = draggedTask.id;

        const taskIndex = tasks.findIndex(task => task.id === taskId);
        if (taskIndex !== -1) {
            tasks[taskIndex].column = newColumn;
            saveTasks();
            updateTaskCounts();
        }

        this.appendChild(draggedTask);
    }
}

function updateTaskCounts() {
    document.querySelectorAll('.task-count').forEach(el => el.textContent = '0');

    tasks.forEach(task => {
        const countElement = document.getElementById(`${task.column}-count`);
        if (countElement) {
            countElement.textContent = (parseInt(countElement.textContent) + 1).toString();
        }
    });
}

function saveTasks() {
    localStorage.setItem('tasks', JSON.stringify(tasks));
}

function deleteTask(id) {

    swal({
        title: 'Are you sure?',
        text: 'You will not be able to recover this task!',
        icon: 'warning',
        buttons: {
            cancel: {
                text: 'No',
                value: false,
                visible: true,
                className: 'btn btn-secondary',
                closeModal: true,
            },
            confirm: {
                text: 'Yes',
                value: true,
                visible: true,
                className: 'btn btn-danger',
                closeModal: false,
            }
        }
    }).then((willDelete) => {
        if (willDelete) {
            swal("Your task has been deleted!", { icon: "success" });

            const taskEl = document.getElementById(id);
            if (taskEl) taskEl.remove();

            tasks = tasks.filter(task => task.id !== id);
            saveTasks();
            updateTaskCounts();
        } else {
            swal("Your task is safe.");
        }
    });

    // Dynamically center the button area after the dialog appears
    setTimeout(() => {
        const footer = document.querySelector('.swal-footer');
        if (footer) {
            footer.style.textAlign = 'center';
        }
    }, 100);
    
}


// Add default tasks only once
if (tasks.length === 0) {
    const sampleTasks = [
        {
            id: '1',
            sku: 'OZS-1',
            title: 'Design Homepage',
            description: 'Create a modern and responsive homepage design',
            priority: 'high',
            column: 'todo',
            percent: '40'
        },
        {
            id: '2',
            sku: 'OZS-2',
            title: 'Lower Page',
            description: 'Create a modern and responsive homepage design',
            priority: 'medium',
            column: 'not-start',
            percent: '50'
        },
        {
            id: '3',
            sku: 'OZS-3',
            title: 'Implement Authentication',
            description: 'Add user authentication system',
            priority: 'medium',
            column: 'in-progress',
            percent: '60'
        },
        {
            id: '4',
            sku: 'OZS-4',
            title: 'Write Documentation',
            description: 'Document the API endpoints',
            priority: 'low',
            column: 'done',
            percent: '20'
        },
        {
            id: '5',
            sku: 'OZS-5',
            title: 'Design astrounaut training program',
            description: 'Develop a comprehensive training program for....',
            priority: 'high',
            parent: { id: 2, title: 'Design Homepage' },
            column: 'todo',
            percent: '30'
        },
    ];
    tasks = sampleTasks;
    saveTasks();
}