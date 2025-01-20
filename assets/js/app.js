// assets/js/app.js
const firebaseConfig = {
    apiKey: "AIzaSyDCNh8grbipAbGp83rvN8ZuPkJZsG3EW8Y",
    authDomain: "jadwalapp-86670.firebaseapp.com",
    projectId: "jadwalapp-86670",
    messagingSenderId: "725184462158",
    appId: "725184462158:web:6f877add6a643971f1321f"
};

firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();
messaging.requestPermission()
    .then(function() {
        return messaging.getToken();
    })
    .then(function(token) {
        sendTokenToServer(token);
    })
    .catch(function(err) {
        console.log('Error:', err);
    });

messaging.onMessage((payload) => {
    new Notification(payload.notification.title, {
        body: payload.notification.body,
        icon: payload.notification.icon
    });
});

function sendTokenToServer(token) {
    fetch('controller/NotifikasiController.php', {
        method: 'POST',
        body: JSON.stringify({ token: token }),
        headers: {
            'Content-Type': 'application/json'
        }
    });
}
