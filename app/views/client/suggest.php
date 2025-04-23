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
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/public/image/logo.png" type="image/x-icon">
    <link href="/public/css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/client.css">
    <title>Yêu cầu bán sách | BKSTORE</title>
</head>

<body class="bg-gray-100">
    <div class="h-screen">
        <header id="header-content" class="sticky top-0 z-50">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/header.php'; ?>
        </header>
        <div class="overflow-y-auto">
            <main class="container max-w-3xl mx-auto min-h-screen pt-16 pb-24 px-2 lg:px-0">
                <div class="pt-6 w-full">
                    <div class="relative flex justify-center items-center">
                        <button class="absolute left-0 material-icons" onclick="goBack()">
                            <img src="/public/image/arrow.png" alt="arrow" class="w-8 h-8">
                        </button>
                        <div class="font-bold text-lg pb-2">
                            Yêu cầu bán sách
                        </div>
                    </div>
                    <hr>
                    
                        <div class="container mx-auto min-h-screen px-4 py-4">
                            <script>
                                const columnTitles = {
                                    MaDeXuat: 'Mã đề xuất',
                                    TenSP: 'Tên sản phẩm',
                                    NgayYeuCau: 'Ngày yêu cầu',
                                    TrangThai: 'Trạng thái',
                                    action: 'Hành động'
                                };

                                let data = []; 
                                
                                function view(item){
                                    const parseItem= JSON.parse(decodeURIComponent(item));
                                    
                                    window.location.href = `/suggest/detail?id=${parseItem.MaDeXuat}`
                                }

                                async function getData() {
                                    const response = await fetch(`${window.location.origin}/api/product/getpropose`, {
                                        method: 'GET',
                                        headers: {
                                            'Content-Type': 'application/json'
                                        }
                                    });

                                    if (response.ok) {
                                        const dataContact = await response.json();
                                        console.log(dataContact); 
                                        dataContact.danh_sach_de_xuat.forEach(contact => {
                                            data.push({
                                                MaDeXuat: contact.MaDeXuat,
                                                TenSP: contact.TenSP,
                                                NoiDung: contact.NoiDung,
                                                TrangThai:contact.TrangThai,
                                                NgayYeuCau: contact.NgayYeuCau,
                                                action: [
                                                    { label: 'Chi tiết', class: 'bg-green-500 text-white', onclick: 'view' },
                                                ]
                                            });
                                        });

                                        const event = new CustomEvent('dataReady', { detail: dataContact });
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
                                $title = "Danh sách yêu cầu";
                                include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/table.php';
                            ?>
                        </div>
                    
                </div>
            </main>
        </div>
        <?php $page = 3;
        include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/footer.php'; ?>
    </div>
    <script src="/public/js/client.js"></script>
</body>

</html>