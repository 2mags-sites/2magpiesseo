// Admin Mode JavaScript
console.log('Admin mode active');

// Make editable elements
document.addEventListener('DOMContentLoaded', function() {
    // Add edit toolbar
    const toolbar = document.createElement('div');
    toolbar.className = 'admin-toolbar';
    toolbar.innerHTML = `
        <div class="toolbar-content">
            <span class="toolbar-title">Admin Mode</span>
            <button onclick="saveAllChanges()" class="toolbar-btn save-btn">Save All Changes</button>
            <button onclick="window.location.href='?logout=true'" class="toolbar-btn logout-btn">Logout</button>
        </div>
    `;
    document.body.prepend(toolbar);

    // Add styles for admin mode
    const style = document.createElement('style');
    style.textContent = `
        .admin-toolbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #2c3e50;
            color: white;
            padding: 10px;
            z-index: 10000;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .toolbar-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .toolbar-title {
            font-weight: bold;
        }
        .toolbar-btn {
            padding: 5px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .save-btn {
            background: #27ae60;
            color: white;
        }
        .save-btn:hover {
            background: #229954;
        }
        .logout-btn {
            background: #e74c3c;
            color: white;
        }
        .logout-btn:hover {
            background: #c0392b;
        }
        body {
            margin-top: 50px !important;
        }
        .editable {
            outline: 2px dashed rgba(52, 152, 219, 0.3);
            outline-offset: 2px;
            cursor: text;
            position: relative;
            transition: outline 0.3s ease;
        }
        .editable:hover {
            outline-color: rgba(52, 152, 219, 0.6);
        }
        .editable:focus {
            outline-color: rgba(52, 152, 219, 1);
            background: rgba(255, 255, 255, 0.05);
        }
        .edit-indicator {
            position: absolute;
            top: -25px;
            right: 0;
            background: #3498db;
            color: white;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 11px;
            display: none;
        }
        .editable:hover .edit-indicator {
            display: block;
        }
    `;
    document.head.appendChild(style);

    // Make elements editable
    makeEditable();
});

// Store original content for comparison
const originalContent = {};
const changedElements = new Set();

function makeEditable() {
    // Find all elements with data-editable attribute
    const editableElements = document.querySelectorAll('[data-editable]');

    editableElements.forEach(element => {
        const field = element.getAttribute('data-editable');
        const page = element.getAttribute('data-page') || getCurrentPage();

        // Store original content
        const key = `${page}.${field}`;
        originalContent[key] = element.innerHTML;

        // Make element editable
        element.classList.add('editable');
        element.contentEditable = true;

        // Add edit indicator
        const indicator = document.createElement('span');
        indicator.className = 'edit-indicator';
        indicator.textContent = 'Click to edit';
        element.appendChild(indicator);

        // Track changes
        element.addEventListener('input', function() {
            if (element.innerHTML !== originalContent[key]) {
                changedElements.add({element, page, field});
                element.style.backgroundColor = 'rgba(46, 204, 113, 0.1)';
            } else {
                // Remove from changed set if reverted
                changedElements.forEach(item => {
                    if (item.element === element) {
                        changedElements.delete(item);
                    }
                });
                element.style.backgroundColor = '';
            }
        });

        // Prevent link clicks while editing
        element.addEventListener('click', function(e) {
            if (e.target.tagName === 'A') {
                e.preventDefault();
            }
        });
    });
}

function getCurrentPage() {
    // Extract page name from URL
    const path = window.location.pathname;
    const page = path.substring(path.lastIndexOf('/') + 1);
    return page.replace('.php', '') || 'index';
}

async function saveAllChanges() {
    if (changedElements.size === 0) {
        alert('No changes to save');
        return;
    }

    const savePromises = [];

    changedElements.forEach(({element, page, field}) => {
        const value = element.innerHTML;

        savePromises.push(
            fetch('/admin-save.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    page: page,
                    field: field,
                    value: value
                })
            })
        );
    });

    try {
        const results = await Promise.all(savePromises);
        const responses = await Promise.all(results.map(r => r.json()));

        const allSuccess = responses.every(r => r.success);

        if (allSuccess) {
            alert('All changes saved successfully!');
            changedElements.forEach(({element, page, field}) => {
                const key = `${page}.${field}`;
                originalContent[key] = element.innerHTML;
                element.style.backgroundColor = '';
            });
            changedElements.clear();
        } else {
            alert('Some changes failed to save. Please try again.');
        }
    } catch (error) {
        console.error('Save error:', error);
        alert('Failed to save changes. Please try again.');
    }
}