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
    <title>Yêu cầu bán sách | ADMIN BKSTORE</title>
</head>

<body class="bg-gray-100">
    <div id="editRequestModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-40">
        <div class="bg-white p-6 rounded-lg w-11/12 lg:w-2/4 z-50 overflow-y-auto" style="max-height: 700px">
            <div class="flex justify-between items-start">
                <h2 class="text-xl font-bold mb-4">Yêu cầu bán sách</h2>
                <button onclick="closeRequestModal()">✕</button>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Mã yêu cầu:</label>
                    <input type="text" id="idRequest" class="mt-2 mb-4 w-full p-2 border rounded" disabled>
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Tên khách hàng:</label>
                    <input type="text" id="nameUser" class="mt-2 mb-4 w-full p-2 border rounded" disabled>
                </div>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Số điện thoại:</label>
                    <input id="phone" class="mt-2 mb-4 w-full p-2 border rounded" disabled>
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Địa chỉ email:</label>
                    <input id="email" class="mt-2 mb-4 w-full p-2 border rounded" disabled>
                </div>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Giới tính:</label>
                    <input id="sex" class="mt-2 mb-4 w-full p-2 border rounded" disabled>
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Ngày gửi:</label>
                    <input id="ngaygui" class="mt-2 mb-4 w-full p-2 border rounded" disabled>
                </div>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Tên sản phẩm:</label>
                    <input id="nameProduct" class="mt-2 mb-4 w-full p-2 border rounded h-10" disabled>
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Trạng thái:</label>
                    <select id="trangthai" class="mt-2 mb-4 w-full p-2 border rounded h-10" onchange="toggleInputNote()">
                        <option value="Đang chờ duyệt">Đang chờ duyệt</option>
                        <option value="Đã duyệt">Đã duyệt</option>
                        <option value="Đã từ chối">Đã từ chối</option>
                    </select>
                </div>
            </div>
            <div class="w-full">
                <label for="mota" class="block text-sm font-medium text-gray-700">Nội dung yêu cầu:</label>
                <textarea id="noidung" class="mt-2 mb-4 w-full p-2 border rounded" rows="3" disabled></textarea>
            </div>
            <div class="w-full hidden" id="noterequest">
                <label class="block text-sm font-medium text-gray-700">Ghi chú:</label>
                <input id="note" class="mt-2 mb-4 w-full p-2 border rounded h-10" placeholder="Nhập ghi chú cho khách hàng">
            </div>

            <div class="flex justify-end space-x-4">
                <button onclick="updateRequest()" class="bg-green-500 text-white px-4 py-2 rounded">Cập nhật</button>
                <button onclick="closeRequestModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Thoát</button>
            </div>
        </div>
    </div>


    <div class="h-screen block md:flex">
        <?php $page = 7; include './partials/sidebar.php'; ?>
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
                            <div class="text-gray-500">Quản lý yêu cầu</div>
                        </nav>

                        <script>
                            const columnTitles = {
                                id: 'ID',
                                name: 'Tên khách hàng',
                                email: 'Địa chỉ email',
                                date: 'Ngày gửi',
                                status: 'Trạng thái',
                                action: "Hành động"
                            };
                            let data = [];

                            async function getListRequest() {
                                const response = await fetch(`${window.location.origin}/api/admin/propose`, {
                                    method: 'GET',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    }
                                });

                                if (response.ok) {
                                    const ListRequest = await response.json();
                                    ListRequest['message'].forEach(request => {
                                        data.push({
                                            id: request.MaDeXuat,
                                            name: request.ten,
                                            email: request.email,
                                            date: request.NgayYeuCau,
                                            phone: request.SDT,
                                            nameProduct: request.TenSP,
                                            content: request.NoiDung,
                                            sex: request.gioi_tinh,
                                            status: request.TrangThai,
                                            action: [
                                                { label: 'Cập nhật', class: 'bg-green-500 text-white', onclick: 'editOrder' },
                                            ]
                                        });

                                    });

                                    const event = new CustomEvent('dataReady', { detail: ListRequest });
                                    window.dispatchEvent(event);
                                } else {
                                    console.error("Lỗi khi lấy dữ liệu từ API:", response.status);
                                }
                            }

                            function editOrder(item) {
                                document.getElementById("editRequestModal").classList.remove("hidden");
                                const parseItem= JSON.parse(decodeURIComponent(item));
                                
                                document.getElementById("idRequest").value = parseItem.id;
                                document.getElementById("nameUser").value = parseItem.name;
                                document.getElementById("email").value = parseItem.email;
                                document.getElementById("phone").value = parseItem.phone;
                                document.getElementById("sex").value = parseItem.sex;
                                document.getElementById("ngaygui").value = parseItem.date;
                                document.getElementById("nameProduct").value = parseItem.nameProduct;
                                document.getElementById("trangthai").value = parseItem.status;
                                document.getElementById("noidung").value = parseItem.content;

                            }

                            function closeRequestModal() {
                                document.getElementById("editRequestModal").classList.add("hidden");
                            }

                            function toggleInputNote() {
                                const select = document.getElementById("trangthai");
                                const inputNote = document.getElementById("noterequest");
                                
                                if (select.value) {
                                    inputNote.classList.remove("hidden"); 
                                    inputNote.focus();
                                }
                            }

                            window.onload = async function() {
                                await getListRequest(); 
                            };
                        </script>
                        <?php
                            $title = "Yêu cầu bán sách";
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
        function updateRequest() {
            const MaDeXuat = document.getElementById("idRequest").value;
            const TrangThai = document.getElementById("trangthai").value;
            const GhiChu = document.getElementById("note").value;

            const payload = {
                MaDeXuat: MaDeXuat,
                TrangThai: TrangThai,
                GhiChu: GhiChu
            };
            fetch(`${window.location.origin}/api/admin/propose`, {
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