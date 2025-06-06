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
});








