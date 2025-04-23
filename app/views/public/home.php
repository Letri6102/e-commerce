<?php
error_reporting(0);
$cookies = http_build_query($_COOKIE, '', '; ');
$url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/api/user';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Cookie: $cookies"
]);
$response = curl_exec($ch);
$data = json_decode($response, true);
curl_close($ch);

function format_currency($number) {
    return number_format($number, 0, ',', '.') . 'đ';
}
?>

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
    <title>Trang chủ | Trọng Phúc Container</title>
</head>

<body class="bg-gray-100">
    <div class="h-screen">
        <header id="header-content" class="sticky top-0 z-50">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/header.php'; ?>
        </header>
        <div class="overflow-y-auto">
            <main class="container max-w-screen-1200 mx-auto min-h-screen pt-16 pb-20 lg:pb-10 px-1 lg:px-0">
                <div class="flex gap-2 pt-4">
                    <div class="w-1/5 bg-white hidden lg:block flex flex-col rounded-xl shadow-lg p-2 pt-3 text-sm">
                        <a
                            class="flex justify-between items-center hover:bg-gray-100 cursor-pointer p-1 rounded-sm mb-1" href="/">
                            <div class="flex justify-between items-center">
                                <img src="/public/image/home.png" alt="trangchu" class="w-6 h-6 mr-2">
                                <div>
                                    Trang chủ
                                </div>
                            </div>
                            <svg height="15" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                <path
                                    d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z">
                                </path>
                            </svg>
                        </a>
                        <a
                            class="flex justify-between items-center hover:bg-gray-100 cursor-pointer p-1 rounded-sm mb-1" href="/introduction">
                            <div class="flex justify-between items-center">
                                <img src="/public/image/intro.png" alt="intro" class="w-6 h-6 mr-2">
                                <div>
                                    Giới thiệu
                                </div>
                            </div>
                            <svg height="15" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                <path
                                    d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z">
                                </path>
                            </svg>
                        </a>
                        <a
                            class="flex justify-between items-center hover:bg-gray-100 cursor-pointer p-1 rounded-sm mb-1"  href="/like">
                            <div class="flex justify-between items-center">
                                <img src="/public/image/product.png" alt="product" class="w-6 h-6 mr-2">
                                <div>
                                    Sản phẩm yêu thích
                                </div>
                            </div>
                            <svg height="15" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                <path
                                    d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z">
                                </path>
                            </svg>
                        </a>
                        <a
                            class="flex justify-between items-center hover:bg-gray-100 cursor-pointer p-1 rounded-sm mb-1" href="/promotion">
                            <div class="flex justify-between items-center">
                                <img src="/public/image/sale.png" alt="sale" class="w-6 h-6 mr-2">
                                <div>
                                    Khuyến mãi
                                </div>
                            </div>
                            <svg height="15" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                <path
                                    d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z">
                                </path>
                            </svg>
                        </a>
                        <a
                            class="flex justify-between items-center hover:bg-gray-100 cursor-pointer p-1 rounded-sm mb-1" href="/news">
                            <div class="flex justify-between items-center">
                                <img src="/public/image/news.png" alt="news" class="w-6 h-6 mr-2">
                                <div>
                                    Tin tức
                                </div>
                            </div>
                            <svg height="15" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                <path
                                    d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z">
                                </path>
                            </svg>
                        </a>
                        <a
                            class="flex justify-between items-center hover:bg-gray-100 cursor-pointer p-1 rounded-sm mb-1" href="/suggest">
                            <div class="flex justify-between items-center">
                                <img src="/public/image/icons8-pull-request-100.png" alt="phone" class="w-6 h-6 mr-2">
                                <div>
                                    Sách đã góp ý
                                </div>
                            </div>
                            <svg height="15" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                <path
                                    d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z">
                                </path>
                            </svg>
                        </a>
                        <a
                            class="flex justify-between items-center hover:bg-gray-100 cursor-pointer p-1 rounded-sm mb-1" href="/comment">
                            <div class="flex justify-between items-center">
                                <img src="/public/image/comment.png" alt="protect" class="w-6 h-6 mr-2">
                                <div>
                                    Góp ý bán sách
                                </div>
                            </div>
                            <svg height="15" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                <path
                                    d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z">
                                </path>
                            </svg>
                        </a>
                    </div>
                    <div class="w-full lg:w-3/5 rounded-lg">
                        <div class="swiper-container rounded-lg">
                            <div class="swiper-wrapper banner-product">
                                <?php
                                $banners = $data['danh_sach_banner'];
                                foreach ($banners as $banner) {
                                    $image = $banner['Image'];
                                    $id = $banner['IdSP'];
                                    ?>
                                    <div class="swiper-slide bg-white flex items-center justify-center text-white rounded-lg overflow-hidden cursor-pointer"
                                        onclick="redirectToPage(<?= $id ?>)">
                                        <img src="<?= $image ?>" alt="Banner <?= $id ?>">
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                    <div class="w-1/5 hidden lg:block ">
                        <?php
                            $banners = $data['danh_sach_banner'];
                            $num = 0;
                            foreach ($banners as $banner) {
                                if($num == 2) break;
                                $image = $banner['Image'];
                                $id = $banner['IdSP'];
                                $num++;
                        ?>
                            <div class="rounded-lg overflow-hidden mb-4 cursor-pointer" onclick="redirectToPage(<?= $id ?>)">
                                <img src="<?= $image ?>" alt="SubBanner <?= $id ?>">
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="w-full bg-blue-300 mt-4 rounded-lg p-4">
                    <?php
                    function renderStars($sao)
                    {
                        $maxStars = 5;
                        $output = '';
                        for ($i = 1; $i <= $maxStars; $i++) {
                            if ($i <= $sao) {
                                $output .= '<span class="text-yellow-500 text-xl">★</span>';
                            } else {
                                $output .= '<span class="text-gray-300 text-xl">☆</span>';
                            }
                        }
                        return $output;
                    }
                    ?>
                    <div class="text-xl font-bold text-gray-700 mb-4">
                        MỚI THÊM GẦN ĐÂY
                    </div>
                    <div class="swiper-container-product overflow-hidden">
                        <div class="swiper-wrapper product-add-recent">
                            <?php
                                $moithem = $data['danh_sach_san_pham'];
                                foreach ($moithem as $item) {
                                    $id = $item['id'];
                                    $ten = $item['ten'];
                                    $hinh = $item['hinh'][0];
                                    $gia_goc = $item['gia_goc'];
                                    $gia_sau_giam_gia = $item['gia_sau_giam_gia'];
                                    $so_sao_trung_binh = $item['so_sao_trung_binh'];
                                    $thich = $item['thich'] ?? false;
                            ?>
                                <div class="swiper-slide-product overflow-hidden">
                                    <div class="bg-white p-2 rounded-lg shadow-lg w-full">
                                        <div class="cursor-pointer" onclick="redirectToPage(<?= $id ?>)">
                                            <div class="h-44 flex justify-center">
                                                <img src="/<?= $hinh ?>" alt="Product Image"
                                                    class="object-cover h-full rounded-md">
                                            </div>
                                            <div class="pt-4 pb-4 text-sm">
                                                <div class="font-semibold mt-2 h-16 text-black-700"><?= $ten ?></div>
                                                <div class="flex gap-2 items-center">
                                                    <p class="text-custom-blue font-bold text-base"><?= format_currency($gia_sau_giam_gia) ?></p>
                                                    <p class="text-gray-500 text-sm"><del><?= format_currency($gia_goc) ?></del></p>                                             
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center">
                                                <?php echo renderStars($so_sao_trung_binh); ?>
                                            </div>
                                            <?php if (isset($_SESSION['email']) && $_SESSION['email'] != '') { ?>
                                                <button class="heart-button focus:outline-none" data-product-id="<?= $id ?>">
                                                    <svg class="heart-icon w-6 h-6 text-red-500 transition duration-300 ease-in-out  <?= $thich ? 'isheart' : '' ?>"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 21c-4.35-3.2-8-5.7-8-9.5 0-2.5 2-4.5 4.5-4.5 1.74 0 3.41 1 4.5 2.54 1.09-1.54 2.76-2.54 4.5-2.54 2.5 0 4.5 2 4.5 4.5 0 3.8-3.65 6.3-8 9.5z" />
                                                    </svg>
                                                </button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="w-full pt-4 mt-4 p-1 lg:p-0">
                    <?php
                        $sanpham = $data['danh_sach_phan_loai'];
                        foreach ($sanpham as $item) {
                            $the_loai = $item['the_loai'];
                            $san_pham = $item['san_pham'];
                    ?>                
                    <div class="text-xl font-bold text-gray-700 mb-4 uppercase">
                        <?php echo $the_loai; ?>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 pb-6">
                        <?php
                            $dem  = 0;
                            foreach ($san_pham as $book) {
                                $dem++;
                                if($dem == 11) break;
                                $id = $book['id'];
                                $ten = $book['ten'];
                                $hinh = $book['hinh'][0];
                                $gia_goc = $book['gia_goc'];
                                $gia_sau_giam_gia = $book['gia_sau_giam_gia'];
                                $so_sao_trung_binh = $book['so_sao_trung_binh'];
                                $thich = $book['thich'] ?? false;
                        ?>
                            <div class="bg-white p-2 rounded-lg shadow-lg w-full">
                                <div class="cursor-pointer" onclick="redirectToPage(<?= $id ?>)">
                                    <div class="h-44 flex justify-center">
                                        <img src="/<?= $hinh ?>" alt="Product Image"
                                            class="object-cover h-full rounded-md">
                                    </div>
                                    <div class="pt-4 pb-4 text-sm">
                                        <div class="font-semibold mt-2 h-16 text-black-700"><?= $ten ?></div>
                                        <div class="flex gap-2 items-center">
                                            <p class="text-custom-blue font-bold text-base"><?= format_currency($gia_sau_giam_gia) ?></p>
                                            <p class="text-gray-500 text-sm"><del><?= format_currency($gia_goc) ?></del></p>                                             
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        <?php echo renderStars($so_sao_trung_binh); ?>
                                    </div>
                                    <?php if (isset($_SESSION['email']) && $_SESSION['email'] != '') { ?>
                                        <button class="heart-button focus:outline-none" data-product-id="<?= $id ?>">
                                            <svg class="heart-icon w-6 h-6 text-red-500 transition duration-300 ease-in-out  <?= $thich ? 'isheart' : '' ?>"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 21c-4.35-3.2-8-5.7-8-9.5 0-2.5 2-4.5 4.5-4.5 1.74 0 3.41 1 4.5 2.54 1.09-1.54 2.76-2.54 4.5-2.54 2.5 0 4.5 2 4.5 4.5 0 3.8-3.65 6.3-8 9.5z" />
                                            </svg>
                                        </button>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <a class="flex justify-center pb-6 cursor-pointer <?php if(count($san_pham) < 11) echo "hidden";?>" href="/category?keyword=<?=urlencode($the_loai)?>">
                        <div class="bg-white py-1 px-16 rounded-md font-bold text-custom-blue border-custom-blue">Xem thêm</div>
                    </a>
                    <?php } ?>
                </div>
            </main>
        </div>
        <?php $page = 1;
        include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/footer.php'; ?>
    </div>
    <script>
        function redirectToPage(id) {
            const targetUrl = `/product?id=${id}`;
            window.location.href = targetUrl;
        }
    </script>
    <script src="/public/js/notyf.min.js"></script>
    <script src="/public/js/heart.js"></script>
    <script src="/public/js/swiper-bundle.min.js"></script>
    <script src="/public/js/client.js"></script>
</body>

</html>