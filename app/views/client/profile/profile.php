<?php
require_once dirname(__DIR__, 4) . '/config/db.php';
require_once dirname(__DIR__, 3) . '/models/UserService.php';

if($TrangThaiBaoTri && $_SESSION['Role'] != 'Admin'){
    header("Location: /maintain");
    exit;
}


if(!isset($_SESSION["email"])){
    header("Location: /auth/login");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/public/image/logo.png" type="image/x-icon">
    <link href="/public/css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/client.css">
    <link rel="stylesheet" href="/public/css/notyf.min.css">
    <title>Tài khoản | BKSTORE</title>
</head>
<body class="bg-gray-100">
    <div class="h-screen">
        <header id="header-content" class="sticky top-0 z-50">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/header.php'; ?>
        </header>
        <div class="overflow-y-auto">
            <main class="container max-w-screen-1200 min-h-screen mx-auto pt-16 pb-20 px-1 lg:px-0">
                <div class="flex gap-4 pt-6">
                    <div class="w-1/4 bg-white hidden lg:block flex flex-col text-gray-800 p-4 shadow-md rounded-lg ">
                        <nav>
                            <ul>
                                <li class="mb-4">
                                    <a href="#" class="block py-2 px-4 bg-blue-300 text-white rounded shadow-lg">
                                        Trang chủ
                                    </a>
                                </li>
                                <li class="mb-4">
                                    <a href="/my/account" class="block py-2 px-4 text-gray-800 rounded hover:bg-gray-300 hover:shadow-lg hover:-translate-y-1 transform transition-all duration-200">
                                        Tài khoản của bạn
                                    </a>
                                </li>
                                <li class="mb-4">
                                    <a href="/my/order" class="block py-2 px-4 text-gray-800 rounded hover:bg-gray-300 hover:shadow-lg hover:-translate-y-1 transform transition-all duration-200">
                                        Lịch sử mua hàng
                                    </a>
                                </li>
                                <li class="mb-4">
                                    <a href="/my/support" class="block py-2 px-4 text-gray-800 rounded hover:bg-gray-300 hover:shadow-lg hover:-translate-y-1 transform transition-all duration-200">
                                        Hỗ trợ
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="block py-2 px-4 text-gray-800 rounded hover:bg-red-500 hover:shadow-lg hover:-translate-y-1 transform transition-all duration-200">
                                        Thoát tài khoản
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                    <div class="w-full lg:w-3/4 rounded-lg space-y-6">


                    <div class="flex items-center space-x-6 cursor-pointer">
                        <div class="relative" onclick="openAvatarModal()">
                            <div class="absolute opacity-70 bottom-0 left-1/2 transform -translate-x-1/2 w-16 h-8 bg-white rounded-b-full flex items-center justify-center text-white text-xs">
                                <div class="opacity-100 text-gray-400">Thay đổi</div>
                            </div>
                            <img id="avatarProfile" 
                                src="<?=$_SESSION["Avatar"]?>" 
                                alt="User Avatar" 
                                class="w-16 h-16 rounded-full">
                        </div>
                        <div class="flex-1">
                            <div class="text-2xl font-bold"><?php echo $_SESSION['Ten']; ?></div>
                            <div class="text-sm text-gray-700"><?php echo $_SESSION['email']; ?></div>
                        </div>
                    </div>


                       
                        
                        <div class="flex flex-col md:flex-row gap-2">
                            <div class="flex-1 bg-white p-10 shadow-md rounded-lg">
                                <h3 class="text-xl text-center font-bold my-2" id="tongdon">4</h3>
                                <p class="text-sm text-center text-gray-600 mb-2">Đơn hàng</p>
                            </div>
                            <div class="flex-1 bg-white p-10 shadow-md rounded-lg">
                                <h3 class="text-xl text-center font-bold my-2" id="tongtien">14M</h3>
                                <p class="text-sm text-center text-gray-600 mb-2">Tổng tiền mua hàng</p>
                            </div>
                            <div class="flex-1 bg-white p-10 shadow-md rounded-lg">
                                <h3 class="text-xl text-center font-bold my-2" id="role"><?php echo $_SESSION["Role"]; ?></h3>
                                <p class="text-sm text-center text-gray-600 mb-2">Cấp bậc</p>
                            </div>
                        </div>

                        <p class="bg-gray-200 text-blue-500 text-sm block px-2 py-2 rounder-lg">
                            Cập nhật thông tin cá nhân và địa chỉ 
                            để có trải nghiệm đặt hàng nhanh và thuận tiện hơn.
                            <a href="/my/account" class="underline text-blue-800">Cập nhập?</a>
                        </p>
                    </div>
                </div>

                <div id="avatarModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
                    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md mx-auto">
                        <h2 class="text-xl font-semibold mb-4">Cập nhật ảnh đại diện</h2>

                        <form id="avatarForm">
                            <label for="avatarUpload" class="block text-sm font-medium text-gray-700 mb-2">Chọn ảnh:</label>
                            <input type="file" id="avatarUpload" name="avatarUpload" accept="image/*" class="w-full text-gray-700 p-2 border rounded mb-4">
                            <button type="button" onclick="uploadAvatar()" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">Tải lên</button>
                        </form>

                        <button onclick="closeAvatarModal()" class="mt-4 w-full text-center text-gray-500 hover:text-gray-700">Hủy</button>
                        <div id="result" class="mt-4 text-center text-sm text-red-500"></div>
                    </div>
                </div>
            </main>
        </div>
        <?php $page = 1; include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/footer.php'; ?>
    </div>
    <script src="/public/js/notyf.min.js"></script>
    <!-- <script src="/public/js/profile.js"></script> -->
    <script>
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
                    closeAvatarModal();

                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
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
            fetch(`${window.location.origin}/api/order/profile`, {
                method: 'GET',
                headers: { 
                    'Content-Type': 'application/json' 
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    document.getElementById('tongdon').textContent = data.So_don_hang;
                    document.getElementById('tongtien').textContent = `${Math.round(data.So_tien_da_mua).toLocaleString('vi-VN')} ₫`;
                }
            })
            .catch(error => {
                notyf.error('Lỗi kết nối');
            });
        }
    } catch { }
    </script>
</body>
</html> 
</body>
</html>