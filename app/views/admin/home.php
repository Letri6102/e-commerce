<?php
require_once dirname(__DIR__, 3) . '/config/db.php';
if(!($_SESSION["Role"] == 'Admin')){
    header("Location: /404");
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
    <link href="/public/css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/admin.css">
    <title>Trang chủ | ADMIN BKSTORE</title>
</head>

<body class="bg-gray-100">
    <div class="h-screen block md:flex">
        <?php $page = 1; include './partials/sidebar.php'; ?>
        <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden"></div>
        <div class="flex-1 overflow-x-hidden">
            <div class="flex flex-col">
                <div class="bg-white fixed top-0 left-0 right-0 z-10">
                    <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/admin/partials/header.php'; ?>
                </div>
                <div class="overflow-y-auto overflow-x-hidden pt-16">
                    <main class="container mx-auto min-h-screen px-4 py-4">
                        <nav class="flex gap-2 text pb-4 text-sm text-gray-700">
                            <a href="/" class="cursor-pointer hover:text-blue-500 focus:outline-none">BkStore.Vn</a>
                            <div>&rsaquo;</div>
                            <div class="text-gray-500">Admin</div>
                        </nav>
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                            <div class="bg-white rounded-lg p-4 h-24 shadow-sm flex items-center gap-4" style="background-color: #00D67F">
                                <div class="bg-gray-200 w-12 h-12 rounded-lg flex justify-center items-center">
                                    <img src="/public/image/user.png" class="w-6 h-6" alt="user">
                                </div>
                                <div class="flex flex-col justify-between">
                                    <div class="font-semibold text-lg text-white">Tổng người dùng</div>
                                    <div class="text-white" id="user-count"></div>
                                </div>
                            </div>
                            <div class="bg-white rounded-lg p-4 h-24 shadow-sm flex items-center gap-4" style="background-color: #007D88">
                                <div class="bg-gray-200 w-12 h-12 rounded-lg flex justify-center items-center">
                                    <img src="/public/image/icons8-order-96.png" class="w-6 h-6" alt="user">
                                </div>
                                <div class="flex flex-col justify-between">
                                    <div class="font-semibold text-lg text-white">Số đơn hàng</div>
                                    <div class="text-white" id="order-count"></div>
                                </div>
                            </div>
                            <div class="bg-white rounded-lg p-4 h-24 shadow-sm flex items-center gap-4" style="background-color: #0069F7">
                                <div class="bg-gray-200 w-12 h-12 rounded-lg flex justify-center items-center">
                                    <img src="/public/image/icons8-profit-100.png" class="w-6 h-6" alt="user">
                                </div>
                                <div class="flex flex-col justify-between">
                                    <div class="font-semibold text-lg text-white">Doanh thu</div>
                                    <div class="text-white" id="revenue-count"></div>
                                </div>
                            </div>
                            <div class="bg-white rounded-lg p-4 h-24 shadow-sm flex items-center gap-4" style="background-color: #262A2E">
                                <div class="bg-gray-200 w-12 h-12 rounded-lg flex justify-center items-center">
                                    <img src="/public/image/icons8-request-100.png" class="w-6 h-6" alt="user">
                                </div>
                                <div class="flex flex-col justify-between">
                                    <div class="font-semibold text-lg text-white">Yêu cầu</div>
                                    <div class="text-white" id="request-count"></div>
                                </div>
                            </div>
                        </div>
                   </main>
                </div>
                <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/admin/partials/footer.php'; ?>
            </div>
        </div>
    </div>
    <script src="/public/js/sidebar.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", async () => {
            try {
                const response = await fetch("/api/system/statistical");
                if (!response.ok){
                    console.log("lỗi")
                    return
                }
                const data = await response.json();
                if(data.success){
                    document.getElementById("user-count").textContent = data.tong_nguoi_dung;
                    document.getElementById("order-count").textContent = data.so_don_hang;
                    document.getElementById("revenue-count").textContent = new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    }).format(data.doanh_thu);
                    document.getElementById("request-count").textContent = data.so_yeu_cau_ban_sach;
                }else{
                    if(data.message === "Người dùng chưa đăng nhập"){
                        window.location.href = '/auth/login'
                        return
                    }else if(data.message === "Không có quyền truy cập"){
                        window.location.href = '/404'
                        return
                    }
                }   

            } catch (error) {
                console.log("lỗi")
            }
        });
    </script>

</body>

</html>