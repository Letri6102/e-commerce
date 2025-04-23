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
    <title>Góp Ý | BKSTORE</title>
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
                        <div class="swiper-container rounded-lg space-y-4">
                            <div class="relative flex justify-center items-center">
                                <button class="absolute left-0 material-icons" onclick="goBack()">
                                    <img src="/public/image/arrow.png" alt="arrow" class="w-8 h-8">
                                </button>
                                <div class="font-bold text-lg pb-2">
                                    Góp ý bán sách
                                </div>
                            </div>
                            <hr>
                            <div class="content space-y-4">
                                <div class="flex flex-col justify-center items-center text-center">
                                    <h3 class="text-xl font-semibold text-blue-600">BKSTORE - Luôn Lắng Nghe Bạn</h3>
                                    <p class="text-sm text-gray-500">
                                        Nếu bạn có bất kỳ góp ý hoặc ý kiến nào, vui lòng chia sẻ với chúng tôi.<br> Mỗi ý kiến của bạn đều rất quý giá và giúp chúng tôi cải thiện dịch vụ.
                                    </p>
                                </div>
                            </div>
                            <form class="bg-white shadow-lg rounded-lg p-8 max-w-lg mx-auto mt-5" id="comment">
                                
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-medium mb-2" for="name">Tên sản phẩm</label>
                                    <input type="text" id="name" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="Nhập tên sản phẩm bạn muốn góp ý">
                                </div>

                                <div class="mb-4">
                                    <label class="block text-gray-700 font-medium mb-2" for="feedback">Nội Dung Góp Ý</label>
                                    <textarea id="feedback" rows="5" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="Nhập góp ý của bạn tại đây"></textarea>
                                </div>
                                
                                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition duration-300">Gửi góp ý</button>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <?php $page = 1;
        include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/footer.php'; ?>
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
        document.getElementById('comment').addEventListener('submit', async function(event) {
            event.preventDefault();

            const TenSP = document.getElementById('name').value.trim();
            const NoiDung = document.getElementById('feedback').value.trim();

            if (!TenSP || !NoiDung) {
                notyf.error('Vui lòng nhập đầy đủ thông tin!');
                return;
            }

            try {
                const response = await fetch(`${window.location.origin}/api/product/propose`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ TenSP, NoiDung })
                });
                
                const result = await response.json();
                console.log(result)
                if (result.success) {
                    notyf.success(result.message);
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    notyf.error(result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                notyf.error(error);

            }
        });
        function goBack() {
            window.history.back();
        }
    </script>
</body>

</html>