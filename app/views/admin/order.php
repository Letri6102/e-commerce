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
    <link rel="stylesheet" href="/public/css/admin.css">
    <link rel="stylesheet" href="/public/css/notyf.min.css">
    <title>Quản đơn hàng | ADMIN BKSTORE</title>
</head>

<body class="bg-gray-100">
    <div id="editOrderModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-40">
        <div class="bg-white p-6 rounded-lg w-11/12 lg:w-2/4 z-50 overflow-y-auto" style="max-height: 700px">
            <div class="flex justify-between items-start">
                <h2 class="text-xl font-bold mb-4">Cập nhật đơn hàng</h2>
                <button onclick="closeModalOrder()">✕</button>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Mã đơn hàng:</label>
                    <input type="text" id="idOrder" class="mt-2 mb-4 w-full p-2 border rounded" disabled>
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Tên người mua:</label>
                    <input type="text" id="nameUser" class="mt-2 mb-4 w-full p-2 border rounded" disabled>
                </div>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Số điện thoại:</label>
                    <input id="phone" class="mt-2 mb-4 w-full p-2 border rounded" disabled>
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Địa chỉ giao hàng:</label>
                    <input id="diachigiaohang" class="mt-2 mb-4 w-full p-2 border rounded" disabled>
                </div>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Ngày đặt hàng:</label>
                    <input id="ngaydat" class="mt-2 mb-4 w-full p-2 border rounded" disabled>
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Số lượng sản phẩm:</label>
                    <input id="soluongsanpham" class="mt-2 mb-4 w-full p-2 border rounded" disabled>
                </div>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Tổng tiền:</label>
                    <input id="tongtien" class="mt-2 mb-4 w-full p-2 border rounded" disabled>
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Mã giảm giá:</label>
                    <input id="magiamgia" class="mt-2 mb-4 w-full p-2 border rounded" disabled>
                </div>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Trạng thái thanh toán:</label>
                    <select id="trangthaithanhtoan" class="mt-2 mb-4 w-full p-2 border rounded">
                        <option value="Chưa thanh toán">Chưa thanh toán</option>
                        <option value="Đã thanh toán">Đã thanh toán</option>
                        <option value="Huỷ thanh toán">Huỷ thanh toán</option>
                    </select>
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Trạng thái đơn hàng:</label>
                    <select id="trangthaidonhang" class="mt-2 mb-4 w-full p-2 border rounded">
                        <option value="Chờ xác nhận">Chờ xác nhận</option>
                        <option value="Đã xác nhận">Đã xác nhận</option>
                        <option value="Đang vận chuyển">Đang vận chuyển</option>
                        <option value="Đã giao hàng">Đã giao hàng</option>
                        <option value="Đã hủy">Đã hủy</option>
                    </select>
                </div>
            </div>

            <div class="w-full pb-6">
                <label for="hinhanh" class="block text-sm font-medium text-gray-700">Danh sách sản phẩm:</label>
                <div id="list-product" class="flex flex-wrap gap-4 mt-2">
                    <div class="w-full border border-gray-300 p-2 rounded-md flex gap-5">
                        <img src="/public/image/book1.webp" alt="Hình ảnh 1" class="w-16 h-16 object-cover rounded">
                        <div class="flex-1">
                            <div class="">
                                <div class="text-gray-600 font-bold">Dế mèn phiêu lưu ký</div>
                                <div class="flex justify-between pt-2">
                                    <div class="text-custom-blue font-bold text-sm">50.000đ</div>
                                    <div class="text-gray-600">x1</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end space-x-4">
                <button onclick="updateOrder()" class="bg-green-500 text-white px-4 py-2 rounded">Cập nhật</button>
                <button onclick="closeModalOrder()" class="bg-gray-500 text-white px-4 py-2 rounded">Thoát</button>
            </div>
        </div>
    </div>


    <div class="h-screen block md:flex">
        <?php $page = 4; include './partials/sidebar.php'; ?>
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
                            <a href="/admin" class="cursor-pointer hover:text-blue-500 focus:outline-none">Admin</a>
                            <div>&rsaquo;</div>
                            <div class="text-gray-500">Quản lý đơn hàng</div>
                        </nav>

                        <script>
                        const columnTitles = {
                            id: 'ID',
                            name: 'Tên khách hàng',
                            ngaydat: 'Ngày đặt hàng',
                            tongtien: "Tổng tiền",
                            trangthaithanhtoan: 'Trạng thái thanh toán',
                            trangthai: 'Trạng thái đơn hàng',
                            action: "Hành động"
                        };
                        let data = [];
                        let dataOrder = [];

                        async function getListOrder() {
                            const response = await fetch(`${window.location.origin}/api/order/list`, {
                                method: 'GET',
                                headers: {
                                    'Content-Type': 'application/json'
                                }
                            });

                            if (response.ok) {
                                const ListOrder = await response.json();
                                ListOrder['list'].forEach(order => {
                                    data.push({
                                        id: order.info.id,
                                        name: order.info.ten_khach_hang,
                                        ngaydat: order.info.ngay_dat,
                                        tongtien: Math.round(order.info.thong_tin_thanh_toan
                                            .tong_tien).toLocaleString('vi-VN'),
                                        trangthaithanhtoan: order.info.thong_tin_thanh_toan
                                            .trang_thai,
                                        trangthai: order.info.trang_thai,
                                        phone: order.info.so_dien_thoai,
                                        diachigiaohang: order.info.dia_chi_giao_hang,
                                        soluongsanpham: order.info.so_luong_san_pham,
                                        magiamgia: order.info.thong_tin_thanh_toan.ma_giam_gia,
                                        action: [{
                                            label: 'Cập nhật',
                                            class: 'bg-green-500 text-white',
                                            onclick: 'editOrder'
                                        }, ]
                                    });
                                    dataOrder.push({
                                        id: order.info.id,
                                        danh_sach_san_pham: order.info.danh_sach_san_pham
                                    });
                                });

                                const event = new CustomEvent('dataReady', {
                                    detail: ListOrder
                                });
                                window.dispatchEvent(event);
                            } else {
                                console.error("Lỗi khi lấy dữ liệu từ API:", response.status);
                            }
                        }

                        function editOrder(item) {
                            document.getElementById("editOrderModal").classList.remove("hidden");
                            const parseItem = JSON.parse(decodeURIComponent(item));

                            const thanhToan = document.getElementById("trangthaithanhtoan");
                            thanhToan.value = parseItem.trangthaithanhtoan;
                            const trangThai = document.getElementById("trangthaidonhang");
                            trangThai.value = parseItem.trangthai;
                            document.getElementById("idOrder").value = parseItem.id;
                            document.getElementById("nameUser").value = parseItem.name;
                            document.getElementById("phone").value = parseItem.phone;
                            document.getElementById("diachigiaohang").value = parseItem.diachigiaohang;
                            document.getElementById("soluongsanpham").value = parseItem.soluongsanpham;
                            document.getElementById("magiamgia").value = parseItem.magiamgia;
                            document.getElementById("tongtien").value = parseItem.tongtien;
                            document.getElementById("ngaydat").value = parseItem.ngaydat;

                            let currentValue = parseItem.trangthai;
                            let statusOrder = [
                                "Chờ xác nhận",
                                "Đã xác nhận",
                                "Đang vận chuyển",
                                "Đã giao hàng",
                                "Đã hủy"
                            ];
                            let currentIndex = statusOrder.indexOf(currentValue);
                            Array.from(trangThai.options).forEach((option, index) => {
                                option.disabled = index < currentIndex; 
                            });

                            if(currentIndex == 3) {
                                trangThai.options[4].classList.add("hidden");
                            }

                            currentValue = parseItem.trangthaithanhtoan;
                            statusOrder = [
                                "Chưa thanh toán",
                                "Đã thanh toán",
                                "Huỷ thanh toán"
                            ];
                            currentIndex = statusOrder.indexOf(currentValue);
                            Array.from(thanhToan.options).forEach((option, index) => {
                                option.disabled = index < currentIndex;
                            });
                            
                            if(currentIndex == 1) {
                                thanhToan.options[2].classList.add("hidden");
                            }

                            dataOrder.forEach(order => {
                                if (order.id == parseItem.id) {
                                    const containerProduct = document.getElementById("list-product");
                                    containerProduct.innerHTML = '';
                                    order.danh_sach_san_pham.forEach(product => {
                                        const productHTML = `
                                                <div class="w-full border border-gray-300 p-2 rounded-md flex gap-5">
                                                    <img src="/${product.anh[0]}" alt="${product.ten}" class="w-16 h-16 object-cover rounded">
                                                    <div class="flex-1">
                                                        <div class="">
                                                            <div class="text-gray-600 font-bold">${product.ten}</div>
                                                            <div class="flex justify-between pt-2">
                                                                <div class="text-custom-blue font-bold text-sm">${Math.round(product.gia_sau_giam_gia).toLocaleString('vi-VN')}</div>
                                                                <div class="text-gray-600">x${product.so_luong}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            `;
                                        containerProduct.insertAdjacentHTML('afterbegin', productHTML);
                                    });
                                }
                            });

                        }

                        function closeModalOrder() {
                            document.getElementById("editOrderModal").classList.add("hidden");
                            const thanhToan = document.getElementById("trangthaithanhtoan");
                            const trangThai = document.getElementById("trangthaidonhang");
                            trangThai.options[4].classList.remove("hidden");
                            thanhToan.options[2].classList.remove("hidden");
                        }

                        window.onload = async function() {
                            await getListOrder();
                        };
                        </script>
                        <?php
                            $title = "Quản lý đơn hàng";
                            include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/table.php';
                        ?>
                    </main>
                </div>
                <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/admin/partials/footer.php'; ?>
            </div>
        </div>
    </div>
    <script src="/public/js/sidebar.js"></script>
    <script src="/public/js/notyf.min.js"></script>
    <script>
    var notyf = new Notyf({
        duration: 3000,
        position: {
            x: 'right',
            y: 'top',
        },
    });

    function updateOrder() {
        const ID_DonHang = document.getElementById("idOrder").value;
        const thanhToan = document.getElementById("trangthaithanhtoan").value;
        const trangThai = document.getElementById("trangthaidonhang").value;

        let statusOrder = [
            "Chờ xác nhận",
            "Đã xác nhận",
            "Đang vận chuyển",
            "Đã giao hàng",
            "Đã hủy"
        ];
        if (!statusOrder.includes(trangThai)) {
            return notyf.error("Trạng thái không hợp lệ!");
        }
        statusOrder = [
            "Chưa thanh toán",
            "Đã thanh toán",
            "Huỷ thanh toán"
        ];
        if (!statusOrder.includes(thanhToan)) {
            return notyf.error("trạng thái thanh toán không hợp lệ!");
        }
        const payload = {
            ID_DonHang: ID_DonHang,
            ThanhToan: thanhToan,
            TrangThai: trangThai
        };
        fetch(`${window.location.origin}/api/order/update`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    notyf.success(data.message);
                    setTimeout(() => {
                        location.reload();
                    }, 2000);

                } else {
                    notyf.error(data.message);
                }
            })
            .catch(error => {
                console.error("Error:", error);
                notyf.error("Không thể kết nối đến server. Vui lòng thử lại sau.");
            });
    }
    </script>
</body>

</html>