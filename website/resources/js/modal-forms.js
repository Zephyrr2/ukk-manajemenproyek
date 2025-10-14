// Common Modal Functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent scrolling

        // Focus on first input if exists
        const firstInput = modal.querySelector('input:not([type="hidden"]), textarea, select');
        if (firstInput) {
            setTimeout(() => firstInput.focus(), 100);
        }
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = ''; // Restore scrolling

        // Reset form if exists
        const form = modal.querySelector('form');
        if (form) {
            form.reset();

            // Clear any dynamic content
            const dynamicElements = modal.querySelectorAll('[data-dynamic]');
            dynamicElements.forEach(el => {
                el.innerHTML = '';
                el.style.display = 'none';
            });
        }
    }
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('fixed', 'inset-0')) {
        const modals = document.querySelectorAll('[id*="form-"]:not(.hidden)');
        modals.forEach(modal => {
            closeModal(modal.id);
        });
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modals = document.querySelectorAll('[id*="form-"]:not(.hidden)');
        modals.forEach(modal => {
            closeModal(modal.id);
        });
    }
});

// Context-aware modal opening functions
function openCommentModal(itemType, itemId, itemTitle, itemDescription = '') {
    document.getElementById('commentable_type').value = itemType;
    document.getElementById('commentable_id').value = itemId;
    document.getElementById('comment-context-title').textContent = 'Komentar untuk: ' + itemTitle;
    document.getElementById('comment-context-desc').textContent = itemDescription;
    openModal('form-create-comment');
}

function openFileUploadModal(relatedType = '', relatedId = '', contextTitle = '', contextDesc = '') {
    if (relatedType && relatedId) {
        document.getElementById('related_type').value = relatedType;
        document.getElementById('related_id').value = relatedId;
        document.getElementById('upload-context').style.display = 'block';
        document.getElementById('upload-context-title').textContent = 'Upload untuk: ' + contextTitle;
        document.getElementById('upload-context-desc').textContent = contextDesc;
    } else {
        document.getElementById('upload-context').style.display = 'none';
    }
    openModal('form-file-upload');
}

function openTaskUpdateModal(taskId, taskTitle, taskProject, currentStatus) {
    document.getElementById('task_id').value = taskId;
    document.getElementById('task-title').textContent = taskTitle;
    document.getElementById('task-project').textContent = taskProject;
    document.getElementById('current-status').value = currentStatus;
    openModal('form-update-task-status');
}

// Form validation helpers
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;

    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('border-red-300');
            isValid = false;
        } else {
            field.classList.remove('border-red-300');
        }
    });

    return isValid;
}

// Show loading state on form submit
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form[action]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.textContent;
                submitBtn.textContent = 'Processing...';
                submitBtn.disabled = true;

                // Re-enable after 5 seconds in case of error
                setTimeout(() => {
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                }, 5000);
            }
        });
    });
});

// Auto-save functionality for long forms
function enableAutoSave(formId, interval = 30000) {
    const form = document.getElementById(formId);
    if (!form) return;

    let autoSaveInterval;

    // Save form data to localStorage
    function saveFormData() {
        const formData = new FormData(form);
        const data = {};

        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }

        localStorage.setItem('autosave_' + formId, JSON.stringify(data));

        // Show save indicator
        showSaveIndicator();
    }

    // Load form data from localStorage
    function loadFormData() {
        const savedData = localStorage.getItem('autosave_' + formId);
        if (savedData) {
            const data = JSON.parse(savedData);

            Object.keys(data).forEach(key => {
                const field = form.querySelector(`[name="${key}"]`);
                if (field && field.type !== 'file') {
                    field.value = data[key];
                }
            });
        }
    }

    function showSaveIndicator() {
        // Create or update save indicator
        let indicator = document.getElementById('autosave-indicator');
        if (!indicator) {
            indicator = document.createElement('div');
            indicator.id = 'autosave-indicator';
            indicator.className = 'fixed top-4 right-4 bg-green-500 text-white px-3 py-1 rounded text-sm z-50';
            document.body.appendChild(indicator);
        }

        indicator.textContent = 'Saved';
        indicator.style.display = 'block';

        setTimeout(() => {
            indicator.style.display = 'none';
        }, 2000);
    }

    // Load saved data on form open
    loadFormData();

    // Start auto-save interval
    autoSaveInterval = setInterval(saveFormData, interval);

    // Clear auto-save on form submit
    form.addEventListener('submit', function() {
        clearInterval(autoSaveInterval);
        localStorage.removeItem('autosave_' + formId);
    });
}

// Utility functions
function formatBytes(bytes, decimals = 2) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

function debounce(func, wait, immediate) {
    let timeout;
    return function executedFunction() {
        const context = this;
        const args = arguments;
        const later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}

// Enhanced notifications
function showNotification(message, type = 'info', duration = 5000) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 max-w-sm ${getNotificationClass(type)}`;
    notification.innerHTML = `
        <div class="flex items-start">
            <div class="flex-shrink-0">
                ${getNotificationIcon(type)}
            </div>
            <div class="ml-3 pt-0.5">
                <p class="text-sm font-medium">${message}</p>
            </div>
            <div class="ml-4 flex-shrink-0 flex">
                <button class="inline-flex text-gray-400 hover:text-gray-600 focus:outline-none" onclick="this.parentElement.parentElement.parentElement.remove()">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    `;

    document.body.appendChild(notification);

    // Auto remove
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, duration);
}

function getNotificationClass(type) {
    const classes = {
        'success': 'bg-green-50 border border-green-200 text-green-800',
        'error': 'bg-red-50 border border-red-200 text-red-800',
        'warning': 'bg-yellow-50 border border-yellow-200 text-yellow-800',
        'info': 'bg-blue-50 border border-blue-200 text-blue-800'
    };
    return classes[type] || classes['info'];
}

function getNotificationIcon(type) {
    const icons = {
        'success': '<svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>',
        'error': '<svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>',
        'warning': '<svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        'info': '<svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
    };
    return icons[type] || icons['info'];
}
