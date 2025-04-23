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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/public/image/logo.png" type="image/x-icon">
    <link href="/public/css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/client.css">
    <link rel="stylesheet" href="/public/css/notyf.min.css">
    <title>Giỏ hàng | BKSTORE</title>
</head>

<body class="bg-gray-100">
    <div class="h-screen">
        <header id="header-content" class="sticky top-0 z-50">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/header.php'; ?>
        </header>
        <div class="overflow-y-auto">
            <main class="container max-w-2xl mx-auto min-h-screen pt-16 pb-24 px-2 lg:px-0">
                <div class="pt-6">
                    <div class="relative flex justify-center items-center">
                        <button class="absolute left-0 material-icons" onclick="goBack()">
                            <img src="/public/image/arrow.png" alt="arrow" class="w-8 h-8">
                        </button>
                        <div class="font-bold text-lg pb-2">
                            Giỏ hàng của bạn
                        </div>
                    </div>
                    <hr>
                    <div id="selectAllbox" class="pt-2 items-center flex gap-2 hidden">
                        <input type="checkbox" id="selectAll" class="w-4 h-4 cursor-pointer">
                        <label for="selectAll">Chọn tất cả</label>
                    </div>
                    
                    <div id="cart-items-container"></div>
                    <div class="flex flex-col items-center justify-center gap-2 pt-10" id="noProduct">
                        <img src="/public/image/icons8-sad-100.png" alt="sad-icon" class="w-8 h-8">
                        <div class="text-center text-gray-500">Chưa có sản phẩm nào trong giỏ hàng</div>
                    </div>
                </div>
                <div id="deletePopup" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden z-50">
                    <div class="bg-white rounded-lg p-6 max-w-sm w-full">
                        <h2 class="text-lg font-bold mb-4">Bạn có chắc chắn muốn xóa sản phẩm này không?</h2>
                        <div class="flex justify-end space-x-4">
                            <button id="cancelDelete" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">Hủy</button>
                            <button id="confirmDelete" class="bg-red-500 text-white px-4 py-2 rounded-lg">Xóa</button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div class="flex items-center justify-center">
            <div class="max-w-2xl w-full fixed bottom-0">
                <div class="flex items-center justify-between bg-white border w-full p-4 rounded-lg shadow-lg h-18">
                    <div>
                        <div class="font-bold text-custom-blue" id="totalAmount">Tạm tính: 0đ</div>
                        <div class="text-xs">Chưa bao gồm mã giảm giá</div>
                    </div>
                    <button id="orderButton" class="bg-custom-background text-white font-bold rounded-lg p-2">Mua ngay</button>
                </div>
            </div>
        </div>

    </div>
    <script src="/public/js/notyf.min.js"></script>
    <script src="/public/js/cart.js"></script>
</body>

</html>
