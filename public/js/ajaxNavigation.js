document.addEventListener('DOMContentLoaded', function() {
    // Функция для загрузки контента через AJAX
    function loadContent(url) {
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest', // Чтобы Laravel понимал, что это AJAX
            },
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.text();
        })
        .then(html => {
            // Парсим HTML и извлекаем только нужный контент
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContent = doc.querySelector('.bodyContainer').innerHTML;

            // Обновляем контент на странице
            document.querySelector('.bodyContainer').innerHTML = newContent;

            // Обновляем URL в браузере (без перезагрузки)
            history.pushState(null, null, url);

            // Можно вызвать здесь дополнительные скрипты, если нужно
            console.log('Content loaded successfully!');
        })
        .catch(error => {
            console.error('Error loading content:', error);
            // Если AJAX-запрос не сработал, делаем обычный переход
            window.location.href = url;
        });
    }

    // Перехватываем клики по ссылкам
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a[href^="/"]'); // Ищем ближайшую ссылку
        if (!link) return;

        // Игнорируем ссылки с атрибутом data-no-ajax или внешние ссылки
        if (link.hasAttribute('data-no-ajax') || link.target === '_blank') {
            return;
        }

        e.preventDefault();
        const url = link.getAttribute('href');
        loadContent(url);
    });

    // Обработка кнопок "Назад"/"Вперед" в браузере
    window.addEventListener('popstate', function() {
        loadContent(window.location.pathname);
    });
});