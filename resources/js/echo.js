import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,  // Ensure this is defined
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
    forceTLS: true
});
window.Echo.channel('orders').listen('.OrderPlaced', (e) => {
    console.log(e);
});
