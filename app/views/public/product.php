<?php
error_reporting(0);
$idsp = $_GET['id'];
if($idsp == ''){
    header("Location: 404");
    exit;
}

$cookies = http_build_query($_COOKIE, '', '; ');
$data = [
    'ID_SP' => $idsp
];
$jsonData = json_encode($data);
$url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/api/product/info';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Cookie: $cookies",
    "Content-Length: " . strlen($jsonData)
]);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
$response = curl_exec($ch);
$data = json_decode($response, true);
curl_close(handle: $ch);

function renderStars($sao)
{
    $maxStars = 5;
    $output = '';
    for ($i = 1; $i <= $maxStars; $i++) {
        if ($i <= $sao) {
            $output .= '<span class="text-yellow-500">★</span>';
        } else {
            $output .= '<span class="text-gray-300">☆</span>';
        }
    }
    return $output;
}

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
    <meta name="description" content="<?=$data['ten']?>">
    <meta name="keywords" content="<?=$data['thong_tin_chi_tiet']['tu_khoa']?>">
    <link rel="icon" href="/public/image/logo.png" type="image/x-icon">
    <link href="/public/css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/swiper-bundle.min.css">
    <link rel="stylesheet" href="/public/css/client.css">
    <link rel="stylesheet" href="/public/css/notyf.min.css">
    <title><?=$data['ten']?> | TPC COMPANY</title>
</head>

<body class="bg-gray-100">
    <div id="cmtModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
        <div class="bg-white p-6 rounded-lg w-3/4 lg:w-1/4 z-50 overflow-y-auto" style="max-height: 700px">
            <div class="flex justify-between items-start">
                <h2 class="text-xl font-bold mb-4">Chỉnh sửa bình luận</h2>
                <button onclick="closeModalCmt()">✕</button>
            </div>
            <div class="flex gap-2">
                <div class="w-full">
                    <label class="block text-sm font-medium text-gray-700">Trạng thái bình luận:</label>
                    <select id="trangthaicmt" class="mt-2 mb-4 w-full p-2 border rounded">
                        <option value="Đang hiện">Đang hiện</option>
                        <option value="Đang ẩn">Đang ẩn</option>
                    </select>
                </div>
            </div>
            <div id="idcmt"></div>
            <div class="w-full">
                <label for="phanhoicmt" class="block text-sm font-medium text-gray-700">Phản hồi:</label>
                <textarea id="phanhoicmt" class="mt-2 mb-4 w-full p-2 border rounded" rows="3" placeholder="Vui lòng nhập phản hồi"></textarea>
            </div>

            <div class="flex justify-end space-x-4">
                <button onclick="updateCmt()" class="bg-green-500 text-white px-4 py-2 rounded">Cập nhật</button>
                <button onclick="closeModalCmt()" class="bg-gray-500 text-white px-4 py-2 rounded">Thoát</button>
            </div>
        </div>
    </div>

    <div id="rvModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
        <div class="bg-white p-6 rounded-lg w-3/4 lg:w-1/4 z-50 overflow-y-auto" style="max-height: 700px">
            <div class="flex justify-between items-start">
                <h2 class="text-xl font-bold mb-4">Chỉnh sửa đánh giá</h2>
                <button onclick="closeModalRv()">✕</button>
            </div>
            <div class="flex gap-2">
                <div class="w-full">
                    <label class="block text-sm font-medium text-gray-700">Trạng thái đánh giá:</label>
                    <select id="trangthairv" class="mt-2 mb-4 w-full p-2 border rounded">
                        <option value="Đang hiện">Đang hiện</option>
                        <option value="Đang ẩn">Đang ẩn</option>
                    </select>
                </div>
            </div>
            <div id="idrv"></div>
            <div class="w-full">
                <label for="phanhoirv" class="block text-sm font-medium text-gray-700">Phản hồi:</label>
                <textarea id="phanhoirv" class="mt-2 mb-4 w-full p-2 border rounded" rows="3" placeholder="Vui lòng nhập phản hồi"></textarea>
            </div>

            <div class="flex justify-end space-x-4">
                <button onclick="updateRv()" class="bg-green-500 text-white px-4 py-2 rounded">Cập nhật</button>
                <button onclick="closeModalRv()" class="bg-gray-500 text-white px-4 py-2 rounded">Thoát</button>
            </div>
        </div>
    </div>


    <div class="h-screen">
        <header id="header-content" class="sticky top-0 z-40">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/header.php'; ?>
        </header>
        <div class="overflow-y-auto">
            <main class="container max-w-screen-1200 mx-auto min-h-screen pt-16 pb-20 lg:pb-10 px-1 lg:px-0">
                <div class="pt-6 pb-2 w-full gap-2 items-center hidden lg:flex">
                    <div class="text-lg text-black font-bold">
                        <?=$data['ten']?>
                    </div>
                    <div class="flex text-base items-center">
                        <?php echo renderStars($data['so_sao_trung_binh']); ?>
                    </div>
                    <div class="text-sm">
                        <?=count($data['danh_sach_danh_gia'])?> đánh giá
                    </div>
                </div>
                <hr>
                <div class="block gap-3 w-full pt-4 lg:flex">
                    <div class="w-full lg:w-2/5">
                    <div
                        class="swiper-container-product-detail bg-white p-2 overflow-hidden relative rounded-t-lg lg:rounded-lg"
                        style="height: 460px">
                        <div class="swiper-wrapper h-full">
                            <?php
                            $banners = $data['hinh'];
                            $save_img = $banners[0];
                            foreach ($banners as $banner) {
                            ?>
                                <div
                                    class="swiper-slide-product-detail bg-white flex items-center justify-center rounded-lg overflow-hidden">
                                    <img class="h-full object-contain" src="<?=$banner?>" alt="1">
                                </div>
                            <?php } ?>
                        </div>
                        <div
                            class="swiper-button-pr-prev absolute top-1/2 left-2 transform -translate-y-1/2 bg-gray-600 text-white p-2 rounded-full hover:bg-gray-700 z-10">
                            &#10094;
                        </div>
                        <div
                            class="swiper-button-pr-next absolute top-1/2 right-2 transform -translate-y-1/2 bg-gray-600 text-white p-2 rounded-full hover:bg-gray-700 z-10">
                            &#10095;
                        </div>
                    </div>
                    </div>
                    <?php
                        $save_id = $data['id'];
                        $save_name = $data['ten'];
                        $save_price = $data['gia_san_pham'];
                        $save_pricesale = $data['gia_sau_giam_gia'];
                    ?>
                    <div class="flex-1 h-110">
                        <div
                            class="w-full h-full bg-white p-3 pr-4 pl-4 flex flex-col justify-between rounded-b-lg lg:rounded-lg">
                            <div class="pt-6 pb-2 w-full gap-2 items-center block lg:hidden">
                                <div class="text-lg text-black font-bold">
                                    <?=$data['ten']?>
                                </div>
                                <div class="flex text-base items-center">
                                    <?php echo renderStars($data['so_sao_trung_binh']); ?>
                                </div>
                                <div class="text-sm">
                                    <?=count($data['danh_sach_danh_gia'])?> đánh giá
                                </div>
                                <div class="flex gap-2 items-center pt-4 pb-4">
                                    <div class="font-bold text-2xl text-custom-blue items-center">
                                        <?=format_currency($data['gia_sau_giam_gia'])?>
                                    </div>
                                    <div class="text-gray-600 items-center">
                                        <del>
                                            <?=format_currency($data['gia_san_pham'])?>
                                        </del>
                                    </div>
                                    <div class="bg-custom-background text-white text-sm font-bold p-1 rounded-lg">
                                        -<?=$data['ty_le_giam_gia']?>
                                    </div>
                                </div>
                            </div>
                            <div class="hidden lg:block">
                                <div class="text-base text-black font-bold">
                                    Thông tin chi tiết
                                </div>
                                <div class="pt-2 flex gap-5">
                                    <div class="text-sm w-1/2">
                                        <div class="flex gap-1">
                                            <div>Loại:</div>
                                            <div class="font-bold"><?=$data['thong_tin_chi_tiet']['the_loai']?></div>
                                        </div>
                                        <div class="flex gap-1 pt-2">
                                            <div>Dài - rộng - cao(bên trong):</div>
                                            <div class="font-bold"><?=$data['thong_tin_chi_tiet']['nha_xuat_ban']?></div>
                                        </div>
                                        <div class="flex gap-1 pt-2">
                                            <div>Dài - rộng - cao(bên ngoài):</div>
                                            <div class="font-bold"><?=$data['thong_tin_chi_tiet']['nam_xuat_ban']?></div>
                                        </div>
                                        <div class="flex gap-1 pt-2">
                                            <div>Cửa rộng cao:</div>
                                            <div class="font-bold"><?=$data['thong_tin_chi_tiet']['kich_thuoc']?></div>
                                        </div>
                                    </div>
                                    <div class="text-sm flex-1">
                                        <div class="flex gap-2">
                                            <div>Thể tích:</div>
                                            <div class="font-bold"><?=$data['thong_tin_chi_tiet']['tac_gia']?></div>
                                        </div>
                                        <div class="flex gap-1 pt-2">
                                            <div>Trọng lượng:</div>
                                            <div class="font-bold"><?=$data['thong_tin_chi_tiet']['hinh_thuc']?></div>
                                        </div>
                                        <div class="flex gap-1 pt-2">
                                            <div>Trọng tải:</div>
                                            <div class="font-bold"><?=$data['thong_tin_chi_tiet']['ngon_ngu']?></div>
                                        </div>
                                        <!-- <div class="flex gap-1 pt-2">
                                            <div>Số trang:</div>
                                            <div class="font-bold"><?=$data['thong_tin_chi_tiet']['so_trang']?></div>
                                        </div> -->
                                    </div>
                                </div>
                                <div class="flex gap-1 pt-1 items-center text-sm">
                                    <div class="flex text-lg items-center">
                                        <?php echo renderStars($data['so_sao_trung_binh']); ?>
                                    </div>
                                    <div class="flex gap-2">
                                        <div>
                                            <?=count($data['danh_sach_danh_gia'])?> đánh giá
                                        </div>
                                        <div>|</div>
                                        <div>Đã bán <?=$data['so_luong_da_ban']?></div>
                                    </div>
                                </div>
                                <div class="flex gap-2 items-center pt-4">
                                    <div class="font-bold text-2xl text-custom-blue items-center">
                                        <?=format_currency($data['gia_sau_giam_gia'])?>
                                    </div>
                                    <div class="text-gray-600 items-center">
                                        <del>
                                            <?=format_currency($data['gia_san_pham'])?>
                                        </del>
                                    </div>
                                    <div class="bg-custom-background text-white text-sm font-bold p-1 rounded-lg">
                                        -<?=$data['ty_le_giam_gia']?>
                                    </div>
                                </div>
                                <div class="text-base text-black font-bold pt-6">
                                    Chương trình ưu đãi
                                </div>
                            </div>
                            <div>
                                <?php
                                    if($data['so_luong_ton_kho'] > 0) {
                                ?>
                                    <div class="pb-6 gap-2 items-center hidden lg:flex">
                                        <div class="text-base items-center text-gray-600">
                                            Số lượng:
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <button
                                                class="bg-custom-background rounded-lg w-6 h-6 flex items-center justify-center text-white font-bold transition-transform duration-200 ease-in-out transform hover:scale-105 active:scale-95"
                                                id="decreaseBtn">
                                                -
                                            </button>
                                            <input type="number" id="quantity" value="1" min="1"
                                                class="text-center w-16 border border-gray-300 rounded-lg" readonly>
                                            <button
                                                class="bg-custom-background rounded-lg w-6 h-6 flex items-center justify-center text-white font-bold transition-transform duration-200 ease-in-out transform hover:scale-105 active:scale-95"
                                                id="increaseBtn">
                                                +
                                            </button>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="flex gap-2 h-14 items-center">
                                    <?php
                                        if($data['so_luong_ton_kho'] < 1){
                                    ?>
                                        <div
                                            class="flex-1 border-2 border-gray-400 text-gray-400 h-full flex flex-col lg:flex-row justify-center items-center rounded-lg font-bold lg:gap-2 cursor-pointer">
                                            Sản phẩm tạm hết hàng
                                        </div>
                                    <?php } else { ?> 
                                        <div onclick="BuyNow()"
                                            class="bg-custom-background rounded-lg h-full flex flex-col justify-center w-2/3 items-center font-bold text-white cursor-pointer active:shadow-lg">
                                            Mua ngay
                                        </div>
                                        <div id="add-to-cart"
                                            class="flex-1 border-2 border-custom-blue text-custom-blue h-full flex flex-col lg:flex-row justify-center items-center rounded-lg font-bold lg:gap-2 cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" class="text-custom-blue">
                                                <path d="M6 2H2v2h2l3.6 7.59L7.2 20H20v-2H8.4l1.6-3.2h8.8l1.2-6H6z"></path>
                                                <circle cx="8" cy="20" r="2"></circle>
                                                <circle cx="16" cy="20" r="2"></circle>
                                            </svg>
                                            <div>
                                                Thêm vào giỏ
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pt-4">
                    <hr>
                </div>
                <div class="pt-4">
                    <div class="w-full rounded-lg shadow-lg bg-white p-6">
                        <div class="text-base text-black font-bold">
                            Mô tả sản phẩm
                        </div>
                        <div class="pt-4">
                            <p class="pb-2"><strong><?=$data['ten']?></strong></p>
                            <p class="text-justify text-gray-700">
                                <?=$data['mo_ta']?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="pt-3">
                    <div class="w-full bg-blue-300 mt-4 rounded-lg p-4">
                        <div class="text-xl font-bold text-gray-700 mb-4">
                            GỢI Ý SẢN PHẨM
                        </div>
                        <div class="swiper-container-product overflow-hidden">
                            <div class="swiper-wrapper">
                                <?php
                                    $moithem = $data['danh_sach_san_pham'];
                                    foreach ($moithem as $item) {
                                        $id = $item['id'];
                                        $ten = $item['ten'];
                                        $hinh = $item['hinh'][0];
                                        $gia_goc = $item['gia_goc'];
                                        $gia_sau_giam_gia = $item['gia_sau_giam_gia'];
                                        $so_sao_trung_binh = $item['so_sao_trung_binh'];
                                        $thich = $item['thich'];
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
                                                    <?php echo renderStars(sao: $so_sao_trung_binh); ?>
                                                </div>
                                                <?php if($_SESSION['email']!=''){ ?>
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
                </div>
                <div class="pt-4 gap-3 flex flex-col lg:flex-row">
                    <div class="w-ful lg:w-4/6 flex flex-col gap-4">
                        <div class="w-full rounded-lg shadow-lg bg-white p-6">
                            <div class="text-base text-black font-bold">
                                Đánh giá sản phẩm
                            </div>
                            <div class="flex flex-col gap-2 pt-2" id="reviewsContainer">
                                <?php
                                    $danhgia = $data['danh_sach_danh_gia'];
                                    $i = 0;
                                    $totalReviews = count($danhgia); 
                                    $initialVisibleReviews = 5; 
                                    if($totalReviews < $initialVisibleReviews){
                                        $initialVisibleReviews = $totalReviews;
                                    }

                                    foreach ($danhgia as $item) {
                                        $id = $item['id'];
                                        $ngay_danh_gia = $item['ngay_danh_gia'];
                                        $so_sao = $item['so_sao'];
                                        $noi_dung = $item['noi_dung'];
                                        $avatar = $item['avatar'];
                                        $ten = $item['ten'];
                                        $phanhoi = !empty($item['phan_hoi']) ? str_replace(["\r", "\n"], '', $item['phan_hoi']) : "";
                                        $trang_thai = $item['trang_thai'];
                                        if ($avatar == null) {
                                            $avatar = "https://ui-avatars.com/api/?background=random&name=" . urlencode($ten);
                                        }
                                ?>
                                    <div data-id="rvcontainer<?=$id?>" class="comment-item pt-2 pb-2 <?php echo $i >= $initialVisibleReviews ? 'hidden' : ''; ?>" style="<?php if($trang_thai=="Đang ẩn") echo"opacity: 0.6;";?>">
                                        <div class="pt-2 pb-2">
                                            <div class="flex gap-2 text-gray-700">
                                                <img src="<?=$avatar?>"
                                                    alt="avt" class="w-10 h-10 rounded-full cursor-pointer ml-3">
                                                <div>
                                                    <div class="flex gap-2 items-center">
                                                        <div>
                                                            <div class="flex gap-2 items-center">
                                                                <div class="text-base font-bold"><?=$ten?></div>
                                                                <div class="text-xs"><?=$ngay_danh_gia?></div>
                                                            </div>
                                                        </div>
                                                        <?php if (isset($_SESSION["Role"]) && $_SESSION["Role"] === 'Admin') { ?>
                                                            <button onclick="OpenReview(<?=$id?>)">
                                                                <img src="/public/image/edit.png" alt="edit" class="w-4 h-4">
                                                            </button>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <?php echo renderStars($so_sao); ?>
                                                    </div>
                                                    <div class="trangthai-rv" data-id="ttrv<?=$id?>" data-value="<?php echo $trang_thai; ?>"></div>
                                                    <div class="text-sm pt-1 text-justify">
                                                        <?=$noi_dung?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div data-id="rv<?=$id?>">
                                                <?php if (isset($phanhoi) && $phanhoi != '') { ?>
                                                    <div class="flex gap-2 text-gray-700 pt-6 pl-10">
                                                        <img src="/public/image/human.png"
                                                            alt="avt" class="w-10 h-10 rounded-full cursor-pointer ml-3">
                                                        <div>
                                                            <div class="flex gap-2 items-center">
                                                                <div>
                                                                    <div class="flex gap-2 items-center">
                                                                        <div class="text-base font-bold">Quản Trị Viên</div>
                                                                        <div class="bg-custom-background text-xs text-white px-2 rounded-md">QTV</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="text-sm pt-1 text-justify">
                                                                <?=$phanhoi?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <?php 
                                        if ($i < max($totalReviews - 1, $initialVisibleReviews - 1)): 
                                        ?>
                                            <div class="pt-4"><hr></div> 
                                        <?php endif; ?>
                                    </div>
                                <?php $i++;} ?>
                                <?php if(count($danhgia) == 0){ ?>
                                    <div class="flex flex-col items-center justify-center gap-2 pt-2 ">
                                        <img src="/public/image/icons8-sad-100.png" alt="sad-icon" class="w-8 h-8">
                                        <div class="text-center text-gray-500">Chưa có đánh giá nào</div>
                                    </div>
                                <?php } ?>
                                <div class="flex justify-between pt-2">
                                    <button id="loadMoreBtn"
                                        class="rounded-lg shadow-lg w-1/3 border p-1 flex items-center justify-center mx-auto hover:bg-gray-100 transition duration-300">
                                        Xem thêm
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="w-full rounded-lg shadow-lg bg-white p-6">
                            <div class="text-base text-black font-bold">
                                Nhận xét và Bình luận
                            </div>
                            <div class="pt-4 <?php if($_SESSION["email"] == '' || $_SESSION["Role"] == 'Admin') echo "hidden"?>">
                                <div class="flex gap-2">
                                    <img src="<?=$_SESSION["Avatar"]?>" alt="avt"
                                        class="w-10 h-10 rounded-full cursor-pointer ml-3">
                                    <textarea id="content-cmt" class="rounded-lg h-20 bg-white w-full p-2 border resize-none text-sm"
                                        placeholder="Xin mời để lại nhận xét và bình luận."></textarea>
                                    <button
                                        class="flex items-center justify-center rounded-lg border border-gray-300 p-2 text-sm h-10 hover:bg-gray-100 transition duration-300" onclick="sendcmt()">
                                        <img src="/public/image/send.png" alt="send" class="h-6 w-6">
                                    </button>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2 pt-2" id="commentContainer">
                                <?php
                                    $danhgia = $data['danh_sach_binh_luan'];
                                    $i = 0;
                                    $totalReviews = count($danhgia);
                                    $initialVisibleReviews = 5;
                                    if($totalReviews < $initialVisibleReviews){
                                        $initialVisibleReviews = $totalReviews;
                                    }

                                    foreach ($danhgia as $item) {
                                        $id = $item['id'];
                                        $ngay_binh_luan = $item['ngay_binh_luan'];
                                        $noi_dung = $item['noi_dung'];
                                        $avatar = $item['avatar'];
                                        $ten = $item['ten'];
                                        $phanhoi = !empty($item['phan_hoi']) ? str_replace(["\r", "\n"], '', $item['phan_hoi']) : "";
                                        $trang_thai = $item['trang_thai'];
                                        if ($avatar == null) {
                                            $avatar = "https://ui-avatars.com/api/?background=random&name=" . urlencode($ten);
                                        }
                                ?>
                                    <div data-id="cmtcontainer<?=$id?>" class="comment-item pt-2 pb-2 <?php echo $i >= $initialVisibleReviews ? 'hidden' : ''; ?>" style="<?php if($trang_thai=="Đang ẩn") echo"opacity: 0.6;";?>">
                                        <div class="pt-2 pb-2">
                                            <div class="flex gap-2 text-gray-700">
                                                <img src="<?=$avatar?>"
                                                    alt="avt" class="w-10 h-10 rounded-full cursor-pointer ml-3">
                                                <div>
                                                    <div class="flex gap-2 items-center">
                                                        <div>
                                                            <div class="flex gap-2 items-center">
                                                                <div class="text-base font-bold"><?=$ten?></div>
                                                                <div class="text-xs"><?=$ngay_binh_luan?></div>
                                                            </div>
                                                        </div>
                                                        <?php if (isset($_SESSION["Role"]) && $_SESSION["Role"] === 'Admin') { ?>
                                                            <button onclick="OpenComment(<?=$id?>)">
                                                                <img src="/public/image/edit.png" alt="edit" class="w-4 h-4">
                                                            </button>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="trangthai-cmt" data-id="ttcmt<?=$id?>" data-value="<?php echo $trang_thai; ?>"></div>
                                                    <div class="text-sm pt-1 text-justify">
                                                        <?=$noi_dung?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div data-id="cmt<?=$id?>">
                                                <?php if (isset($phanhoi) && $phanhoi != '') { ?>
                                                    <div class="flex gap-2 text-gray-700 pt-6 pl-10">
                                                        <img src="/public/image/human.png"
                                                            alt="avt" class="w-10 h-10 rounded-full cursor-pointer ml-3">
                                                        <div>
                                                            <div class="flex gap-2 items-center">
                                                                <div>
                                                                    <div class="flex gap-2 items-center">
                                                                        <div class="text-base font-bold">Quản Trị Viên</div>
                                                                        <div class="bg-custom-background text-xs text-white px-2 rounded-md">QTV</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="text-sm pt-1 text-justify">
                                                                <?=$phanhoi?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <?php 
                                        if ($i < max($totalReviews - 1, $initialVisibleReviews - 1)): 
                                        ?>
                                            <div class="pt-4"><hr></div> 
                                        <?php endif; ?>
                                    </div>
                                <?php $i++;} ?>
                                <?php if(count($danhgia) == 0){ ?>
                                    <div class="flex flex-col items-center justify-center gap-2 pt-2 " id="NoCmt">
                                        <img src="/public/image/icons8-sad-100.png" alt="sad-icon" class="w-8 h-8">
                                        <div class="text-center text-gray-500">Chưa có bình luận nào</div>
                                    </div>
                                <?php } ?>
                                <div class="flex justify-between pt-2">
                                    <button id="loadMoreBtn2"
                                        class="rounded-lg shadow-lg w-1/3 border p-1 flex items-center justify-center mx-auto hover:bg-gray-100 transition duration-300">
                                        Xem thêm
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1">
                    <div class="w-full rounded-lg shadow-lg bg-white p-6">
                        <div class="text-base text-black font-bold">
                            Tin tức về sản phẩm
                        </div>
                        <div id="news-container" class="flex flex-col pt-4 pb-2 gap-4">
                            
                        </div>
                    </div>
                    </div>
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
        fetch('api/system/inforList')
            .then(response => response.json())
            .then(data => {
                const newsContainer = document.getElementById('news-container');
                newsContainer.innerHTML = data.danh_sach_tin_tuc.map(item => `
                    <div class="flex gap-4">
                        <img src="${item.AnhMinhHoa[0].LinkAnh}" alt="Tin tức" class="rounded-lg w-32">
                        <a href="/news/detail?id=${item.MaTinTuc}" class="text-sm text-gray-700 hover:underline">${item.TieuDe}</a>
                    </div>
                `).join('');
            })
            .catch(error => console.error('Có lỗi khi gọi API:', error));

        
        document.getElementById('add-to-cart').addEventListener('click', function() {
    
        let login = <?= json_encode($_SESSION['email']); ?>;
       
        if (!login) {
            notyf.error('Vui lòng đăng nhập để thêm vào giỏ hàng!');
            setTimeout(() => {
                window.location.href = 'auth/login';
            }, 2000);
            return
        }
        let productId = <?= json_encode($data['id']); ?>;
        let quantity = document.getElementById('quantity').value
        const data = {
            id: productId,
            quantity: quantity
        };
        fetch('api/user/cart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success == true) {
                notyf.success('Thêm vào sản phẩm vào giỏ hàng thành công!');

                CountCart();
            } else {
                notyf.error('Thêm vào sản phẩm vào giỏ hàng thất bại!');
            }
        })
        .catch(error => {
            notyf.error('Đã xảy ra lỗi khi thêm sản phẩm vào giỏ hàng!');
        });
    });
    </script>
    <script>
        function addCommentToTop(commentData) {
            const reviewsContainer = document.getElementById('commentContainer');

            const comments = reviewsContainer.getElementsByClassName('comment-item').length;
            

            const newComment = document.createElement('div');
            newComment.classList.add('comment-item', 'pt-2', 'pb-2');

            console.log(commentData.id)

            newComment.innerHTML = `
                <div class="pt-2 pb-2">
                    <div class="flex gap-2 text-gray-700">
                        <img src="${commentData.avatar}" 
                            alt="avt" class="w-10 h-10 rounded-full cursor-pointer ml-3">
                        <div>
                            <div class="flex gap-2 items-center">
                                <div>
                                    <div class="flex gap-2 items-center">
                                        <div class="text-base font-bold">${commentData.ten}</div>
                                        <div class="text-xs">${commentData.ngay_binh_luan}</div>
                                    </div>
                                </div>
                                <?php if (isset($_SESSION["Role"]) && $_SESSION["Role"] === 'Admin') { ?>
                                    <button onclick="OpenComment(${commentData.id})">
                                        <img src="/public/image/edit.png" alt="edit" class="w-4 h-4">
                                    </button>
                                <?php } ?>
                            </div>
                            <div class="trangthai-cmt" data-id="ttcmt${commentData.id}" data-value="Đang hiện"></div>
                            <div class="text-sm pt-1 text-justify">
                                ${commentData.noi_dung}
                            </div>
                        </div>
                    </div>
                </div>
            `;

            if (comments > 0) {
                const div = document.createElement('div');
                div.classList.add('pt-4');
                const hr = document.createElement('hr');
                div.appendChild(hr);
                newComment.appendChild(div);
            }

            reviewsContainer.insertAdjacentElement('afterbegin', newComment);
        }

        function sendcmt() {
            const content = document.getElementById('content-cmt').value;

            if (content.trim() === '') {
                notyf.error('Vui lòng nhập nội dung cần bình luận!');
                return;
            }

            const data = { ID_SP: <?= json_encode($data['id']); ?>, NoiDung: content};

            fetch('/api/user/comment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    notyf.success('Thêm bình luận thành công!');

                    const currentDate = new Date();
                    const day = currentDate.getDate();
                    const month = currentDate.getMonth() + 1;
                    const year = currentDate.getFullYear();
                    const formattedDate = `${day < 10 ? '0' + day : day}-${month < 10 ? '0' + month : month}-${year}`;

                    addCommentToTop({id: result.idcmt,ten: <?= json_encode($_SESSION["Ten"])?>, avatar: <?= json_encode($_SESSION["Avatar"])?>, noi_dung: content, ngay_binh_luan: formattedDate}); 
                    document.getElementById('content-cmt').value = '';

                    try {
                        document.getElementById('NoCmt').classList.add('hidden');
                    } catch (error) {}

                } else {
                    notyf.error('Thêm bình luận thất bại!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                notyf.error('Đã xảy ra lỗi khi viết bình luận!');
            });
        }

        function OpenComment(idcmt){
            document.getElementById("cmtModal").classList.remove("hidden");

            const commentElement = document.querySelector(`div[data-id="cmt${idcmt}"]`);

            const commentContentElement = commentElement ? commentElement.querySelector('.text-sm.pt-1.text-justify') : null;
            const phanhoi = commentContentElement ? commentContentElement.textContent.trim() : "";

            const commentElement2 = document.querySelector(`div[data-id="cmtcontainer${idcmt}"]`);
            const trangthaiElement = commentElement2 ? commentElement2.querySelector('.trangthai-cmt') : null;

            const trangthai = trangthaiElement ? trangthaiElement.getAttribute('data-value') : "";

            document.getElementById("phanhoicmt").value = phanhoi;
            document.getElementById("idcmt").value = idcmt;
            document.getElementById("trangthaicmt").value = trangthai;

        }

        function closeModalCmt(){
            document.getElementById("cmtModal").classList.add("hidden");
        }

        function updateCmt(){
            const idcmt = document.getElementById('idcmt').value
            const phanhoicmt = document.getElementById('phanhoicmt').value
            const trangthaicmt = document.getElementById('trangthaicmt').value

            const trangthaiElement = document.querySelector(`.trangthai-cmt[data-id="ttcmt${idcmt}"]`);
            trangthaiElement.setAttribute('data-value', trangthaicmt);

            const data = {
                MaBinhLuan: idcmt,
                TrangThai: trangthaicmt,
                PhanHoi: phanhoicmt
            };

            fetch('/api/admin/comment/status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    notyf.success('Cập nhật bình luận thành công!');

                    closeModalCmt()

                    const adminCmtElement = document.querySelector(`[data-id="cmt${idcmt}"]`);
                    adminCmtElement.innerHTML = '';

                    const divElement = document.querySelector(`div[data-id="cmtcontainer${idcmt}"]`);
                    if (trangthaicmt === "Đang ẩn") {
                        divElement.style.opacity = '0.6';
                    }else{
                        divElement.style.opacity = '1';
                    }

                    if(phanhoicmt){
                        const adminResponseDiv = document.createElement('div');
                        adminResponseDiv.classList.add('flex', 'gap-2', 'text-gray-700', 'pt-6', 'pl-10');
                        
                        adminResponseDiv.innerHTML = `
                            <img src="/public/image/human.png" alt="avt" class="w-10 h-10 rounded-full cursor-pointer ml-3">
                            <div>
                                <div class="flex gap-2 items-center">
                                    <div>
                                        <div class="flex gap-2 items-center">
                                            <div class="text-base font-bold">Quản Trị Viên</div>
                                            <div class="bg-custom-background text-xs text-white px-2 rounded-md">QTV</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-sm pt-1 text-justify">
                                    ${phanhoicmt}
                                </div>
                            </div>
                        `;
                        
                        adminCmtElement.appendChild(adminResponseDiv);
                    }

                } else {
                    notyf.error('Cập nhật bình luận thất bại!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                notyf.error('Đã xảy ra lỗi khi cập nhật bình luận!');
            });

        }

        function OpenReview(idcmt){
            document.getElementById("rvModal").classList.remove("hidden");

            const commentElement = document.querySelector(`div[data-id="rv${idcmt}"]`);

            const commentContentElement = commentElement ? commentElement.querySelector('.text-sm.pt-1.text-justify') : null;
            const phanhoi = commentContentElement ? commentContentElement.textContent.trim() : "";

            const commentElement2 = document.querySelector(`div[data-id="rvcontainer${idcmt}"]`);
            const trangthaiElement = commentElement2 ? commentElement2.querySelector('.trangthai-rv') : null;

            const trangthai = trangthaiElement ? trangthaiElement.getAttribute('data-value') : "";

            document.getElementById("phanhoirv").value = phanhoi;
            document.getElementById("idrv").value = idcmt;
            document.getElementById("trangthairv").value = trangthai;

        }

        function closeModalRv(){
            document.getElementById("rvModal").classList.add("hidden");
        }

        function updateRv(){
            const idcmt = document.getElementById('idrv').value
            const phanhoicmt = document.getElementById('phanhoirv').value
            const trangthaicmt = document.getElementById('trangthairv').value
        
            const trangthaiElement = document.querySelector(`.trangthai-rv[data-id="ttrv${idcmt}"]`);
            trangthaiElement.setAttribute('data-value', trangthaicmt);

            const data = {
                MaDanhGia: idcmt,
                TrangThai: trangthaicmt,
                PhanHoi: phanhoicmt
            };

            fetch('/api/admin/review/status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    notyf.success('Cập nhật đánh giá thành công!');

                    closeModalRv()

                    const adminCmtElement = document.querySelector(`[data-id="rv${idcmt}"]`);
                    adminCmtElement.innerHTML = '';

                    const divElement = document.querySelector(`div[data-id="rvcontainer${idcmt}"]`);
                    if (trangthaicmt === "Đang ẩn") {
                        divElement.style.opacity = '0.6';
                    }else{
                        divElement.style.opacity = '1';
                    }

                    if(phanhoicmt){
                        const adminResponseDiv = document.createElement('div');
                        adminResponseDiv.classList.add('flex', 'gap-2', 'text-gray-700', 'pt-6', 'pl-10');
                        
                        adminResponseDiv.innerHTML = `
                            <img src="/public/image/human.png" alt="avt" class="w-10 h-10 rounded-full cursor-pointer ml-3">
                            <div>
                                <div class="flex gap-2 items-center">
                                    <div>
                                        <div class="flex gap-2 items-center">
                                            <div class="text-base font-bold">Quản Trị Viên</div>
                                            <div class="bg-custom-background text-xs text-white px-2 rounded-md">QTV</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-sm pt-1 text-justify">
                                    ${phanhoicmt}
                                </div>
                            </div>
                        `;
                        
                        adminCmtElement.appendChild(adminResponseDiv);
                    }

                } else {
                    notyf.error('Cập nhật đánh giá thất bại!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                notyf.error('Đã xảy ra lỗi khi cập nhật đánh giá!');
            });

        }
        //Buy now kiểm tra đăng nhập
        // function BuyNow(){
        //     const email = '<?php echo $_SESSION['email'];?>';
        //     if(!email){
        //         notyf.error('Vui lòng đăng nhập để mua ngay!');
        //         setTimeout(() => {
        //             window.location.href = 'auth/login';
        //         }, 2000);
        //         return
        //     }
            
        //     let selectedProducts = []
        //     const quatity = document.getElementById('quantity').value
        //     if(quatity < 0){
        //         notyf.error('Số lượng phải lớn hơn 0');
        //         return
        //     }

        //     selectedProducts.push({
        //         id: '<?php echo $save_id; ?>',
        //         name: '<?php echo $save_name; ?>',
        //         price: <?php echo $save_price; ?>,
        //         pricesale: <?php echo $save_pricesale; ?>,
        //         quantity: `${quatity}`,
        //         imageUrl: '/<?php echo $save_img; ?>'
        //     });

        //     localStorage.setItem('selectedProducts', JSON.stringify(selectedProducts));

        //     window.location.href = '/checkout/preview?type=buynow'
        // }

        //Mua không cần đăng nhập 
        function BuyNow() {
            let selectedProducts = [];
            const quantity = document.getElementById('quantity').value;
            
            if (quantity <= 0) {
                notyf.error('Số lượng phải lớn hơn 0');
                return;
            }

            selectedProducts.push({
                id: '<?php echo $save_id; ?>',
                name: '<?php echo $save_name; ?>',
                price: <?php echo $save_price; ?>,
                pricesale: <?php echo $save_pricesale; ?>,
                quantity: `${quantity}`,
                imageUrl: '/<?php echo $save_img; ?>'
            });

            localStorage.setItem('selectedProducts', JSON.stringify(selectedProducts));

            window.location.href = '/checkout/preview?type=buynow';
        }


    </script>
    <script src="/public/js/notyf.min.js"></script>
    <script src="/public/js/product.js"></script>
    <script src="/public/js/heart.js"></script>
    <script src="/public/js/numbox.js"></script>
    <script src="/public/js/swiper-bundle.min.js"></script>
    <script src="/public/js/client.js"></script>
</body>

</html>