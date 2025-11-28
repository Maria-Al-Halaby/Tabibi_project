importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js');

//Change this code
firebase.initializeApp({
    apiKey: "AIzaSyBU0K32q4UhNifP_sUdeSXz2ZMguzFZZAg",
    authDomain: "tabibi-f206b.firebaseapp.com",
    databaseURL: "https://tabibi-f206b-default-rtdb.firebaseio.com",
    projectId: "tabibi-f206b",
    storageBucket: "tabibi-f206b.firebasestorage.app",
    messagingSenderId: "957880531145",
    appId: "1:957880531145:web:7ba544dd18ddb6e8a4b518",
    measurementId: "G-QGTH2GQZX4"
});

const messaging = firebase.messaging();

// Only handle background notifications here
messaging.setBackgroundMessageHandler(function(payload) {
    console.log('[firebase-messaging-sw.js] Received background message', payload);

    const notificationTitle = payload.notification.title || 'Background Notification';
    const notificationOptions = {
        body: payload.notification.body || '',
        icon: '/icon.png'
    };

    return self.registration.showNotification(notificationTitle, notificationOptions);
}); 


/* importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js');

firebase.initializeApp({
    apiKey: "AIzaSyBU0K32q4UhNifP_sUdeSXz2ZMguzFZZAg",
    authDomain: "tabibi-f206b.firebaseapp.com",
    databaseURL: "https://tabibi-f206b-default-rtdb.firebaseio.com",
    projectId: "tabibi-f206b",
    storageBucket: "tabibi-f206b.firebasestorage.app",
    messagingSenderId: "957880531145",
    appId: "1:957880531145:web:7ba544dd18ddb6e8a4b518",
    measurementId: "G-QGTH2GQZX4"
});

const messaging = firebase.messaging();


messaging.setBackgroundMessageHandler(function(payload) {
    console.log('[firebase-messaging-sw.js] Received background message', payload);

    const notification = payload.notification || {};
    const title = notification.title || 'Background Notification';
    const options = {
    body: notification.body || '',
    icon: notification.icon || '/icon.png',
    };

    return self.registration.showNotification(title, options);
});
 */