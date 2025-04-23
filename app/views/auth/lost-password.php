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
    <title>Quên mật khẩu | BKSTORE</title> 
</head>
<body class="bg-gray-100">
    <div class="h-screen">
        <header id="header-content" class="sticky top-0 z-50">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/header.php'; ?>
        </header>

        <div class="overflow-y-auto">
            <main class="container max-w-screen-1200 mx-auto min-h-screen pt-20 pb-20 px-2 lg:px-0 flex justify-center items-center">
                <div class="w-full max-w-md p-8 bg-white shadow-lg rounded-lg">
                    <h2 class="text-3xl font-bold text-center mb-4 text-blue-600">Quên mật khẩu</h2>
                    <p class="text-center text-gray-600 mt-3 mb-6">Vui lòng nhập địa chỉ email. Bạn sẽ nhận được mật khẩu mới trong hộp thư.</p> 
                    <form id="forgot_password">
                        <div>
                            <label for="email" class="font-bold block text-sm text-gray-900 my-2">Địa chỉ Email</label>
                            <input id="email" name="email" type="email" autocomplete="email"
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                                placeholder="Email address">
                        </div>

                        <div>
                            <button id="reset-btn" type="submit" class="w-full flex justify-center py-2 px-4 mt-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Đặt lại mật khẩu
                            </button>
                        </div>
                    </form>
                    <div class="text-sm mt-3">
                        <a href="/auth/login" class="font-medium text-indigo-600 hover:text-indigo-500"> Về trang đăng nhập </a>
                    </div>
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

        document.getElementById('forgot_password').addEventListener('submit', function(event) {
            event.preventDefault();
            const email = document.getElementById('email').value;

            if (!email) {
                notyf.error('Vui lòng nhập đầy đủ thông tin!');
                return;
            }

            const button = document.getElementById('reset-btn')
            button.textContent = 'Đang xử lý...';
            button.disabled = true;

            fetch(`${window.location.origin}/api/auth/forgot`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    email: email
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    notyf.success('Gửi yêu cầu thành công!');
                    setTimeout(() => {
                        window.location.href = "/auth/login";
                    }, 2000);
                } else {
                    notyf.error('Gửi yêu cầu thất bại!');
                }
            })
            .catch(error => {
                notyf.error('Gửi yêu cầu thất bại!');
            })
            .finally(() => {
                button.textContent = 'Đặt lại mật khẩu';
                button.disabled = false;
            });
        });
    </script>

</body>
</html>