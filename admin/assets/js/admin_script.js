/**
 * ===================================================================
 * Universal Modal Control Functions
 * ===================================================================
 */
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        document.body.classList.add('modal-open');
        setTimeout(() => { modal.classList.add('show'); }, 10);
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('show');
        setTimeout(() => { document.body.classList.remove('modal-open'); }, 300);
    }
}

/**
 * ===================================================================
 * Specific Modal Trigger Functions (called from HTML onclick)
 * ===================================================================
 */

// --- THIS IS THE CORRECTED FUNCTION FOR MATERIALS ---
function openMaterialModal(materialData = null) {
    const form = document.getElementById('material-form');
    const titleElement = document.getElementById('material-modal-title');
    const typeChoice = document.getElementById('type-choice');
    
    form.reset();

    if (materialData) { // EDIT mode
        const data = JSON.parse(decodeURIComponent(materialData));
        titleElement.innerText = 'Edit Material';
        form.action = 'handlers/book_handler.php';
        
        // Use form.querySelector to correctly find elements within the form
        form.querySelector('[name="action"]').value = 'update';
        form.querySelector('[name="material_id"]').value = data.id;
        form.querySelector('[name="title"]').value = data.title;
        form.querySelector('[name="author_lecturer"]').value = data.author_lecturer;
        form.querySelector('[name="description"]').value = data.description || '';
        form.querySelector('[name="genre_course"]').value = data.genre_course || '';

        const isBook = data.material_type === 'book';
        typeChoice.value = isBook ? 'book' : 'study_material';
        typeChoice.disabled = true;

        if (isBook) {
            form.querySelector('[name="publisher"]').value = data.publisher || '';
            form.querySelector('[name="publication_date"]').value = data.publication_date || '';
            form.querySelector('[name="pages"]').value = data.pages || '';
            form.querySelector('[name="total_copies"]').value = data.total_copies || '1';
        } else {
            form.querySelector('[name="material_type"]').value = data.material_type;
        }
    } else { // ADD mode
        titleElement.innerText = 'Add New Material';
        form.action = 'handlers/book_handler.php';
        form.querySelector('[name="action"]').value = 'add';
        form.querySelector('[name="material_id"]').value = '0';
        typeChoice.disabled = false;
    }
    
    typeChoice.dispatchEvent(new Event('change'));
    openModal('material-modal');
}

// --- THIS IS THE CORRECTED FUNCTION FOR USERS ---
function openUserModal(userData = null) {
    const form = document.getElementById('user-form');
    const titleElement = document.getElementById('user-modal-title');
    const passwordInput = form.querySelector('[name="password"]');

    form.reset();

    if (userData) { // EDIT mode
        const data = JSON.parse(decodeURIComponent(userData));
        titleElement.innerText = 'Edit User';
        form.action = 'handlers/user_handler.php';
        
        // Use form.querySelector to correctly find elements within the form
        form.querySelector('[name="action"]').value = 'update_user';
        form.querySelector('[name="user_id"]').value = data.id;
        form.querySelector('[name="full_name"]').value = data.full_name;
        form.querySelector('[name="email"]').value = data.email;
        form.querySelector('[name="student_number"]').value = data.student_number;
        form.querySelector('[name="course"]').value = data.course || '';
        form.querySelector('[name="role"]').value = data.role;
        passwordInput.placeholder = 'Leave blank to keep current';
        passwordInput.required = false;
    } else { // ADD mode
        titleElement.innerText = 'Add New User';
        form.action = 'handlers/user_handler.php';
        form.querySelector('[name="action"]').value = 'add_user';
        passwordInput.placeholder = 'Required for new user';
        passwordInput.required = true;
    }
    openModal('user-modal');
}

// Opens the DELETE confirmation modal (this was already correct)
function openDeleteConfirmation(deleteUrl, itemName) {
    const modal = document.getElementById('confirmation-modal');
    if (modal) {
        modal.querySelector('#confirmation-message').innerHTML = `Are you sure you want to permanently delete <strong>"${itemName}"</strong>? This action cannot be undone.`;
        modal.querySelector('#confirm-delete-btn').href = deleteUrl;
        openModal('confirmation-modal');
    }
    return false;
}

/**
 * ===================================================================
 * Main script execution (runs once the page is fully loaded)
 * ===================================================================
 */
document.addEventListener('DOMContentLoaded', () => {
    // Logic for setting the active state on the sidebar navigation (correct)
    const currentPage = window.location.pathname.split('/').pop();
    document.querySelectorAll('.admin-sidebar-nav a').forEach(link => {
        const linkPage = link.getAttribute('href').split('/').pop();
        if (currentPage.startsWith(linkPage) && linkPage !== '') {
            link.classList.add('active');
        }
    });

    // Dynamic Form Logic for the 'Manage Materials' modal (correct)
    const materialForm = document.getElementById('material-form');
    if (materialForm) {
        const typeChoice = document.getElementById('type-choice');
        const bookFields = document.getElementById('book-fields');
        const materialTypeField = document.getElementById('material-type-field');
        
        const toggleMaterialFormFields = (choice) => {
            if (choice === 'book') {
                bookFields.style.display = 'block';
                materialTypeField.style.display = 'none';
                if (document.getElementById('material_type_select')) {
                    document.getElementById('material_type_select').required = false;
                }
            } else { // 'study_material'
                bookFields.style.display = 'none';
                materialTypeField.style.display = 'block';
                if (document.getElementById('material_type_select')) {
                    document.getElementById('material_type_select').required = true;
                }
            }
        };
        if (typeChoice) {
            typeChoice.addEventListener('change', () => toggleMaterialFormFields(typeChoice.value));
            toggleMaterialFormFields(typeChoice.value);
        }
    }
    
    // Count-up animation for dashboard stat cards (correct)
    document.querySelectorAll('.stat-card-info h4').forEach(counter => {
        const target = +counter.innerText;
        if (isNaN(target)) return;
        counter.innerText = '0';
        const speed = 100;
        const updateCount = () => {
            const current = +counter.innerText;
            const increment = target / speed;
            if (current < target) {
                counter.innerText = Math.ceil(current + increment);
                setTimeout(updateCount, 15);
            } else {
                counter.innerText = target.toLocaleString();
            }
        };
        updateCount();
    });

    // Live Search functionality for tables (correct)
    const initLiveSearch = (inputId, tableBodyId) => {
        const searchInput = document.getElementById(inputId);
        const tableBody = document.getElementById(tableBodyId);
        if (searchInput && tableBody) {
            searchInput.addEventListener('keyup', () => {
                const searchTerm = searchInput.value.toLowerCase();
                const rows = tableBody.getElementsByTagName('tr');
                Array.from(rows).forEach(row => {
                    const rowText = row.textContent.toLowerCase();
                    row.style.display = rowText.includes(searchTerm) ? '' : 'none';
                });
            });
        }
    };
    initLiveSearch('material-search', 'materials-table-body');
    initLiveSearch('user-search', 'users-table-body');
});