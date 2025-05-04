// document.addEventListener("DOMContentLoaded", function() {
//     const sidebarBTN = document.getElementById('showHideSidebar');
//     const sidebar = document.getElementById('sidebar');

//     sidebarBTN.addEventListener('click',function (event) {
//         if($("#sidebar").css('display') == "none"){
//             sidebar.style.opacity = "1";
//             setTimeout(() => {
//                 sidebar.classList.remove('d-none');
//             }, 1000);
//         }
//         else {
//             sidebar.style.opacity = "0";
//             setTimeout(() => {
//                 sidebar.classList.add('d-none');
//             }, 1000);
//         }
//     })
// });

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