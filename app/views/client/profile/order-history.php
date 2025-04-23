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
    <title>Lịch sử mua hàng | BKSTORE</title>
</head>
<body class="bg-gray-100">
    <div class="h-screen">
        <header id="header-content" class="sticky top-0 z-50">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/header.php'; ?>
        </header>
        <div class="overflow-y-auto">
            <main class="container max-w-screen-1200 min-h-screen mx-auto pt-16 pb-20 px-1 lg:px-0">
                <div class="flex gap-4 pt-6">
                    <div class="w-1/4 bg-white hidden lg:block flex flex-col text-gray-800 p-4 shadow-md rounded-lg min-h-screen">
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
                                    <a href="/my/order" class="block py-2 px-4 bg-blue-300 text-white rounded shadow-lg">
                                        Lịch sử mua hàng
                                    </a>
                                </li>
                                <li class="mb-4">
                                    <a href="/my/support" class="block py-2 px-4 text-gray-800 rounded hover:bg-gray-300 hover:shadow-lg hover:-translate-y-1 transform transition-all duration-200">
                                        Hồ trợ
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
                        <div class="flex gap-2 pb-4">
                            <select id="status-filter" class="px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300">
                                <option value="all">Tất cả</option>
                                <option value="Đã giao hàng">Đã giao hàng</option>
                                <option value="Đang vận chuyển">Đang vận chuyển</option>
                                <option value="Đã xác nhận">Đã xác nhận</option>
                                <option value="Chờ xác nhận">Chờ xác nhận</option>
                                <option value="Đã hủy">Đã hủy</option>
                            </select>
                        </div>

                        <div id="order-list" class="space-y-6"></div>

                        <div id="pagination" class="flex justify-center gap-2 pt-4"></div>
                    </div>
                </div>
            </main>
        </div>
        <?php $page = 1; include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/footer.php'; ?>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const orderList = document.getElementById('order-list');
            const pagination = document.getElementById('pagination');
            const statusFilter = document.getElementById('status-filter');;
            let ordersData = [];
            let currentPage = 1;
            const ordersPerPage = 3;

            fetch(`${window.location.origin}/api/order/paid`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        ordersData = data.message;
                        renderOrders('all', currentPage);
                    } else {
                        orderList.innerHTML = `
                        <div class="flex flex-col items-center justify-center gap-2 pt-10" id="noProduct">
                            <img src="/public/image/icons8-sad-100.png" alt="sad-icon" class="w-8 h-8">
                            <div class="text-center text-gray-500">Chưa có sản phẩm nào trong lịch sử mua hàng</div>
                        </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error("Error fetching orders:", error);
                });

            function renderOrders(status, page) {
                orderList.innerHTML = '';
                const filteredOrders = status === 'all' ? ordersData : ordersData.filter(order => order.trang_thai === status);
                const start = (page - 1) * ordersPerPage;
                const end = start + ordersPerPage;
                const paginatedOrders = filteredOrders.slice(start, end);
                if(paginatedOrders.length == 0) {
                    orderList.innerHTML = `
                        <div class="flex flex-col items-center justify-center gap-2 pt-10" id="noProduct">
                            <img src="/public/image/icons8-sad-100.png" alt="sad-icon" class="w-8 h-8">
                            <div class="text-center text-gray-500">Chưa có sản phẩm nào trong trạng thái "${status}"</div>
                        </div>
                        `;
                } 
                paginatedOrders.forEach(order => {
                    const orderItem = document.createElement('div');
                    orderItem.classList.add('flex', 'flex-col', 'md:flex-row', 'bg-white', 'border', 'rounded-lg', 'p-4', 'md:h-42');
                    
                    let bgColor;
                    let textColor;
                    switch (order.trang_thai) {
                        case 'Đã giao hàng':
                            bgColor = 'bg-green-200';
                            textColor = 'text-green-800';
                            break;
                        case 'Đang vận chuyển':
                            bgColor = 'bg-blue-200';
                            textColor = 'text-blue-800';
                            break;
                        case 'Đã hủy':
                            bgColor = 'bg-red-200';
                            textColor = 'text-red-800';
                            break;
                        case 'Đã xác nhận':
                            bgColor = 'bg-blue-200';
                            textColor = 'text-blue-800';
                            break;
                        case 'Chờ xác nhận':
                            bgColor = 'bg-red-200';
                            textColor = 'text-red-800';
                            break;
                        default:
                            bgColor = 'bg-gray-200';
                            textColor = 'text-white';
                    }

                    orderItem.innerHTML = `
                       <div class="flex-1">
                            <div class="flex">
                                <div class="w-32">
                                    <img src="/${order.hinh[0]}" alt="product">
                                </div>
                                <div class="flex-1">
                                    <div class="h-full w-full">
                                        <div class="flex flex-col justify-between md:flex-1 md:px-4">
                                            <div class="flex">
                                                <div>
                                                    <p class="text-gray-700 font-medium">${order.ten_sp}</p>
                                                    <p class="text-gray-600">Ngày đặt: <span class="font-medium text-gray-700">${order.ngay_dat}</span></p>
                                                    <p class="text-gray-600">Số lượng: <span class="font-medium text-gray-700">${order.so_luong_san_pham}</span></p>
                                                    <p class="text-gray-700 font-semibold">${order.gia.toLocaleString('vi-VN')}₫</p>
                                                </div>
                                            </div>
                                            <div class="md:flex md:flex-col md:justify-between md:items-end mt-2 md:mt-0">
                                                <p class="text-sm px-3 py-1 ${bgColor} ${textColor} font-medium rounded-full inline">${order.trang_thai}</p>
                                                <a href="/my/order/detail?id=${order.id}" class="text-blue-500 hover:underline font-medium  block">Xem chi tiết>></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    orderList.appendChild(orderItem);
                });

                renderPagination(filteredOrders.length, page);
            }

            function renderPagination(totalOrders, currentPage) {
                pagination.innerHTML = '';
                const totalPages = Math.ceil(totalOrders / ordersPerPage);
                
                for (let i = 1; i <= totalPages; i++) {
                    const pageButton = document.createElement('button');
                    pageButton.innerText = i;
                    pageButton.classList.add('px-4', 'py-2', 'rounded', 'mx-1');
                    pageButton.classList.add('bg-blue-500', 'text-white');
                    
                    pageButton.addEventListener('click', () => {
                        renderOrders(statusFilter.value, i);
                        currentPage = i;
                    });

                    pagination.appendChild(pageButton);
                }
            }

            statusFilter.addEventListener('change', () => {
                renderOrders(statusFilter.value, 1);
            }); 
        });
    </script>
</body>
</html>