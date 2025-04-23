var notyf = new Notyf({
    duration: 3000,
    position: {
    x: 'right',
    y: 'top',
    },
});
try {
    document.addEventListener('DOMContentLoaded', function() {
        fetchUserData();
    });

    function openAvatarModal() {
        document.getElementById('avatarModal').classList.remove('hidden');
    }

    function closeAvatarModal() {
        document.getElementById('avatarModal').classList.add('hidden');
    }

    function uploadAvatar() {
        const fileInput = document.getElementById('avatarUpload');

        if (!fileInput || fileInput.files.length === 0) {
            notyf.error('Vui lòng chọn một ảnh.');
            return;
        }

        const formData = new FormData();
        formData.append('avatar', fileInput.files[0]);

         fetch(`${window.location.origin}/api/user/avatar`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                notyf.success('Cập nhật avatar thành công!');
                fetchUserData()
                closeAvatarModal();
            } else {
                notyf.error('Lỗi: 001' + data.message);
            }
        })
        .catch(error => {
            notyf.error('Đã xảy ra lỗi khi tải ảnh lên.');
            console.error(error);
        });
    }

    function fetchUserData() {
        fetch(`${window.location.origin}/api/user/info`, {
            method: 'GET',
            headers: { 
                'Content-Type': 'application/json' 
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            console.log(data)
            if(!(data.thong_tin.avatar == null)) {
                const avatarURL = `/` + data.thong_tin.avatar;
                document.getElementById('avatarProfile').src = avatarURL;
            }
            document.getElementById('role').textContent = data.thong_tin.role;
        })
        .catch(error => {
            notyf.error('Lỗi kết nối');
        });
    }
} catch { }