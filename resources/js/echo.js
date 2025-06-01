import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

const yourAccessToken = localStorage.getItem('token'); // пример, где хранится токен

window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    cluster: '', // <-- добавь сюда пустую строку, чтобы не было ошибки
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    wssPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
    auth: {
        headers: {
            Authorization: `Bearer ${yourAccessToken}`,
        },
    },
});

// const echo = new Echo({
//     broadcaster: 'reverb',
//     key: import.meta.env.VITE_REVERB_APP_KEY,
//     wsHost: window.location.hostname,
//     wsPort: import.meta.env.VITE_REVERB_PORT || 8080,
//     forceTLS: false, // Для HTTPS: true
//     enabledTransports: ['ws', 'wss'], // Важно для Reverb
// });

export default window.Echo;
