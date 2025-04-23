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
    <link rel="stylesheet" href="/public/css/admin.css">
    <link rel="stylesheet" href="/public/css/notyf.min.css">
    <link href="/public/css/tailwind.min.css" rel="stylesheet">
    <title>Chương trình khuyến mãi | ADMIN BKSTORE</title>
</head>

<body class="bg-gray-100">
    <div id="editPromotionModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-40">
        <div class="bg-white p-6 rounded-lg w-11/12 lg:w-2/4 z-50 overflow-y-auto" style="max-height: 700px">
            <div class="flex justify-between items-start">
                <h2 class="text-xl font-bold mb-4">Cập nhật chương trình khuyến mãi</h2>
                <button onclick="closeModalPromotion()">✕</button>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">ID:</label>
                    <input type="text" id="id" class="mt-2 mb-4 w-full p-2 border rounded" disabled>
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Mã giảm giá:</label>
                    <input type="text" id="magiamgia" class="mt-2 mb-4 w-full p-2 border rounded">
                </div>
            </div>
        
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Tiền giảm:</label>
                    <input id="tiengiam" class="mt-2 mb-4 w-full p-2 border rounded">
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Điều kiện:</label>
                    <select id="dieukien" class="mt-2 mb-4 w-full p-2 border rounded">
                        <option value="Tất cả">Tất cả</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Chẵn">Chẵn</option>
                        <option value="Lẻ">Lẻ</option>
                        <option value="COD">COD</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Số lượng:</label>
                    <input id="soluong" class="mt-2 mb-4 w-full p-2 border rounded">
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Trạng thái:</label>
                    <select id="trangthai" class="mt-2 mb-4 w-full p-2 border rounded h-10">
                        <option value="Kích hoạt">Kích hoạt</option>
                        <option value="Hết hạn">Hết hạn</option>
                    </select>
                </div>
            </div>
        
            <div class="flex justify-end space-x-4 pt-6">
                <button onclick="updatePromotion()" class="bg-green-500 text-white px-4 py-2 rounded">Cập nhật</button>
                <button onclick="closeModalPromotion()" class="bg-gray-500 text-white px-4 py-2 rounded">Thoát</button>
            </div>
        </div>
    </div>

    <div id="addPromotionModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-40">
        <div class="bg-white p-6 rounded-lg w-11/12 lg:w-2/4 z-50 overflow-y-auto" style="max-height: 700px">
            <div class="flex justify-between items-start">
                <h2 class="text-xl font-bold mb-4">Thêm khuyến mãi</h2>
                <button onclick="closeModalAddPromotion()">✕</button>
            </div>

            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Mã giảm giá:</label>
                    <input type="text" id="themmagiamgia" class="mt-2 mb-4 w-full p-2 border rounded" placeholder="Nhập mã giảm giá">
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Số lượng:</label>
                    <input id="themsoluong" class="mt-2 mb-4 w-full p-2 border rounded" placeholder="Nhập số lượng">
                </div>
            </div>

            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Tiền giảm:</label>
                    <input id="themtiengiam" class="mt-2 mb-4 w-full p-2 border rounded" placeholder="Nhập tiền giảm">
                </div>
                <div class="w-1/2">
                <label class="block text-sm font-medium text-gray-700">Điều kiện:</label>
                <select id="themdieukien" class="mt-2 mb-4 w-full p-2 border rounded">
                    <option value="Tất cả">Tất cả</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Chẵn">Chẵn</option>
                    <option value="Lẻ">Lẻ</option>
                    <option value="COD">COD</option>
                </select>
            </div>
            </div>
            
            <div class="flex justify-end space-x-4 pt-6">
                <button onclick="addPromotion()" class="bg-green-500 text-white px-4 py-2 rounded">Thêm</button>
                <button onclick="closeModalAddPromotion()" class="bg-gray-500 text-white px-4 py-2 rounded">Thoát</button>
            </div>
        </div>
    </div>

    <div class="h-screen block md:flex">
        <?php $page = 8; include './partials/sidebar.php'; ?>
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
                            <div class="text-gray-500">Quản lý khuyến mãi</div>
                        </nav>
                        <div class="pb-4">
                            <button class="flex items-center bg-custom-background-bl text-white px-4 py-2 rounded focus:outline-none" onclick="addPromotionModal()">
                                <span class="mr-2">+</span> Thêm khuyến mãi
                            </button>
                        </div>

                        <script>
                            const columnTitles = {
                                id: 'ID',
                                ma: 'Mã giảm giá',
                                tiengiam: 'Tiền giảm',
                                dieukien: "Điều kiện",
                                soluong: 'Số lượng',
                                trangthai: 'Trạng thái',
                                action: "Hành động"
                            };

                            let data = [];

                            function editPromotion(item) {
                                document.getElementById("editPromotionModal").classList.remove("hidden");
                                const parseItem= JSON.parse(decodeURIComponent(item));

                                document.getElementById("id").value = parseItem.id;
                                document.getElementById("magiamgia").value = parseItem.ma;
                                document.getElementById("tiengiam").value = parseItem.tiengiam; 
                                document.getElementById("dieukien").value = parseItem.dieukien; 
                                document.getElementById("soluong").value = parseItem.soluong; 
                                document.getElementById("trangthai").value = parseItem.trangthai; 
                                
                                
                                
                            }

                            // function updatePromotion() {
                            //     const status = document.getElementById("userStatus").value;
                            //     alert(`User status updated to: ${status}`);
                            //     closeModal();
                            // }

                            function closeModalPromotion() {
                                document.getElementById("editPromotionModal").classList.add("hidden");
                            }

                            function addPromotionModal() {
                                document.getElementById("addPromotionModal").classList.remove("hidden");
                            }

                            function closeModalAddPromotion() {
                                document.getElementById("addPromotionModal").classList.add("hidden");
                            }

                            async function getData() {
                                const response = await fetch(`${window.location.origin}/api/admin/sale`, {
                                    method: 'GET',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    }
                                });

                                if (response.ok) {
                                    const dataSale = await response.json();
                                    dataSale['danh_sach_khuyen_mai'].forEach(sale => {
                                        data.push({
                                            id: sale.ID_GiamGia,
                                            ma: sale.Ma,
                                            tiengiam: Math.round(sale.TienGiam).toLocaleString('vi-VN'),
                                            dieukien: sale.DieuKien,
                                            soluong: sale.SoLuong,
                                            trangthai: sale.TrangThai,
                                            action: [
                                                { label: 'Cập nhật', class: 'bg-green-500 text-white', onclick: 'editPromotion' },
                                            ]
                                        });
                                    });

                                    const event = new CustomEvent('dataReady', { detail: dataSale });
                                    window.dispatchEvent(event);
                                } else {
                                    console.error("Lỗi khi lấy dữ liệu từ API:", response.status);
                                }
                            }

                            window.onload = async function() {
                                await getData(); 
                            };
                        </script>
                        <?php
                            $title = "Quản lý sản phẩm";
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

        function updatePromotion() {
            const id = Number(document.getElementById("id").value.trim());
            const maGiamGia = document.getElementById("magiamgia").value.trim();
            let tienGiam = document.getElementById("tiengiam").value.trim();
            const dieuKien = document.getElementById("dieukien").value.trim();
            const soLuong = Number(document.getElementById("soluong").value.trim());
            const trangThai = document.getElementById("trangthai").value.trim();

            
            if (!/^\d+$/.test(id)) {
                return notyf.error("ID không hợp lệ. Vui lòng kiểm tra lại.");
            }

            if (!maGiamGia || !/^[a-zA-Z0-9_-]+$/.test(maGiamGia)) {
                return notyf.error("Mã giảm giá không hợp lệ. Chỉ cho phép chữ, số, gạch ngang và gạch dưới.");
            }

            if (/^[\d.]+$/.test(tienGiam)) {
                tienGiam = parseInt(tienGiam.replace(/\./g, ''), 10);
                if (!/^\d+$/.test(tienGiam) || parseInt(tienGiam) < 0) {
                    return notyf.error("Tiền giảm phải là số nguyên dương.");
                }
            } else {
                return notyf.error("Tiền giảm phải là số nguyên dương.");
            }

            if (!['Male', 'Female', 'COD', 'Tất cả', 'Chẵn', 'lẻ'].includes(dieuKien)) {
                return notyf.error("Điều kiện không hợp lệ.");
            }

            if (!/^\d+$/.test(soLuong) || parseInt(soLuong) <= 0) {
                return notyf.error("Số lượng phải là số nguyên dương.");
            }

            const validTrangThai = ["Kích hoạt", "Hết hạn"];
            if (!validTrangThai.includes(trangThai)) {
                return notyf.error("Trạng thái không hợp lệ.");
            }

            const dataUpdate = {
                ID_GiamGia: id, 
                Ma: maGiamGia,
                TienGiam: tienGiam,
                DieuKien: dieuKien,
                SoLuong: soLuong,
                TrangThai: trangThai,
            };
            fetch(`${window.location.origin}/api/admin/updateSale`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(dataUpdate)
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

        function addPromotion() {
            const themMaGiamGia = document.getElementById("themmagiamgia").value.trim();
            let themTienGiam = document.getElementById("themtiengiam").value.trim();
            const themDieuKien = document.getElementById("themdieukien").value.trim();
            const themSoLuong = Number(document.getElementById("themsoluong").value.trim());

            if (!themMaGiamGia || !/^[a-zA-Z0-9_-]+$/.test(themMaGiamGia)) {
                return notyf.error("Mã giảm giá không hợp lệ. Chỉ cho phép chữ, số, gạch ngang và gạch dưới.");
            }

            if (/^[\d.]+$/.test(themTienGiam)) {
                themTienGiam = parseInt(themTienGiam.replace(/\./g, ''), 10);
                if (!/^\d+$/.test(themTienGiam) || parseInt(themTienGiam) <= 0) {
                    return notyf.error("Tiền giảm phải là số nguyên dương.");
                }
            } else {
                return notyf.error("Tiền giảm phải là số nguyên dương.");
            }

            if (!['Male', 'Female', 'COD', 'Tất cả', 'Chẵn', 'lẻ'].includes(themDieuKien)) {
                return notyf.error("Điều kiện không hợp lệ.");
            }

            if (!/^\d+$/.test(themSoLuong) || parseInt(themSoLuong) <= 0) {
                return notyf.error("Số lượng phải là số nguyên dương.");
            }

            const dataUpdate = {
                Ma: themMaGiamGia,
                TienGiam: themTienGiam,
                DieuKien: themDieuKien,
                SoLuong: themSoLuong,
            };
            fetch(`${window.location.origin}/api/admin/sale`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(dataUpdate)
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