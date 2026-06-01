<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('Document') }}</title>
</head>

<body>

    <h1>{{ __('firebase notification :)') }}</h1>


    <script>
        const messages = {
            alreadyAllowed: @js(__('Notifications already allowed')),
            permissionGranted: @js(__('Permission granted!')),
            permissionDenied: @js(__('Permission denied!')),
            thanks: @js(__('Thanks for enabling notifications')),
            unsupported: @js(__('Notifications are not supported by this browser.')),
            serviceWorkerRegistered: @js(__('Service Worker registered:')),
            fcmToken: @js(__('FCM Token:')),
            tokenError: @js(__('Error getting token:')),
            permissionNotGranted: @js(__('Notifications permission not granted.')),
            serviceWorkerFailed: @js(__('Service Worker registration failed:')),
            foregroundMessage: @js(__('Message received in foreground:')),
        };

        document.addEventListener("DOMContentLoaded", function() {
            if ("Notification" in window) {
                if (Notification.permission === "granted") {
                    console.log(messages.alreadyAllowed);
                } else if (Notification.permission !== "denied") {
                    Notification.requestPermission().then(function(permission) {
                        if (permission === "granted") {
                            console.log(messages.permissionGranted);
                            new Notification(messages.thanks);
                        } else {
                            console.log(messages.permissionDenied);
                        }
                    });
                }
            } else {
                console.log(messages.unsupported);
            }
        });
    </script>

    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js"></script>
    {{-- <script src="{{ asset('firebase-messaging-sw.js') }}"></script> --}}



    <script>
        const firebaseConfig = {
            apiKey: "AIzaSyBU0K32q4UhNifP_sUdeSXz2ZMguzFZZAg",
            authDomain: "tabibi-f206b.firebaseapp.com",
            databaseURL: "https://tabibi-f206b-default-rtdb.firebaseio.com",
            projectId: "tabibi-f206b",
            storageBucket: "tabibi-f206b.firebasestorage.app",
            messagingSenderId: "957880531145",
            appId: "1:957880531145:web:7ba544dd18ddb6e8a4b518",
            measurementId: "G-QGTH2GQZX4"
        };

        firebase.initializeApp(firebaseConfig);

        const messaging = firebase.messaging();

        navigator.serviceWorker.register('/firebase-messaging-sw.js')
            .then(function(registration) {
                console.log(messages.serviceWorkerRegistered, registration);
                messaging.useServiceWorker(registration);

                Notification.requestPermission().then(function(permission) {
                    if (permission === 'granted') {
                        messaging.getToken({
                            vapidKey: "BOmU7wjMEJbh6G7EAzJs9Nl_XQNPpNXpZiw8R3WXrMa-TnPLEVGpZegstk9MopbhTddldUxKEtPwtG9C5kB-DAg"
                        }).then(function(token) {
                            console.log(messages.fcmToken, token);

                            const tokenBox = document.createElement("pre");
                            tokenBox.innerText = token;
                            document.body.appendChild(tokenBox);
                        }).catch(function(err) {
                            console.error(messages.tokenError, err);
                        });
                    } else {
                        console.warn(messages.permissionNotGranted);
                    }
                });

            }).catch(function(err) {
                console.error(messages.serviceWorkerFailed, err);
            });



        messaging.onMessage(function(payload) {
            console.log(messages.foregroundMessage, payload);

            new Notification(payload.notification.title, {
                body: payload.notification.body,
                icon: '/icon.png'
            });
        });
    </script>
</body>

</html>
