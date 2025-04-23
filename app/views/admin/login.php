<?php
require_once dirname(__DIR__, 3) . '/config/db.php';
if(isset($_SESSION["email"])){
    header("Location: /admin");
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
    <title>Đăng nhập | BKSTORE ADMIN</title> 
</head>
<body class="bg-gray-100">
    <div class="h-screen">

        <div class="overflow-y-auto">
            <main class="container max-w-screen-1200 mx-auto min-h-screen pt-20 pb-20 px-2 lg:px-0 flex justify-center items-center">
                <div class="w-full max-w-xl p-8 bg-white shadow-md rounded-lg">
                    <h2 class="text-3xl font-bold text-center mb-4 text-gray-600">Quản trị hệ thống</h2>
                    <form id="login-form" class="mt-8 space-y-6">
                        <div>
                            <div>                                    
                                <label for="email" class="font-bold block text-sm text-gray-900 my-2">Địa chỉ Email
                                <span>*</span>
                                </label>
                                <input id="email" name="email" type="email" autocomplete="email" required
                                    class="block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                                    placeholder="Email address">
                            </div>
                            <div class="mt-3 relative">
                                <label for="password" class="font-bold block text-sm text-gray-900 my-2">
                                    Mật khẩu <span>*</span>
                                </label>
                                <input id="password" name="password" type="password" autocomplete="password" required
                                    class="block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                                    placeholder="Password">
                                <button type="button" onclick="togglePasswordVisibility()"
                                    class="absolute inset-y-0 right-0 top-6 flex items-center pr-3 text-gray-500">
                                    <svg id="show-icon" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg id="hide-icon" class="h-5 w-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.038 10.038 0 013.262-4.982M10 10l6 6m-6 0l6-6m-6 6a3 3 0 013-3m0 6a3 3 0 003-3"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div>
                            <button id="submit" type="submit"
                                class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Đăng nhập
                            </button>
                        </div>
                        
                    </form>
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
        document.getElementById('login-form').addEventListener('submit', function(event) {
            event.preventDefault()
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            if (!email || !password) {
                notyf.error('Vui lòng nhập đầy đủ thông tin!');
                return;
            }

            const button =document.getElementById('submit')
            button.textContent = 'Đang xử lý...';
            button.disabled = true;

            fetch(`${window.location.origin}/api/auth/login`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    email: email,
                    password: password,
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    notyf.success('Đăng nhập thành công!');
                    setTimeout(() => {
                        window.location.href = '/admin'
                    }, 2000);
                } else {
                    notyf.error(data.message);
                }
            })
            .catch(error => notyf.error("Đã xảy ra lỗi đăng nhập!"))
            .finally(() => {
                button.textContent = 'Đăng nhập';
                button.disabled = false;
            });
        });
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const showIcon = document.getElementById('show-icon');
            const hideIcon = document.getElementById('hide-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                showIcon.classList.add('hidden');
                hideIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                showIcon.classList.remove('hidden');
                hideIcon.classList.add('hidden');
            }
        }
    </script>
</body>
</html>