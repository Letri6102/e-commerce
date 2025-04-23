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

$idsp = $_GET['id'];
if($idsp == ''){
    header("Location: /404");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/public/image/logo.png" type="image/x-icon">
    <link href="/public/css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/client.css">
    <title>Chi tiết yêu cầu | BKSTORE</title>
</head>

<body class="bg-gray-100">
    <div class="h-screen">
        <header id="header-content" class="sticky top-0 z-50">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/header.php'; ?>
        </header>
        <div class="overflow-y-auto">
            <main class="container max-w-2xl mx-auto min-h-screen pt-16 pb-24 px-2 lg:px-0">
                <div class="pt-6 w-full">
                    <div class="relative flex justify-center items-center">
                        <button class="absolute left-0 material-icons" onclick="goBack()">
                            <img src="/public/image/arrow.png" alt="arrow" class="w-8 h-8">
                        </button>
                        <div class="font-bold text-lg pb-2">
                            Chi tiết yêu cầu
                        </div>
                    </div>
                    <hr>
                    <div class="h-full overflow-y-auto">
                        <div class="p-4 flex flex-col gap-2 bg-white rounded-lg mt-4">
                            <div class="flex gap-2">
                                <div class="font-bold">Ngày yêu cầu:</div>
                                <div class="flex-1" id="NgayYeuCau"></div>
                            </div>
                            <div class="flex gap-2">
                                <div class="font-bold">Trạng thái:</div>
                                <div class="flex-1" id="TrangThai"></div>
                            </div>
                            <div class="flex gap-2">
                                <div class="font-bold">Tên sản phẩm:</div>
                                <div class="flex-1" id="TenSP"></div>
                            </div>
                            <div class="flex gap-2">
                                <div class="font-bold">Nội dung:</div>
                                <div class="flex-1" id="NoiDung"></div>
                            </div>
                            <div class="flex gap-2">
                                <div class="font-bold">Phản hồi:</div>
                                <div class="flex-1" id="GhiChu"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <?php $page = 3;
        include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/footer.php'; ?>
    </div>
    <script src="/public/js/client.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
        async function fetchNotifications() {
            try {
                const response = await fetch('/api/product/proposeinfo', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        MaDeXuat: <?php echo $idsp; ?>,
                    }),
                });

                const data = await response.json();

                if (data.success) {
                    document.getElementById("NgayYeuCau").textContent = data.chi_tiet_de_xuat.NgayYeuCau;
                    document.getElementById("TrangThai").textContent = data.chi_tiet_de_xuat.TrangThai;
                    document.getElementById("TenSP").textContent = data.chi_tiet_de_xuat.TenSP;
                    document.getElementById("NoiDung").textContent = data.chi_tiet_de_xuat.NoiDung;
                    document.getElementById("GhiChu").textContent = data.chi_tiet_de_xuat.GhiChu;        
                } else {
                    console.error("Không thể tải dữ liệu");
                    window.location.href = '/404'
                }
            } catch (error) {
                console.error("Lỗi khi gọi API:", error);
            }
        }

        fetchNotifications()

    });
    </script>
</body>

</html>