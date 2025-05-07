document.addEventListener("DOMContentLoaded", function() {
    const sidebarBTN = document.getElementById('sidebarBTN');
    const sidebar = document.getElementById('sidebar');

    sidebarBTN.addEventListener('click', function(event) {
        if (sidebar.classList.contains('hidden')) {
            // Показываем сайдбар
            sidebar.classList.remove('d-none');
            setTimeout(() => {
                sidebar.classList.remove('hidden');
            }, 10); // Небольшая задержка для активации анимации
        } else {
            // Скрываем сайдбар
            sidebar.classList.add('hidden');
            setTimeout(() => {
                sidebar.classList.add('d-none');
            }, 300); // Ждем завершения анимации opacity
            
        }
    });
});