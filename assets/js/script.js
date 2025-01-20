function requestNotificationPermission() {
    if ('Notification' in window) {
        Notification.requestPermission().then(function (permission) {
            if (permission === 'granted') {
                console.log('Izin notifikasi diberikan');
            }
        });
    }
}

function showNotification(title, body) {
    if ('Notification' in window && Notification.permission === 'granted') {
        new Notification(title, {
            body: body,
            icon: '/assets/images/icon.png'
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    requestNotificationPermission();

    // Menambahkan event listener untuk form tambah sekolah
    var addSchoolForm = document.getElementById('add-school-form');
    if (addSchoolForm) {
        addSchoolForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            fetch('tambahSekolah.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Sukses', 'Sekolah berhasil ditambahkan');
                    addSchoolForm.reset();
                } else {
                    showNotification('Gagal', 'Terjadi kesalahan saat menambahkan sekolah');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error', 'Terjadi kesalahan pada sistem');
            });
        });
    }
});
