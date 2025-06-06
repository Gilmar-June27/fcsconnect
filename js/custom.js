document.addEventListener('DOMContentLoaded', function() {
    // Function to toggle the sidebar
    function toggleSidebar() {
        const header = document.querySelector('header');
        if (header) {
            header.classList.toggle('nav-open');
        } else {
            console.error('Header element not found');
        }
    }

    // Event listener for the sidebar toggle button
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', toggleSidebar);
    } else {
        console.error('Sidebar toggle button not found');
    }

    const closeModalButton = document.getElementById('closeModal');
    const editProductForm = document.getElementById('editProductForm');

    if (closeModalButton && editProductForm) {
        closeModalButton.addEventListener('click', function() {
            editProductForm.style.display = 'none';
        });

        document.addEventListener('click', function(event) {
            const form = document.querySelector('.edit-product-form form');
            if (form && !form.contains(event.target) && !event.target.matches('.close-btn')) {
                editProductForm.style.display = 'none';
            }
        });
    } else {
        console.error('Modal or form elements not found');
    }
});
