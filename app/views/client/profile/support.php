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
    <link rel="stylesheet" href="/public/css/notyf.min.css">
    <link rel="stylesheet" href="/public/css/client.css">
    <title>Hỗ trợ | BKSTORE</title>
</head>
<body class="bg-gray-100">
    <div class="h-screen">
        <header id="header-content" class="sticky top-0 z-50">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/header.php'; ?>
        </header>
        <div class="overflow-y-auto">
            <main class="container max-w-screen-1200 min-h-screen mx-auto pt-16 pb-20 px-1 lg:px-0">
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
                                    <a href="#" class="block py-2 px-4 bg-blue-300 text-white rounded shadow-lg">
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

                    <div id="contact-container" class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 w-full lg:w-3/4 rounded-lg"></div>
                </div>
            </main>
        </div>
        <?php $page = 1; include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/footer.php'; ?>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetch(`${window.location.origin}/api/system/contact`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                            const contactContainer = document.getElementById("contact-container");
                            contactContainer.innerHTML = "";

                            data.info.forEach((contact) => {
                            const contactItem = document.createElement("div");
                            contactItem.className =
                                "flex-1 bg-white p-5 shadow-md rounded-xl flex items-center";

                            // Nội dung HTML
                            contactItem.innerHTML = `
                                <div class="w-16 h-16 flex-shrink-0 mr-4">
                                <img src="${contact.hinh_anh}" alt="${contact.loai}" class="w-full h-full object-cover rounded-full">
                                </div>
                                <div>
                                <h5 class="font-bold text-lg text-gray-800 capitalize">${contact.loai}</h5>
                                <p class="text-lg text-yellow-500">${contact.thong_tin}</p>
                                </div>
                            `;

                            contactContainer.appendChild(contactItem);
                            });
                        } else {
                            console.log("lỗi kết nối với api")
                        }
                })
                .catch(error => console.error('Error fetching contact info:', error));
        });
    </script>
</body>
</html>