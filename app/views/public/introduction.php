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
    <title>Giới thiệu | Container Trọng Phúc</title>
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
                        <div class=" rounded-lg bg-white ">
                            <div class="flex justify-start items-center">
                                <button class="left-0 material-icons" onclick="goBack()">
                                    <img src="/public/image/arrow.png" alt="arrow" class="w-8 h-8">
                                </button>
                            </div>
                            <hr>
                            <div class="title p-2">
                                <h1 class="text-2xl font-bold text-black-700">Về chúng tôi</h1>
                                <time class="text-sm text-gray-500">29/10/2024</time>
                            </div>
                            <hr>
                            <div class="content space-y-4">
                                <div class="flex flex-col justify-center items-center text-center">
                                    <h3 class="text-xl font-semibold text-blue-600">Container Trọng Phúc - HÀNH TRÌNH </h3>
                                    <p class="text-gray-600">Nhiều tri thức hơn, Nhiều thành công hơn</p>
                                </div>
                                <div class="ml-2 space-y-4">
                                    <p class="text-gray-700">Trọng Phúc ra đời vào thời điểm nhà và đất ở TPHCM là những thứ rất xa xỉ với phần lớn người trẻ ở Việt Nam. Trọng Phúc là kết quả của mong muốn được cống hiến, được mang lại giá trị cho người dân Việt Nam.
                                    
                                    </p>
                                    <p class="text-gray-700">
                                    
                                    </p>

                                    <p class="text-gray-700">
                                        Xuyến suốt hành trình từ lúc thành lập đến nay Container Trọng Phúc đã đặt được nhiều thành tựu. Hơn 150 dự án được khởi động thành công, nhưng Trọng Phúc tin đây chỉ là điểm khởi đầu nhỏ cho sự nghiệp kiến quốc.
                                        
                                    </p>
                                    <img src="/public/image/nhungthanhtuu.png" alt="thanhtuu">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <?php $page = 1;
        include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/footer.php'; ?>
    </div>
    <script src="/public/js/notyf.min.js"></script>
    <script src="/public/js/heart.js"></script>
    <script src="/public/js/swiper-bundle.min.js"></script>
    <script src="/public/js/client.js"></script>
</body>

</html>