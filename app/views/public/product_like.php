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
    <title>Trang chủ | BKSTORE</title>
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
                            <div class="swiper-wrapper">
                                <div
                                    class="swiper-slide bg-white flex items-center justify-center text-white rounded-lg overflow-hidden">
                                    <img src="/public/image/1.webp" alt="1">
                                </div>
                                <div
                                    class="swiper-slide bg-white flex items-center justify-center text-white rounded-lg overflow-hidden">
                                    <img src="/public/image/2.webp" alt="2">
                                </div>
                                <div
                                    class="swiper-slide bg-white flex items-center justify-center text-white rounded-lg overflow-hidden">
                                    <img src="/public/image/3.webp" alt="3">
                                </div>
                            </div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                    <div class="w-1/5 hidden lg:block ">
                        <div class="rounded-lg overflow-hidden mb-4">
                            <img src="/public/image/1.webp" alt="sale1">
                        </div>
                        <div class="rounded-lg overflow-hidden">
                            <img src="/public/image/2.webp" alt="sale2">
                        </div>
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
                        SẢN PHẨM YÊU THÍCH
                    </div>
                    <div class="swiper-container-product overflow-hidden">
                        <div class="swiper-wrapper" id="products-container">
                        </div>
                    </div>
                    <div class="flex flex-col items-center justify-center gap-2 pt-5 pb-10 hidden" id="noProduct">
                        <img src="/public/image/icons8-sad-100.png" alt="sad-icon" class="w-8 h-8">
                        <div class="text-center text-gray-500">Chưa có sản phẩm nào trong mục yêu thích</div>
                    </div>
                </div>
            </main>
        </div>
        <?php $page = 1;
        include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/footer.php'; ?>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            fetch(`${window.location.origin}/api/product/like`, {  
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                },
                credentials: 'include' 
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const list = data.product_list;

                        const productsContainer = document.getElementById('products-container');
                        productsContainer.innerHTML = '';
                        list.forEach(product => {
                            const productHTML = `
                                <div class="swiper-slide-product overflow-hidden">
                                    <div class="bg-white p-2 rounded-lg shadow-lg w-full">
                                        <div class="h-44 flex justify-center">
                                            <img src="${product.hinh[0]}" alt="Product Image"
                                                class="object-cover h-full rounded-md">
                                        </div>
                                        <div class="pt-4 pb-4 text-sm">
                                            <div class="font-semibold mt-2 h-16 text-black-700">${product.ten}</div>
                                            <p class="text-custom-blue font-bold text-base">${product.gia_sau_giam_gia.toLocaleString('vi-VN')} đ</p>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center">
                                                <?php echo renderStars(sao: 4); ?>
                                            </div>
                                            <button class="heart-button focus:outline-none" data-product-id="${product.id}" onclick="toggleHeart(this)">
                                                <svg class="heart-icon w-6 h-6 text-red-500 transition duration-300 ease-in-out"
                                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 21c-4.35-3.2-8-5.7-8-9.5 0-2.5 2-4.5 4.5-4.5 1.74 0 3.41 1 4.5 2.54 1.09-1.54 2.76-2.54 4.5-2.54 2.5 0 4.5 2 4.5 4.5 0 3.8-3.65 6.3-8 9.5z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                            productsContainer.insertAdjacentHTML('beforeend', productHTML);
                        });
                } else {
                    if(data.message === "Người dùng chưa đăng nhập"){
                        window.location.href = '/auth/login'
                        return
                    }

                    if(data.message === "Chưa thích sản phẩm nào"){
                        document.getElementById('noProduct').classList.remove('hidden')



                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('products-container').innerText = 'Có lỗi xảy ra khi thực hiện yêu cầu';
            });
        });
    </script>
    <script>
        function toggleHeart(button) {
            const heartIcon = button.querySelector('.heart-icon');
            const productId = button.getAttribute('data-product-id')
            const isFilled = heartIcon.getAttribute('fill') === 'currentColor';

            if (isFilled) {
                const res = unlikeProduct(productId);
                if(res){
                    heartIcon.setAttribute('fill', 'none');
                    notyf.success('Xoá sản phẩm khỏi mục yêu thích thành công!');
                }
            } else {
                const res = likeProduct(productId);
                if(res){
                    heartIcon.setAttribute('fill', 'currentColor');
                    notyf.success('Thêm vào sản phẩm yêu thích thành công!');
                }
            }
        }

        function likeProduct(productId) {
            return fetch('/api/user/like', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    ID_SP: productId,
                }),
            })
            .then(response => {
                if (!response.ok) {
                    return false;
                }
                return response.json();
            })
            .then(data => {
                return data.success === true;
            })
            .catch(error => {
                return false;
            });
        }

        function unlikeProduct(productId) {
            return fetch('/api/user/unlike', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    ID_SP: productId,
                }),
            })
            .then(response => {
                if (!response.ok) {
                    return false;
                }
                return response.json();
            })
            .then(data => {
                return data.success === true;
            })
            .catch(error => {
                return false;
            });
        }
    </script>
    <script src="/public/js/notyf.min.js"></script>
    <script src="/public/js/heart.js"></script>
    <script src="/public/js/swiper-bundle.min.js"></script>
    <script src="/public/js/client.js"></script>
</body>

</html>