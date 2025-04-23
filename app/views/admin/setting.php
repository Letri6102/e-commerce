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
    <link href="/public/css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/notyf.min.css">
    <title>Cấu hình hệ thống | ADMIN BKSTORE</title>
</head>

<body class="bg-gray-100">
    <div class="h-screen block md:flex">
        <?php $page = 10; include './partials/sidebar.php'; ?>
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
                            <div class="text-gray-500">Cấu hình hệ thống</div>
                        </nav>

                        <div class="pb-4 border rounded-lg bg-white shadow-sm">
                            <div class="p-4">
                                <div class="font-semibold text-lg pb-2">Cấu hình hệ thống</div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Trạng thái:</label>
                                        <select id="trangthai" class="mt-2 mb-4 w-full p-2 border rounded h-10">
                                            <option value="0">Đang hoạt động</option>
                                            <option value="1">Đang bảo trì</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Từ khoá trang web:</label>
                                        <input type="text" id="tukhoa" class="mt-2 mb-4 w-full p-2 border rounded" placeholder="Nhập từ khoá">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Client ID PayOS:</label>
                                        <input type="text" id="clientid" class="mt-2 mb-4 w-full p-2 border rounded" placeholder="Nhập Client ID PayOS">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">API Key PayOS:</label>
                                        <input type="text" id="apikey" class="mt-2 mb-4 w-full p-2 border rounded" placeholder="Nhập API Key PayOS">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Checksum Key PayOS:</label>
                                        <input type="text" id="checksum" class="mt-2 mb-4 w-full p-2 border rounded" placeholder="Checksum Key PayOS">
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <button onclick="saveconfig()" class="px-4 py-1 bg-green-500 text-white rounded-md">
                                        Lưu
                                    </button>
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
    <script src="/public/js/notyf.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", async () => {
            try {
                const response = await fetch("/api/system");
                if (!response.ok){
                    console.log("lỗi")
                    return
                }
                const data = await response.json();
                if(data[0] !== null){
                    document.getElementById("clientid").value = data[0].ClientID;
                    document.getElementById("tukhoa").value = data[0].TuKhoa;
                    document.getElementById("apikey").value = data[0].APIKey;
                    document.getElementById("checksum").value = data[0].Checksum;
                    document.getElementById("trangthai").value = data[0].TrangThaiBaoTri;
                    
                    
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
                console.log(error)
            }
        });

        var notyf = new Notyf({
            duration: 3000,
            position: {
            x: 'right',
            y: 'top',
            },
        });

        function saveconfig(){
            const clientId = document.getElementById("clientid").value;
            const tukhoa = document.getElementById("tukhoa").value;
            const apikey = document.getElementById("apikey").value;
            const checksum = document.getElementById("checksum").value;
            const trangthai = document.getElementById("trangthai").value;

            if(!clientId || !tukhoa || !apikey || !checksum || !trangthai){
                notyf.error("Vui lòng nhập đầy đủ thông tin!")
                return;
            }

            fetch('/api/system', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    MaHeThong: 1,
                    TuKhoa: tukhoa,
                    ClientID: clientId,
                    APIKey: apikey,
                    Checksum: checksum,
                    TrangThaiBaoTri: trangthai
                }),
            })
            .then(response => {
                if (!response.ok) {
                    notyf.error("Đã xảy ra lỗi khi cập nhật dữ liệu!")
                    return false;
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    notyf.success("Cập nhật dữ liệu hệ thống thành công!")
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    notyf.error("Cập nhật dữ liệu hệ thống thất bại!")
                }
            })
            .catch(error => {
                notyf.error("Đã xảy ra lỗi khi cập nhật dữ liệu!")
            });
        }
    </script>
</body>

</html>