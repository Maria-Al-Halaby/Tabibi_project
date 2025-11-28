<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>

    <h1>firebase notification :)</h1>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if ("Notification" in window) {
                // Check current status
                if (Notification.permission === "granted") {
                    console.log("Notifications already allowed ✅");
                } else if (Notification.permission !== "denied") {
                    // Ask user for permission
                    Notification.requestPermission().then(function(permission) {
                        if (permission === "granted") {
                            console.log("✅ Permission granted!");
                            new Notification("Thanks for enabling notifications 🎉");
                        } else {
                            console.log("❌ Permission denied!");
                        }
                    });
                }
            } else {
                console.log("❌ Notifications are not supported by this browser.");
            }
        });
    </script>

    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js"></script>
    {{-- <script src="{{ asset('firebase-messaging-sw.js') }}"></script> --}}



    <script>
        // Your web app's Firebase configuration
        // For Firebase JS SDK v7.20.0 and later, measurementId is optional
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

        // Register service worker
        navigator.serviceWorker.register('/firebase-messaging-sw.js')
            .then(function(registration) {
                console.log('Service Worker registered:', registration);
                messaging.useServiceWorker(registration);

                // Request notification permission
                Notification.requestPermission().then(function(permission) {
                    if (permission === 'granted') {
                        messaging.getToken({
                            //change this
                            vapidKey: "BOmU7wjMEJbh6G7EAzJs9Nl_XQNPpNXpZiw8R3WXrMa-TnPLEVGpZegstk9MopbhTddldUxKEtPwtG9C5kB-DAg"
                        }).then(function(token) {
                            console.log("FCM Token:", token);

                            // Show token on page
                            const tokenBox = document.createElement("pre");
                            tokenBox.innerText = token;
                            document.body.appendChild(tokenBox);
                        }).catch(function(err) {
                            console.error("Error getting token:", err);
                        });
                    } else {
                        console.warn("Notifications permission not granted.");
                    }
                });

            }).catch(function(err) {
                console.error('Service Worker registration failed:', err);
            });



        // Handle foreground messages
        messaging.onMessage(function(payload) {
            console.log('Message received in foreground: ', payload);

            new Notification(payload.notification.title, {
                body: payload.notification.body,
                icon: '/icon.png'
            });
        });
    </script>
</body>

</html>
