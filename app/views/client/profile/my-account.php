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
            <main class="container max-w-screen-1200 mx-auto pt-16 pb-20 px-1 lg:px-0">
                <div class="flex gap-4 pt-6">
                    <div class="w-1/4 bg-white hidden lg:block flex flex-col text-gray-800 p-4 shadow-md rounded-lg">
                        <nav>
                            <ul>
                                <li class="mb-4">
                                    <a href="/my/profile" class="block py-2 px-4 text-gray-800 rounded hover:bg-gray-300 hover:shadow-lg hover:-translate-y-1 transform transition-all duration-200">
                                        Trang chủ
                                    </a>
                                </li>
                                <li class="mb-4">
                                    <a href="#" class="block py-2 px-4 bg-blue-300 text-white rounded shadow-lg">
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

                    <div class="w-full lg:w-3/4 rounded-lg space-y-2">
                        <div class="flex flex-col md:flex-row gap-2">
                            <div class="flex-1 bg-white p-10 shadow-md rounded-lg">
                                <h2 class="text-3xl font-bold mb-6">Thông tin tài khoản</h2>
                               
                                <div class="flex flex-col md:flex-row md:space-x-4 mb-4">
                                    <div class="flex-1">
                                        <label for="full-name" class="block text-gray-700 font-semibold mb-2">Họ và Tên</label>
                                        <input type="text" id="full-name" 
                                        class="block w-full px-3 py-2 border border-gray-300 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                                        value="">
                                    </div>
                                    <div class="flex-1">
                                        <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                                        <input type="email" id="email" 
                                        class="block w-full px-3 py-2 border border-gray-300 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm bg-gray-100 cursor-not-allowed" 
                                        value="<?php echo $_SESSION["email"]; ?>" readonly>
                                    </div>
                                </div>

                                <div class="flex flex-col md:flex-row md:space-x-4 mb-4">
                                    <div class="flex-1">
                                        <label for="phone" class="block text-gray-700 font-semibold mb-2">Số điện thoại</label>
                                        <input type="tel" id="phone" 
                                            class="block w-full px-3 py-2 border border-gray-300 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                                            pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
                                            placeholder="Nhập số điện thoại"> 
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="address" class="block text-gray-700 font-semibold mb-2">Địa chỉ</label>
                                    <input type="text" id="address" 
                                        class="block w-full px-3 py-2 border border-gray-300 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                                        placeholder="Nhập địa chỉ của bạn">
                                </div>

                                <div class="mb-6">
                                    <label class="block text-gray-700 font-semibold mb-1">Giới tính</label>
                                    <div class="flex space-x-4">
                                            <label class="flex items-center">
                                            <input type="radio" id="Male" name="gender" value="Male" class="form-radio text-blue-500">
                                            <span class="ml-2 text-gray-700">Nam</span>
                                            </label>
                                            <label class="flex items-center">
                                            <input type="radio" id="Female" name="gender" value="Female" class="form-radio text-blue-500">
                                            <span class="ml-2 text-gray-700">Nữ</span>
                                            </label>
                                            <label class="flex items-center">
                                            <input type="radio" id="other" name="gender" value="NULL" class="form-radio text-blue-500">
                                            <span class="ml-2 text-gray-700">Tùy chỉnh</span>
                                            </label>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="level" class="block text-gray-700 font-semibold mb-2">Cấp độ</label>
                                    <input type="text" id="level" 
                                    class="block w-full px-3 py-2 border border-gray-300 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm bg-gray-100 bg-gray-100 cursor-not-allowed" 
                                    readonly>
                                </div>

                                <div class="text-right">
                                    <button onclick="updateUserInfo()"
                                        class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Lưu thay đổi
                                    </button>
                                </div>
                            </div>

                            <div class="flex-1 bg-white p-10 shadow-md rounded-lg">
                                <h2 class="text-3xl font-bold mb-6">Đổi mật khẩu</h2>
                                <form  id="changePasswordForm">
                                    <div class="mb-4">
                                        <label for="old-password" class="block text-gray-700 font-semibold mb-2">Mật khẩu cũ</label>
                                        <input type="password" id="old-password" class="block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Nhập mật khẩu cũ" required>
                                    </div>

                                    <div class="mb-4">
                                        <label for="new-password" class="block text-gray-700 font-semibold mb-2">Mật khẩu mới</label>
                                        <input type="password" id="new-password" class="block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Nhập mật khẩu mới" required>
                                    </div>

                                    <div class="mb-4">
                                        <label for="auth-password" class="block text-gray-700 font-semibold mb-2">Xác nhận mật khẩu mới</label>
                                        <input type="password" id="auth-password" class="block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Xác nhận mật khẩu" required>
                                    </div>

                                    <div class="text-right">
                                        <button id="change-password-btn" class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Thay đổi
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <script src="/public/js/notyf.min.js"></script>
                        <script>
                            var notyf = new Notyf({
                                duration: 3000,
                                position: {
                                x: 'right',
                                y: 'top',
                                },
                            });

                            const columnTitles = {
                                date: 'Thời gian',
                                content: 'Nội dung',
                            };

                            let data = [];

                            document.getElementById("changePasswordForm").addEventListener("submit", function (event) {
                                event.preventDefault();
                                const oldPassword = document.getElementById("old-password").value;
                                const newPassword = document.getElementById("new-password").value;
                                const confirmPassword = document.getElementById("auth-password").value;

                                if (newPassword !== confirmPassword) {
                                    notyf.error('Mật khẩu mới và mật khẩu xác nhận không khớp.');
                                    return;
                                }

                                const button = document.getElementById("change-password-btn");
                                button.textContent = "Đang xử lý...";
                                button.disabled = true;

                                fetch(`${window.location.origin}/api/auth/change`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        current_password: oldPassword,
                                        new_password: newPassword,
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        notyf.success(data.message);
                                        document.getElementById("changePasswordForm").reset();
                                    } else {
                                        notyf.error("Lỗi: " + data.message);
                                    }
                                })
                                .catch(error => {
                                    notyf.error("Đã xảy ra lỗi:", error);
                                })
                                .finally(() => {
                                    button.textContent = "Thay đổi";
                                    button.disabled = false;
                                });
                            });

                            async function fetchUserData() {
                                fetch(`${window.location.origin}/api/user/info`, {
                                    method: 'GET',
                                    headers: { 
                                        'Content-Type': 'application/json' 
                                    },
                                    credentials: 'same-origin'
                                })
                                .then(response => response.json())
                                .then(dataUser => {
                                    try {
                                        document.getElementById("full-name").value = dataUser.thong_tin.name;
                                        if(dataUser.thong_tin.phone) {
                                            document.getElementById("phone").value = dataUser.thong_tin.phone;
                                        }
                                        if(dataUser.thong_tin.address) {
                                            document.getElementById("address").value = dataUser.thong_tin.address;
                                        }
                                        if(!dataUser.thong_tin.sex) {
                                            document.getElementById("other").checked = true;
                                            document.getElementById("Male").checked = false;
                                            document.getElementById("Female").checked = false;
                                        } else if (dataUser.thong_tin.sex == "Male") {
                                            document.getElementById("other").checked = false;
                                            document.getElementById("Male").checked = true;
                                            document.getElementById("Female").checked = false;
                                        } else {
                                            document.getElementById("other").checked = false;
                                            document.getElementById("Male").checked = false;
                                            document.getElementById("Female").checked = true;
                                        }
                                        document.getElementById("level").value = dataUser.thong_tin.role;

                                        dataUser['nhat_ky'].forEach(diaryData => {
                                        data.push({
                                            date: diaryData.ThoiGian,
                                            content: diaryData.NoiDung,
                                        });

                                        const event = new CustomEvent('dataReady', { detail: dataUser });
                                        window.dispatchEvent(event);
                                    });
                                    } catch (error) {
                                        console.error('Lỗi phân tích JSON:', error);
                                        notyf.error('Lỗi phản hồi máy chủ');
                                    }
                                })
                                .catch(error => {
                                    console(error);
                                    notyf.error('Lỗi kết nối' + error);
                                });
                            }

                            function updateUserInfo() {
                                const name = document.getElementById('full-name').value.trim();
                                const sex = document.querySelector('input[name="gender"]:checked') ? document.querySelector('input[name="gender"]:checked').value.trim() : '';
                                const phone = document.getElementById('phone').value.trim();
                                const address = document.getElementById('address').value.trim();

                                if (name.length < 3 || name.length > 50 || !/^[a-zA-Z\s]+$/.test(name)) {
                                    notyf.error('Tên không hợp lệ. Vui lòng nhập tên từ 3-50 ký tự, chỉ bao gồm chữ và khoảng trắng.');
                                    return;
                                }

                                if (sex !== "Male" && sex !== "Female" && sex !== "NULL") {
                                    notyf.error('Giới tính không hợp lệ.');
                                    return;
                                }

                                if (!/^\d{10}$/.test(phone)) {
                                    notyf.error('Số điện thoại không hợp lệ. Vui lòng nhập số đúng 10 chữ số.');
                                    return;
                                }

                                if (address.length < 5 || address.length > 100) {
                                    notyf.error('Địa chỉ không hợp lệ. Vui lòng nhập địa chỉ từ 5-100 ký tự.');
                                    return;
                                }

                                fetch(`${window.location.origin}/api/user/info`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    credentials: 'same-origin',
                                    body: JSON.stringify({ name, sex, phone, address })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        notyf.success(data.message);
                                        fetchUserData();
                                    } else {
                                        notyf.error(data.message);
                                    }
                                })
                                .catch(error => {
                                    notyf.error(data.error);
                                });
                            }

                            window.onload = async function() {
                                await fetchUserData(); 
                            };

                        </script>
                        <?php
                            $title = "Nhật ký người dùng";
                            include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/table.php';
                        ?>
                    </div>
                </div>
            </main>
        </div>
        <?php $page = 1; include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/footer.php'; ?>
    </div>
</body>
</html>