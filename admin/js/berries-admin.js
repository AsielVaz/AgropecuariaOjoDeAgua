(function () {
    'use strict';

    function closeSidebar() {
        document.body.classList.remove('sidebar-open');
    }

    document.addEventListener('DOMContentLoaded', function () {
        var loader = document.getElementById('load_screen');
        if (loader) {
            loader.remove();
        }

        document.querySelectorAll('.sidebarCollapse').forEach(function (button) {
            button.addEventListener('click', function () {
                document.body.classList.toggle('sidebar-open');
            });
        });

        document.querySelectorAll('.overlay').forEach(function (overlay) {
            overlay.addEventListener('click', closeSidebar);
        });

        document.querySelectorAll('#sidebar a[href]').forEach(function (link) {
            link.addEventListener('click', function () {
                if (window.innerWidth < 992 && !link.hasAttribute('data-bs-toggle')) {
                    closeSidebar();
                }
            });
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth >= 992) {
                closeSidebar();
            }
        });
    });
})();
