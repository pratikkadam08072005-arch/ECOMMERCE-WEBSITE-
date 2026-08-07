document.addEventListener('DOMContentLoaded', function() {
    // Create menu toggle button for mobile
    if (window.innerWidth <= 768) {
        const toggle = document.createElement('div');
        toggle.className = 'menu-toggle';
        toggle.innerHTML = '<span class="material-icons">menu</span>';
        document.body.appendChild(toggle);
        
        // Add click event
        toggle.addEventListener('click', function() {
            document.body.classList.toggle('menu-open');
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (document.body.classList.contains('menu-open') && 
                !e.target.closest('.sidebar') && 
                !e.target.closest('.menu-toggle')) {
                document.body.classList.remove('menu-open');
            }
        });
    }
});
