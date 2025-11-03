/* global importScripts, firebase */
importScripts('https://www.gstatic.com/firebasejs/10.12.2/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.12.2/firebase-messaging-compat.js');

// Misma config que arriba
firebase.initializeApp({
    apiKey: "{{ env('FIREBASE_API_KEY') }}",
    authDomain: "{{ env('FIREBASE_AUTH_DOMAIN') }}",
    projectId: "{{ env('FIREBASE_PROJECT_ID') }}",
    messagingSenderId: "{{ env('FIREBASE_MESSAGING_SENDER_ID') }}",
    appId: "{{ env('FIREBASE_APP_ID') }}",
});

const messaging = firebase.messaging();

// Cuando llega notificación y la pestaña NO está activa
messaging.onBackgroundMessage(function(payload) {
    console.log('Push recibido en background:', payload);

    const title = payload?.notification?.title || 'Notificación';
    const body  = payload?.notification?.body  || '';
    const data  = payload?.data || {};

    self.registration.showNotification(title, {
        body: body,
        icon: '/favicon.ico',
        data: data,
    });
});

// Click en notificación → abrir pantalla
self.addEventListener('notificationclick', function(event) {
    event.notification.close();

    const urlToOpen = event.notification?.data?.url || '/admin/citas';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(windowClients => {
            for (const client of windowClients) {
                // reusa pestaña si ya existe
                if ('focus' in client) {
                    client.navigate(urlToOpen);
                    return client.focus();
                }
            }
            // o abre una nueva
            if (clients.openWindow) {
                return clients.openWindow(urlToOpen);
            }
        })
    );
});
