<?php
require_once dirname(__DIR__, 3) . '/config/db.php';

if($TrangThaiBaoTri && $_SESSION['Role'] != 'Admin'){
    header("Location: /maintain");
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
    <link rel="stylesheet" href="/public/css/swiper-bundle.min.css">
    <link rel="stylesheet" href="/public/css/client.css">
    <link rel="stylesheet" href="/public/css/notyf.min.css">
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <title>Tin tức | BKSTORE</title>
</head>

<body class="bg-gray-100">
    <div class="h-screen">
        <header id="header-content" class="sticky top-0 z-50">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/header.php'; ?>
        </header>
        <div class="overflow-y-auto">
            <main class="container max-w-screen-1200 mx-auto min-h-screen pt-16 pb-20 lg:pb-10 px-1 lg:px-0">
                <div class="flex gap-2 pt-4">
                    <div class="w-full rounded-lg">              
                        <div class="rounded-lg bg-white ">
                            <div class="flex justify-start items-center">
                                <button class="left-0 material-icons" onclick="goBack()">
                                    <img src="/public/image/arrow.png" alt="arrow" class="w-8 h-8">
                                </button>
                            </div>
                            <hr>
                            <div class="content space-y-4" id="content-new"></div>
                            <div id="imageContainer" class="ml-2 space-y-4"></div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <?php $page = 5;
        include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/footer.php'; ?>
    </div>
    <script src="/public/js/notyf.min.js"></script>
    <script src="/public/js/heart.js"></script>
    <script src="/public/js/swiper-bundle.min.js"></script>
    <script src="/public/js/client.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const ID_New = urlParams.get('id');

            if (ID_New) {
                fetch(`${window.location.origin}/api/system/new`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ MaTinTuc: ID_New })
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data)
                    const contentNew = document.getElementById("content-new");

                    let metaDescription = document.querySelector('meta[name="description"]');
                    if (metaDescription) {
                        metaDescription.setAttribute('content', data.MoTa);
                    }

                    // Cập nhật meta keywords
                    let metaKeywords = document.querySelector('meta[name="keywords"]');
                    if (metaKeywords) {
                        metaKeywords.setAttribute('content', data.TuKhoa);
                    }

                    contentNew.innerHTML = `
                        <div class="flex flex-col justify-center items-center text-center">
                            <h3 class="text-xl font-semibold text-blue-600">${data.TieuDe}</h3>
                        </div>
                        <p class="ml-2 text-gray-400">${data.ThoiGianTao}</p>
                        <div class="ml-2 space-y-4">
                            <p class="text-gray-700">
                                ${data.NoiDung}
                            </p>
                        </div>
                    `;

                    const imageContainer = document.getElementById('imageContainer');

                    data.Anh.forEach(imageSrc => {
                        const imageDiv = document.createElement('div');
                        imageDiv.innerHTML = `
                            <img src="${imageSrc}" alt="Hình ảnh" class="p-2">
                        `;
                        imageContainer.appendChild(imageDiv);
                    });
                })
                .catch(error => {
                    console.error("Lỗi khi gọi API:", error);
                });
            } else {
                console.error("Không tìm thấy MaDonHang trong URL");
            }
        });

    </script>
</body>

</html> 