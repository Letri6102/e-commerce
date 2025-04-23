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
    <title>Chi tiết đơn hàng | BKSTORE</title>
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
                                    <a href="/my/order" class="block py-2 px-4 bg-blue-300 text-white rounded shadow-lg">
                                        Lịch sử mua hàng
                                    </a>
                                </li>
                                <li class="mb-4">
                                    <a href="/my/support" class="block py-2 px-4 text-gray-800 rounded hover:bg-gray-300 hover:shadow-lg hover:-translate-y-1 transform transition-all duration-200">
                                        Hồ sơ
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

                    <div class="w-full lg:w-3/4 rounded-lg space-y-5">
                        <div class="relative flex justify-center items-center">
                            <button class="absolute left-0 material-icons" onclick="goBack()">
                                <img src="/public/image/arrow.png" alt="arrow" class="w-8 h-8">
                            </button>
                            <div class="font-bold text-lg pb-2">
                                Chi tiết đơn hàng
                            </div>
                        </div>
                        <hr>
                        <div>
                            <p class="text-gray-500 mt-1">Mã đơn hàng: <span class="font-medium text-gray-700" id="order-id"></span></p>
                            <p class="text-gray-500 mt-1">Ngày đặt: <span class="font-medium text-gray-700" id="order-date"></span></p>
                            <p id="order-status" class="text-sm px-3 py-1 font-medium rounded-full inline-block mt-2"></p>
                        </div>
                        
                        <div>
                            <div id="products-container" class="space-y-2"></div>
                            <div class="pb-2 pt-6 font-bold text-custom-blue">THÔNG TIN THANH TOÁN</div>
                            <div class="bg-white rounded-lg border w-full p-4 pl-6 pr-6">
                                <div class="flex justify-between text-gray-500 pt-6">
                                    <div>Phương thức thanh toán:</div>
                                    <div class="text-black" id="payment"></div>
                                </div>
                                <div class="flex justify-between text-gray-500 pt-4">
                                    <div>Tiền hàng (Tạm tính)</div>
                                    <div class="text-black" id="subtotal">100.000đ</div>
                                </div>
                                <div class="flex justify-between text-gray-500 pt-4">
                                    <div>Giảm giá</div>
                                    <div class="text-black" id="discount">0đ</div>
                                </div>
                                <div class="flex justify-between text-gray-500 pt-4 pb-4">
                                    <div>Phí vận chuyển</div>
                                    <div class="text-black">Miễn phí</div>
                                </div>
                                <hr>
                                <div class="flex justify-between pt-4">
                                    <div class="font-bold">Tổng tiền</div>
                                    <div class="text-black" id="total-amount">100.000đ</div>
                                </div>
                            </div>
                            <div class="pb-2 pt-6 font-bold text-custom-blue">THÔNG TIN KHÁCH HÀNG</div>
                            <div class="bg-white rounded-lg border h-18 w-full p-4 pl-6 pr-6">
                                <div class="flex gap-4">
                                    <div class="w-1/2">
                                        <div class="text-gray-600 mb-2">
                                            Tên khách hàng
                                        </div>
                                        <input type="text" class="border rounded-lg w-full p-2 cursor-not-allowed" id="customer-name" readonly>
                                    </div>
                                    <div class="w-1/2">
                                        <div class="text-gray-600 mb-2">
                                            Số điện thoại
                                        </div>
                                        <input type="text" class="border rounded-lg w-full p-2 cursor-not-allowed" id="customer-phone" readonly>
                                    </div>
                                </div>
                                <div class="text-gray-600 mb-2 mt-2">
                                    Địa chỉ nhận hàng
                                </div>
                                <input type="text" class="border rounded-lg w-full p-2 cursor-not-allowed" id="customer-address" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <?php $page = 1; include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/footer.php'; ?>
    </div>

    <div id="rating-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Đánh giá sản phẩm:</h2>
            <div class="flex space-x-1">
                <button class="star text-gray-400 hover:text-yellow-500" data-value="1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 inline-block" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                    </svg>
                </button>
                <button class="star text-gray-400 hover:text-yellow-500" data-value="2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 inline-block" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                    </svg>
                </button>
                <button class="star text-gray-400 hover:text-yellow-500" data-value="3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 inline-block" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                    </svg>
                </button>
                <button class="star text-gray-400 hover:text-yellow-500" data-value="4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 inline-block" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                    </svg>
                </button>
                <button class="star text-gray-400 hover:text-yellow-500" data-value="5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 inline-block" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                    </svg>
                </button>
            </div>
            <div class="mt-4">
                <label for="review-text" class="block text-gray-700 font-bold mb-2">Nội dung đánh giá:</label>
                <textarea id="review-text" rows="4" class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Nhập nội dung đánh giá của bạn..."></textarea>
            </div>
            <p id="rating-output" class="text-gray-700 mt-4">Rate: <span class="font-bold">0</span> stars</p>
            <div class="mt-4 flex justify-end space-x-2">
                <button onclick="closeRating()" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400">Đóng</button>
                <button onclick="saving()" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">Lưu</button>
            </div>
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
        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const ID_DonHang = urlParams.get('id');

            if (ID_DonHang) {
                fetch(`${window.location.origin}/api/order/info`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ ID_DonHang: ID_DonHang })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const info = data.info;

                        document.getElementById('order-id').textContent = info.id;
                        document.getElementById('order-date').textContent = info.ngay_dat;

                        const statusOrder = document.getElementById('order-status');
                        statusOrder.textContent = info.trang_thai;
                        let bgColor;
                        let textColor;

                        switch (info.trang_thai) {
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
                                textColor = 'text-gray-800';
                                bgColor = 'bg-gray-200';
                        }
                        statusOrder.classList.add(bgColor);
                        statusOrder.classList.add(textColor);

                        const productsContainer = document.getElementById('products-container');
                        let productFirst = true;
                        productsContainer.innerHTML = ''; 
                        info.danh_sach_san_pham.forEach(product => {
                            let productHTML = '';
                            if(product.isReviewed || info.trang_thai != "Đã giao hàng") {
                                productHTML = `
                                <div class="flex flex-col md:flex-row bg-white border rounded-lg p-4 md:h-46">
                                    <div class="flex-1">
                                        <div class="flex">
                                            <div class="w-32">
                                                <img src="/${product.anh[0]}" alt="product">
                                            </div>
                                            <div class="flex-1">
                                                <div class="h-full w-full">
                                                    <div class="flex flex-col justify-between md:flex-1 md:px-4">
                                                        <div class="flex">
                                                            <div>
                                                                <p class="text-gray-700 font-medium">Tên sản phẩm: ${product.ten}</p>
                                                                <p class="text-gray-700 font-semibold">Số lượng: ${product.so_luong}</p>
                                                                <p class="text-gray-700 font-semibold">Giá: ${product.gia_sau_giam_gia.toLocaleString('vi-VN')} đ</p>
                                                            </div>
                                                        </div>
                                                        <div class="flex flex-col md:justify-between items-start md:items-end mt-2 md:mt-0 md:pt-8">
                                                            <div class="flex justify-end items-center">
                                                                    <div class="flex items-center space-x-2">
                                                                        <button
                                                                            onclick="addToCart(${product.id})"
                                                                            class="flex justify-center py-2 px-4 border text-sm font-medium rounded-md text-black hover:text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                                            Mua lại
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                `;
                            }
                            else {
                                productHTML = `
                                <div class="flex flex-col md:flex-row bg-white border rounded-lg p-4 md:h-46">
                                    <div class="flex-1">
                                        <div class="flex">
                                            <div class="w-32">
                                                <img src="/${product.anh[0]}" alt="product">
                                            </div>
                                            <div class="flex-1">
                                                <div class="h-full w-full">
                                                    <div class="flex flex-col justify-between md:flex-1 md:px-4">
                                                        <div class="flex">
                                                            <div>
                                                                <p class="text-gray-700 font-medium">Tên sản phẩm: ${product.ten}</p>
                                                                <p class="text-gray-700 font-semibold">Số lượng: ${product.so_luong}</p>
                                                                <p class="text-gray-700 font-semibold">Giá: ${product.gia_sau_giam_gia.toLocaleString('vi-VN')} đ</p>
                                                            </div>
                                                        </div>
                                                        <div class="flex flex-col md:justify-between items-start md:items-end mt-2 md:mt-0 md:pt-8">
                                                            <div class="flex justify-end items-center">
                                                                    <div class="flex items-center space-x-2">
                                                                        <button
                                                                            onclick="openRating(${product.id})"
                                                                            class="flex justify-center py-2 px-4 border text-sm font-medium rounded-md text-black hover:text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                                            Đánh giá
                                                                        </button>
                                                                        <button
                                                                            onclick="addToCart(${product.id})"
                                                                            class="flex justify-center py-2 px-4 border text-sm font-medium rounded-md text-black hover:text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                                            Mua lại
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                `;
                            }
                            productsContainer.insertAdjacentHTML('beforeend', productHTML);
                        });

                        document.getElementById('payment').innerText = info.thong_tin_thanh_toan.phuong_thuc;

                        document.getElementById('subtotal').innerText = `${Math.round(info.thong_tin_thanh_toan.tong_tien).toLocaleString('vi-VN')} ₫`;
                        document.getElementById('discount').innerText = `${info.thong_tin_thanh_toan.so_tien_da_giam.toLocaleString('vi-VN')} ₫`;
                        document.getElementById('total-amount').innerText = `${Math.round(info.thong_tin_thanh_toan.tong_tien_phai_tra).toLocaleString('vi-VN')}₫`;

                        document.getElementById('customer-name').value = info.ten_khach_hang;
                        document.getElementById('customer-phone').value = info.so_dien_thoai;
                        document.getElementById('customer-address').value = info.dia_chi_giao_hang;
                    } else {
                        console.error(data.message);
                        alert(data.message); 
                    }
                })
                .catch(error => {
                    console.error("Lỗi khi gọi API:", error);
                });
            } else {
                console.error("Không tìm thấy ID_DonHang trong URL");
            }
        });

        function addToCart(id) {
            const data = {
                id: id,
                quantity: "1"
            };
            fetch(`${window.location.origin}/api/user/cart`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success == true) {
                    notyf.success('Thêm vào sản phẩm vào giỏ hàng thành công!');

                    CountCart();
                } else {
                    notyf.error('Thêm vào sản phẩm vào giỏ hàng thất bại!');
                }
            })
            .catch(error => {
                notyf.error('Đã xảy ra lỗi khi thêm sản phẩm vào giỏ hàng!');
            });
        }

        let ID_SP = "";
        const ratingModal = document.getElementById('rating-modal');

        function openRating(id) {
            ratingModal.classList.remove('hidden');
            ID_SP = id;
        }
        
        function closeRating() {
            ratingModal.classList.add('hidden');
            ID_SP = "";
        }

        document.querySelectorAll('.star').forEach((star) => {
            star.addEventListener('click', function () {
                const rating = this.getAttribute('data-value');
                document.getElementById('rating-output').querySelector('span').textContent = rating;

                document.querySelectorAll('.star').forEach((el) => {
                    el.classList.remove('text-yellow-500');
                    el.classList.add('text-gray-400');
                });

                for (let i = 0; i < rating; i++) {
                    document.querySelectorAll('.star')[i].classList.add('text-yellow-500');
                    document.querySelectorAll('.star')[i].classList.remove('text-gray-400');
                }
            });
        });

        function saving() {
            const rating = parseInt(document.getElementById('rating-output').querySelector('span').textContent);
            const reviewText = document.getElementById('review-text').value.trim();

            if (isNaN(rating) || rating < 1 || rating > 5) {
                notyf.error("Vui lòng chọn số sao từ 1 đến 5.");
                return;
            }

            if (!reviewText || reviewText.length < 5) {
                notyf.error("Nội dung đánh giá phải có ít nhất 5 ký tự.");
                return;
            }

            const data = {
                ID_SP: ID_SP,
                SoSao: rating,
                NoiDung: reviewText
            };
            fetch(`${window.location.origin}/api/user/review`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            })
            .then(response => response.json())
            .then(data => {
                try {
                    if (data.success) {
                        notyf.success(data.message);
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        notyf.error('Đánh giá sản phẩm thất bại!');
                    }
                } catch (e) {
                    console.error("Parsing error:", e);
                    notyf.error("Phản hồi không hợp lệ từ server!");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                notyf.error('Đã xảy ra lỗi khi đánh giá sản phẩm!');
            });
        }
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>