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
    <title>Quản lý người dùng | ADMIN BKSTORE</title>
</head>

<body class="bg-gray-100">
    <div id="editUserModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-40">
        <div class="bg-white p-6 rounded-lg w-11/12 lg:w-2/4 z-50">
            <div class="flex justify-between items-start">
                <h2 class="text-xl font-bold mb-4">Cập nhật người dùng</h2>
                <button onclick="closeModal()">✕</button>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Mã người dùng:</label>
                    <input type="text" id="idUser" class="mt-2 mb-4 w-full p-2 border rounded" disabled>
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Tên người dùng:</label>
                    <input type="text" id="nameUser" class="mt-2 mb-4 w-full p-2 border rounded">
                </div>
            </div>

            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Địa chỉ email:</label>
                    <input type="text" id="email" class="mt-2 mb-4 w-full p-2 border rounded">
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                    <input type="text" id="phone" class="mt-2 mb-4 w-full p-2 border rounded">
                </div>
            </div>

            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Giới tính:</label>
                    <select id="sex" class="mt-2 mb-4 w-full p-2 border rounded h-10">
                        <option value="Male">Nam</option>
                        <option value="Female">Nữ</option>
                        <option value="Other">Khác</option>
                    </select>
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Trạng thái:</label>
                    <select id="trangthai" class="mt-2 mb-4 w-full p-2 border rounded h-10">
                        <option value="Đang hoạt động">Đang hoạt động</option>
                        <option value="Bị cấm">Bị cấm</option>
                    </select>
                </div>
            </div>
            
            <div class="w-full">
                <label class="block text-sm font-medium text-gray-700">Địa chỉ:</label>
                <input type="text" id="address" class="mt-2 mb-4 w-full p-2 border rounded">
            </div>

            <div class="flex justify-end space-x-4 pt-6">
                <button onclick="updateUser()" class="bg-green-500 text-white px-4 py-2 rounded">Cập nhật</button>
                <button onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Thoát</button>
            </div>
        </div>
    </div>
    <div class="h-screen block md:flex">
        <?php $page = 2; include './partials/sidebar.php'; ?>
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
                            <div class="text-gray-500">Quản lý người dùng</div>
                        </nav>
                        <script>
                            const columnTitles = {
                                id: 'ID',
                                name: 'Họ và tên',
                                email: 'Email',
                                phone: "Số điện thoại",
                                status: "Trạng thái",
                                action: "Hành động"
                            };

                            let data = [];
                            
                            function editUser(item) {
                                document.getElementById("editUserModal").classList.remove("hidden");
                                const parseItem = JSON.parse(decodeURIComponent(item));
                                
                                document.getElementById("idUser").value = parseItem.id;
                                document.getElementById("nameUser").value = parseItem.name;
                                document.getElementById("email").value = parseItem.email;
                                document.getElementById("phone").value = parseItem.phone;
                                document.getElementById("address").value = parseItem.address;
                                document.getElementById("trangthai").value = parseItem.status;
                                document.getElementById("sex").value = parseItem.sex;
                            }

                            function closeModal() {
                                document.getElementById("editUserModal").classList.add("hidden");
                            }

                            async function getUsers() {
                                const response = await fetch(`${window.location.origin}/api/admin/user`, {
                                    method: 'GET',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    }
                                });

                                if (response.ok) {
                                    const dataUser = await response.json();
                                    dataUser['user-list'].forEach(user => {
                                        data.push({
                                            id: user.uid,
                                            name: user.name,
                                            email: user.email,
                                            phone: user.phone,
                                            address: user.address,
                                            status: user.status,    
                                            sex: user.sex,
                                            action: [
                                                { label: 'Cập nhật', class: 'bg-green-500 text-white', onclick: 'editUser' },
                                            ]
                                        });
                                    });

                                    const event = new CustomEvent('dataReady', { detail: dataUser });
                                    window.dispatchEvent(event);
                                } else {
                                    console.error("Lỗi khi lấy dữ liệu từ API:", response.status);
                                }
                            }

                            window.onload = async function() {
                                await getUsers(); 
                            };

                        </script>
                        <?php
                            $title = "Quản lý người dùng";
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
        function updateUser() {
            const uid = Number(document.getElementById("idUser").value.trim());
            const name = document.getElementById("nameUser").value.trim();
            const email = document.getElementById("email").value.trim();
            const phone = document.getElementById("phone").value.trim();
            const sex = document.getElementById("sex").value.trim();
            const status = document.getElementById("trangthai").value.trim();
            const address = document.getElementById("address").value.trim();

            if (!/^\d+$/.test(uid)) {
                return notyf.error("UID không hợp lệ.");
            }
            if (!/^[a-zA-Z\s]+$/.test(name) || name.length > 50) {
                return notyf.error("Tên không hợp lệ hoặc quá dài.");
            }
            if (!/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(email)) {
                return notyf.error("Email không hợp lệ.");
            }
            if (!/^\d{9,15}$/.test(phone)) {
                return notyf.error("Số điện thoại không hợp lệ.");
            }
            if (!['Male', 'Female', 'Other'].includes(sex)) {
                return notyf.error("Giới tính không hợp lệ.");
            }
            if (!['Đang hoạt động', 'Bị cấm'].includes(status)) {
                return notyf.error("Trạng thái không hợp lệ.");
            }
            if (address.length > 200) {
                return notyf.error("Địa chỉ quá dài.");
            }

            const payload = {
                UID: uid, 
                name: name,
                email: email,
                phone: phone,
                sex: sex,
                status: status,
                address: address
            };
            fetch(`${window.location.origin}/api/admin/updateUser`, {
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
                // Xử lý lỗi kết nối hoặc lỗi khác
                console.error("Error:", error);
                notyf.error("Không thể kết nối đến server. Vui lòng thử lại sau.");
            });
        }

    </script>
</body>

</html>