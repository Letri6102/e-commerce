<?php
require_once dirname(__DIR__, 3) . '/config/db.php';

if($TrangThaiBaoTri && $_SESSION['Role'] != 'Admin'){
    header("Location: /maintain");
    exit;
}

if(isset($_SESSION["email"])){
    header("Location: /");
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
    <title>Đăng ký | BKSTORE</title> 
</head>
<body class="bg-gray-100">
    <div class="h-screen">
        <header id="header-content" class="sticky top-0 z-50">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/header.php'; ?>
        </header>

        <div class="overflow-y-auto">
            <main class="container max-w-screen-1200 mx-auto min-h-screen pt-20 px-2 lg:px-0 flex justify-center items-center">
                <div class="w-full max-w-xl p-8 bg-white shadow-md rounded-lg">
                    <h2 class="text-3xl font-bold text-center mb-4 text-blue-600">Đăng ký</h2>
                    <form id="register-form">
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 font-semibold mb-2">Họ và Tên
                                <span>*</span>
                            </label>
                            <input id="name" name="name" type="text"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                placeholder="Nhập họ và tên">
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 font-semibold mb-2">Email
                                <span>*</span>
                            </label>
                            <input id="email" name="email" type="email"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                placeholder="Nhập email">
                        </div>

                        <div class="mb-4 relative">
                            <label for="password" class="block text-gray-700 font-semibold mb-2">Mật khẩu
                                <span>*</span>
                            </label>
                            <input id="password" name="password" type="password"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                placeholder="Nhập mật khẩu">
                            <button type="button" onclick="togglePasswordVisibility()"
                                class="absolute inset-y-0 right-0 top-7 flex items-center pr-3 text-gray-500">
                                <svg id="show-icon" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg id="hide-icon" class="h-5 w-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.038 10.038 0 013.262-4.982M10 10l6 6m-6 0l6-6m-6 6a3 3 0 013-3m0 6a3 3 0 003-3"/>
                                </svg>
                            </button>
                        </div>

                        <div class="mb-4 relative">
                            <label for="auth_password" class="block text-gray-700 font-semibold mb-2">Xác nhận mật khẩu
                                <span>*</span>
                            </label>
                            <input id="auth_password" name="auth-password" type="password" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                placeholder="Nhập lại mật khẩu">
                            <button type="button" onclick="togglePasswordVisibility()"
                                    class="absolute inset-y-0 right-0 top-7 flex items-center pr-3 text-gray-500">
                                <svg id="show-auth-icon" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg id="hide-auth-icon" class="h-5 w-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.038 10.038 0 013.262-4.982M10 10l6 6m-6 0l6-6m-6 6a3 3 0 013-3m0 6a3 3 0 003-3"/>
                                </svg>
                            </button>
                        </div>


                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                            <input type="checkbox" id="terms" class="h-4 w-4 text-blue-600 cursor-pointer focus:ring-blue-500">
                            <label for="terms" class="ml-2 text-gray-700 text-sm">Tôi đồng ý với các <a href="#" class="text-blue-500">điều khoản</a></label>
                            </div>
                        </div>

                        <button id="register-btn" type="submit" class="w-full bg-green-500 text-white py-2 rounded-lg font-bold hover:bg-green-600 transition duration-300">Đăng ký</button>
                    </form>
            
                    <p class="text-center text-gray-600 mt-6">Bạn đã có tài khoản? <a href="/auth/login" class="text-blue-500 font-bold">Đăng nhập</a></p>
                </div>
            </main>
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
        document.getElementById('register-form').addEventListener('submit', function(event){
            event.preventDefault();

            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const auth_password = document.getElementById('auth_password').value;
            const terms = document.getElementById('terms').checked;

            if (!name || !email || !password || !auth_password) {
                notyf.error("Vui lòng điền đầy đủ thông tin.");
                return;
            }

            if (password !== auth_password) {
                notyf.error("Xác nhận mật khẩu không khớp.");
                return;
            }

            if (!terms) {
                notyf.error("Bạn phải đồng ý với các điều khoản.");
                return;
            }
            
            const button = document.getElementById('register-btn')
            button.textContent = 'Đang xử lý...';
            button.disabled = true;

            fetch(`${window.location.origin}/api/auth/register`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name: name,
                    email: email,
                    password: password,
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    notyf.success("Đăng ký thành công!");
                    setTimeout(() => {
                        window.location.href = "/auth/login";
                    }, 2000);
                } else {
                    notyf.error(data.message);
                }
            })
            .catch(error => notyf.error("Đã xảy ra lỗi khi đăng ký!"))
            .finally(() => {
                button.textContent = 'Đăng ký';
                button.disabled = false;
            });
        });
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const authPasswordInput = document.getElementById('auth_password');
            const showIcon = document.getElementById('show-icon');
            const hideIcon = document.getElementById('hide-icon');
            const showAuthIcon = document.getElementById('show-auth-icon');
            const hideAuthIcon = document.getElementById('hide-auth-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                authPasswordInput.type = 'text';

                showIcon.classList.add('hidden');
                hideIcon.classList.remove('hidden');
                showAuthIcon.classList.add('hidden');
                hideAuthIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                authPasswordInput.type = 'password';

                showIcon.classList.remove('hidden');
                hideIcon.classList.add('hidden');
                showAuthIcon.classList.remove('hidden');
                hideAuthIcon.classList.add('hidden');
            }
        }
    </script>
</body>
</html>