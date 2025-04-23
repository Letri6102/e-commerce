<?php
$phanloai = $_GET['keyword'];
if ($phanloai == '') {
    header("Location: 404");
    exit;
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
    <link rel="stylesheet" href="/public/css/client.css">
    <link rel="stylesheet" href="/public/css/notyf.min.css">
    <title><?= $phanloai ?> | BKSTORE</title>
</head>

<body class="bg-gray-100">
    <div class="h-screen">
        <header id="header-content" class="sticky top-0 z-50">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/header.php'; ?>
        </header>
        <div class="overflow-y-auto">
            <main class="container max-w-screen-1200 mx-auto min-h-screen pt-16 pb-20 lg:pb-10 px-1 lg:px-0">
                <div class="w-full p-1 lg:p-0">
                    <nav class="flex gap-2 text pb-4 text-sm text-gray-700 pt-4">
                        <a href="/" class="cursor-pointer hover:text-blue-500 focus:outline-none">BkStore.Vn</a>
                        <div>&rsaquo;</div>
                        <div class="text-gray-500"><?= $phanloai ?></div>
                    </nav>
                    <div class="flex gap-2 pb-4 h-12" id="buttonSort">
                        <button id="sortDefault"
                            class="rounded-lg bg-blue-100 border-custom-blue text-custom-blue text-sm w-24 flex items-center justify-center">
                            Tất cả
                        </button>
                        <button id="sortHigh"
                            class="rounded-lg bg-white border-2 border-gray-500 text-gray-500 text-sm w-24 flex items-center justify-center">
                            <svg height="15" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                <path
                                    d="M416 288h-95.1c-17.67 0-32 14.33-32 32s14.33 32 32 32H416c17.67 0 32-14.33 32-32S433.7 288 416 288zM544 32h-223.1c-17.67 0-32 14.33-32 32s14.33 32 32 32H544c17.67 0 32-14.33 32-32S561.7 32 544 32zM352 416h-32c-17.67 0-32 14.33-32 32s14.33 32 32 32h32c17.67 0 31.1-14.33 31.1-32S369.7 416 352 416zM480 160h-159.1c-17.67 0-32 14.33-32 32s14.33 32 32 32H480c17.67 0 32-14.33 32-32S497.7 160 480 160zM192.4 330.7L160 366.1V64.03C160 46.33 145.7 32 128 32S96 46.33 96 64.03v302L63.6 330.7c-6.312-6.883-14.94-10.38-23.61-10.38c-7.719 0-15.47 2.781-21.61 8.414c-13.03 11.95-13.9 32.22-1.969 45.27l87.1 96.09c12.12 13.26 35.06 13.26 47.19 0l87.1-96.09c11.94-13.05 11.06-33.31-1.969-45.27C224.6 316.8 204.4 317.7 192.4 330.7z">
                                </path>
                            </svg>
                            Giá cao
                        </button>
                        <button id="sortLow"
                            class="rounded-lg bg-white border-2 border-gray-500 text-gray-500 text-sm w-24 flex items-center justify-center">
                            <svg height="15" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                <path
                                    d="M416 288h-95.1c-17.67 0-32 14.33-32 32s14.33 32 32 32H416c17.67 0 32-14.33 32-32S433.7 288 416 288zM544 32h-223.1c-17.67 0-32 14.33-32 32s14.33 32 32 32H544c17.67 0 32-14.33 32-32S561.7 32 544 32zM352 416h-32c-17.67 0-32 14.33-32 32s14.33 32 32 32h32c17.67 0 31.1-14.33 31.1-32S369.7 416 352 416zM480 160h-159.1c-17.67 0-32 14.33-32 32s14.33 32 32 32H480c17.67 0 32-14.33 32-32S497.7 160 480 160zM192.4 330.7L160 366.1V64.03C160 46.33 145.7 32 128 32S96 46.33 96 64.03v302L63.6 330.7c-6.312-6.883-14.94-10.38-23.61-10.38c-7.719 0-15.47 2.781-21.61 8.414c-13.03 11.95-13.9 32.22-1.969 45.27l87.1 96.09c12.12 13.26 35.06 13.26 47.19 0l87.1-96.09c11.94-13.05 11.06-33.31-1.969-45.27C224.6 316.8 204.4 317.7 192.4 330.7z">
                                </path>
                            </svg>
                            Giá thấp
                        </button>
                    </div>

                    <div class="flex flex-col items-center justify-center gap-2 pt-10 hidden" id="noProduct">
                        <img src="/public/image/icons8-sad-100.png" alt="sad-icon" class="w-8 h-8">
                        <div class="text-center text-gray-500">Không tìm thấy sản phẩm nào</div>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4" id="product-item-container">

                    </div>
                    <div id="pagination-container" class="flex justify-center gap-2 mt-4"></div>
                </div>
            </main>
        </div>
        <?php $page = 1;
        include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/footer.php'; ?>
    </div>
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

        function redirectToPage(id) {
            const targetUrl = `/product?id=${id}`;
            window.location.href = targetUrl;
        }

        document.addEventListener('DOMContentLoaded', () => {
            const buttons = document.querySelectorAll('#buttonSort button');

            const updateButtonStyles = (selectedButton) => {
                buttons.forEach(button => {
                    if (button === selectedButton) {
                        button.classList.add('bg-blue-100', 'border-custom-blue', 'text-custom-blue');
                        button.classList.remove('bg-white', 'border-gray-500', 'text-gray-500');
                    } else {
                        button.classList.add('bg-white', 'border-2', 'border-gray-500', 'text-gray-500');
                        button.classList.remove('bg-blue-100', 'border-custom-blue', 'text-custom-blue');
                    }
                });
            };

            buttons.forEach(button => {
                button.addEventListener('click', () => {
                    updateButtonStyles(button);
                });
            });


            let allProducts = [];
            let currentPage = 1;
            const itemsPerPage = 10;
            let sortOrder = 'default';

            function fetchProducts() {
                fetch('/api/product/productType', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        PhanLoai: "<?php echo $phanloai; ?>"
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        allProducts = data.DanhSachSanPham;
                        renderPage();
                    })
                    .catch(error => {
                        console.error('Error fetching products:', error);
                    });
            }

            function sortProducts(products) {
                if (sortOrder === 'priceHigh') {
                    return products.sort((a, b) => b.GiaSauGiam - a.GiaSauGiam);
                } else if (sortOrder === 'priceLow') {
                    return products.sort((a, b) => a.GiaSauGiam - b.GiaSauGiam);
                }
                return products;
            }

            function paginateProducts(products) {
                const startIndex = (currentPage - 1) * itemsPerPage;
                const endIndex = startIndex + itemsPerPage;
                return products.slice(startIndex, endIndex);
            }

            function renderPagination(totalPages) {
                const paginationContainer = document.getElementById('pagination-container');
                paginationContainer.innerHTML = '';

                for (let i = 1; i <= totalPages; i++) {
                    const pageButton = document.createElement('button');
                    pageButton.textContent = i;
                    pageButton.className = `px-3 py-1 border rounded ${i === currentPage ? 'bg-custom-background text-white' : 'bg-white'}`;
                    pageButton.addEventListener('click', () => {
                        currentPage = i;
                        renderPage();
                    });
                    paginationContainer.appendChild(pageButton);
                }
            }

            function renderPage() {
                const sortedProducts = sortProducts([...allProducts]); 
                const paginatedProducts = paginateProducts(sortedProducts); 
                displayItem(paginatedProducts); 
                renderPagination(Math.ceil(allProducts.length / itemsPerPage), currentPage);
            }


            function displayItem(products) {
                const cartContainer = document.getElementById('product-item-container');
                cartContainer.innerHTML = '';

                if (products.length === 0) {
                    document.getElementById('noProduct').classList.remove('hidden');
                    document.getElementById('buttonSort').classList.add('hidden');
                } else {
                    document.getElementById('noProduct').classList.add('hidden');
                    document.getElementById('buttonSort').classList.remove('hidden');
                }

                products.forEach(product => {
                    const productDiv = document.createElement('div');
                    productDiv.innerHTML = `
                <div class="bg-white p-2 rounded-lg shadow-lg w-full">
                    <div class="cursor-pointer" onclick="redirectToPage(${product.ID_SP})">
                            <div class="h-44 flex justify-center">
                                <img src="${product.Hinh}" alt="Product Image" class="object-cover h-full rounded-md">
                            </div>
                            <div class="pt-4 pb-4 text-sm">
                                <div class="font-semibold mt-2 h-16 text-black-700">${product.TenSP}</div>
                                <div class="flex gap-2 items-center">
                                    <p class="text-custom-blue font-bold text-base">${formatCurrency(product.GiaSauGiam)}</p>
                                    <p class="text-gray-500 text-sm"><del>${formatCurrency(product.Gia)}</del></p>                                             
                                </div>
                            </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            ${renderStars(product.SoSao)}
                        </div>
                        <?php if (isset($_SESSION['email']) && $_SESSION['email'] != '') { ?>
                            <button class="heart-button focus:outline-none" data-product-id="${product.ID_SP}" onclick="toggleHeart(this)">
                                <svg class="heart-icon w-6 h-6 text-red-500 transition duration-300 ease-in-out"
                                    xmlns="http://www.w3.org/2000/svg" fill="${product.YeuThich == 1 ? 'currentColor' : 'none'}" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 21c-4.35-3.2-8-5.7-8-9.5 0-2.5 2-4.5 4.5-4.5 1.74 0 3.41 1 4.5 2.54 1.09-1.54 2.76-2.54 4.5-2.54 2.5 0 4.5 2 4.5 4.5 0 3.8-3.65 6.3-8 9.5z" />
                                </svg>
                            </button>
                        <?php } ?>
                    </div>
                </div>
            `;
                    cartContainer.appendChild(productDiv);
                });
            }

            function renderStars(sao) {
                const maxStars = 5;
                let output = '';

                for (let i = 1; i <= maxStars; i++) {
                    if (i <= sao) {
                        output += '<span class="text-yellow-500 text-xl">★</span>';
                    } else {
                        output += '<span class="text-gray-300 text-xl">☆</span>';
                    }
                }

                return output;
            }

            function formatCurrency(number) {
                return number.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replace('₫', 'đ');
            }

            document.getElementById('buttonSort').addEventListener('click', (event) => {
                if (event.target.textContent.includes('Giá cao')) {
                    sortOrder = 'priceHigh';
                } else if (event.target.textContent.includes('Giá thấp')) {
                    sortOrder = 'priceLow';
                } else {
                    sortOrder = 'default';
                }
                renderPage();
            });

            fetchProducts();
        });
    </script>
    <script src="/public/js/notyf.min.js"></script>
    <!-- <script src="/public/js/heart.js"></script> -->
    <script src="/public/js/swiper-bundle.min.js"></script>
    <script src="/public/js/client.js"></script>

</body>

</html>