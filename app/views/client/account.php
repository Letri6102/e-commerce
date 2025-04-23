<?php
require_once dirname(__DIR__, 3) . '/config/db.php';

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
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/public/image/logo.png" type="image/x-icon">
    <link href="/public/css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/client.css">
    <title>Tài khoản | BKSTORE</title>
</head>

<body class="bg-gray-100">
    <div class="h-screen">
        <header id="header-content" class="sticky top-0 z-50">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/header.php'; ?>
        </header>
        <div class="overflow-y-auto">
            <main class="container max-w-2xl mx-auto min-h-screen pt-16 pb-24 px-2 lg:px-0">
                <div class="pt-6">
                    <div class="flex items-center space-x-6 cursor-pointer">
                        <div class="relative">
                            <img id="avatarProfile" 
                                src="<?php echo $_SESSION['Avatar']; ?>" 
                                alt="User Avatar" 
                                class="w-14 h-14 rounded-full">
                        </div>
                        <div class="flex-1">
                            <div class="text-2xl font-bold"><?php echo $_SESSION['Ten']; ?></div>
                            <div class="text-sm text-gray-700"><?php echo $_SESSION['email']; ?></div>
                        </div>
                    </div> 

                    <div class="bg-white rounded-md mt-6 text-gray-600">
                        <a href="/my/profile" class="flex justify-between items-center pr-4 pb-2">
                            <div class="px-3 py-2 cursor-pointer rounded-lg flex gap-2 items-center">
                                <img src="/public/image/icons8-home-100.png" alt="user" class="h-6">
                                <div>Trang cá nhân</div>
                            </div>
                            <svg height="15" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                <path
                                    d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z">
                                </path>
                            </svg>
                        </a>
                        <a href="/my/account" class="flex justify-between items-center pr-4 pb-2">
                            <div class="px-3 py-2 cursor-pointer rounded-lg flex gap-2 items-center">
                                <img src="/public/image/user.png" alt="user" class="h-6">
                                <div>Tài khoản của bạn</div>
                            </div>
                            <svg height="15" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                <path
                                    d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z">
                                </path>
                            </svg>
                        </a>
                        <a href="/my/order" class="flex justify-between items-center pr-4 pb-2">
                            <div class="px-3 py-2 cursor-pointer rounded-lg flex gap-2 items-center">
                                <img src="/public/image/order-100.png" alt="user" class="h-6">
                                <div>Lịch sửa mua hàng</div>
                            </div>
                            <svg height="15" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                <path
                                    d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z">
                                </path>
                            </svg>
                        </a>
                        <a href="/like" class="flex justify-between items-center pr-4 pb-2">
                            <div class="px-3 py-2 cursor-pointer rounded-lg flex gap-2 items-center">
                                <img src="/public/image/icons8-love-100.png" alt="user" class="h-6">
                                <div>Sản phẩm yêu thích</div>
                            </div>
                            <svg height="15" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                <path
                                    d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z">
                                </path>
                            </svg>
                        </a>
                    </div>

                    <div class="bg-white rounded-md mt-6 text-gray-600">
                        <a href="/promotion" class="flex justify-between items-center pr-4 pb-2">
                            <div class="px-3 py-2 cursor-pointer rounded-lg flex gap-2 items-center">
                                <img src="/public/image/discount-100.png" alt="user" class="h-6">
                                <div>Mã giảm giá</div>
                            </div>
                            <svg height="15" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                <path
                                    d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z">
                                </path>
                            </svg>
                        </a>
                        <a href="/comment" class="flex justify-between items-center pr-4 pb-2">
                            <div class="px-3 py-2 cursor-pointer rounded-lg flex gap-2 items-center">
                                <img src="/public/image/comment.png" alt="user" class="h-6">
                                <div>Góp ý bán sách</div>
                            </div>
                            <svg height="15" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                <path
                                    d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z">
                                </path>
                            </svg>
                        </a>
                        <a href="/suggest" class="flex justify-between items-center pr-4 pb-2">
                            <div class="px-3 py-2 cursor-pointer rounded-lg flex gap-2 items-center">
                                <img src="/public/image/icons8-pull-request-100.png" alt="user" class="h-6">
                                <div>Sách đã góp ý</div>
                            </div>
                            <svg height="15" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                <path
                                    d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z">
                                </path>
                            </svg>
                        </a>
                    </div>

                    <div class="bg-white rounded-md mt-6 text-gray-600">
                        <a href="/my/support" class="flex justify-between items-center pr-4 pb-2">
                            <div class="px-3 py-2 cursor-pointer rounded-lg flex gap-2 items-center">
                                <img src="/public/image/icons8-phone-100.png" alt="user" class="h-6">
                                <div>Hỗ trợ</div>
                            </div>
                            <svg height="15" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                <path
                                    d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z">
                                </path>
                            </svg>
                        </a>
                        <div onclick="logout()" class="flex justify-between items-center pr-4 pb-2 cursor-pointer">
                            <div class="px-3 py-2 cursor-pointer rounded-lg flex gap-2 items-center">
                                <img src="/public/image/exit.png" alt="user" class="h-6">
                                <div>Đăng xuất</div>
                            </div>
                            <svg height="15" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                <path
                                    d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z">
                                </path>
                            </svg>
                        </div>
                    </div>






                </div>
            </main>
        </div>
        <?php $page = 3;
        include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/footer.php'; ?>
    </div>
    <script src="/public/js/client.js"></script>
    <script>
        function logout() {
            fetch('/api/auth/logout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(() => {
                    window.location.href = '/';
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                });
        };
    </script>
</body>

</html>