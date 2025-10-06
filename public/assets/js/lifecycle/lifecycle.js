// Initialize Bootstrap Select
function initializeBootstrapSelect() {
    $('.bootstrap-select').each(function() {
        if (!$(this).data('bs.select')) {
            $(this).selectpicker();
        }
    });
}

// Progress Bar Functionality
function updateProgress(progressElement, percentage) {
    const progressFill = progressElement.querySelector('.progress-fill');
    const progressText = progressElement.querySelector('.text-end.text-muted');
    
    if (progressFill && progressText) {
        progressFill.style.width = percentage + '%';
        progressText.textContent = percentage + '% Complete';
    }
}

// Checklist Functionality
function initializeChecklist() {
    const checkboxes = document.querySelectorAll('.checklist-checkbox:not([disabled])');
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const item = this.closest('.checklist-item');
            if (item) {
                if (this.checked) {
                    item.classList.add('completed');
                    item.classList.remove('pending');
                } else {
                    item.classList.remove('completed');
                    item.classList.add('pending');
                }
                
                // Calculate and update progress
                const allCheckboxes = document.querySelectorAll('.checklist-checkbox');
                const checkedBoxes = document.querySelectorAll('.checklist-checkbox:checked');
                const percentage = Math.round((checkedBoxes.length / allCheckboxes.length) * 100);
                
                updateProgress(document.querySelector('.progress-section'), percentage);
            }
        });
    });
}

// Document Ready Function
$(document).ready(function() {
    initializeBootstrapSelect();
    initializeChecklist();
});

// Initialize Datepickers
function initializeDatepickers() {
    $('.datepicker').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
    });
}

// Search Functionality
function initializeSearch(searchInputId, itemClass) {
    const searchInput = document.getElementById(searchInputId);
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const items = document.querySelectorAll('.' + itemClass);
            
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }
}
