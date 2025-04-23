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
    <title>Tin tức | BKSTORE</title>
</head>

<body class="bg-gray-100">
    <div class="h-screen">
        <header id="header-content" class="sticky top-0 z-50">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/header.php'; ?>
        </header>
        <div class="overflow-y-auto">
            <main class="container max-w-screen-1200 mx-auto min-h-screen pt-16 pb-20 lg:pb-10 px-1 lg:px-0">
                <div class="flex gap-6 w-full mt-4 rounded-lg p-4">
                    <div class="w-full lg:w-4/5 rounded-lg">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Tin tức nổi bật</h2>

                        <div id="news" class="space-y-4"></div>

                        <div id="pagination" class="flex justify-center gap-2 pt-4"></div>
                    </div>
                    <div class="w-1/5 hidden lg:block ">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Tin tức mới nhất</h2>

                        <div id="news-side" class="space-y-4"></div>
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
            const news = document.getElementById('news');
            const newsSide = document.getElementById('news-side');
            const pagination = document.getElementById('pagination');
            const filterButtons = document.querySelectorAll('.filter-btn');
            let newsData = [];
            let currentPage = 1;
            const newsPerPage = 3;

            fetch(`${window.location.origin}/api/system/inforList`)
                .then(response => response.json())
                .then(data => {
                    newsData = data.danh_sach_tin_tuc;
                    renderNews('all', currentPage);
                    renderNewsSide();
                })
                .catch(error => {
                    console.error("Error fetching orders:", error);
                });

            function renderNews(status, page) {
                news.innerHTML = '';
                // const filteredOrders = status === 'all' ? ordersData : ordersData.filter(order => order.trang_thai === status);
                const start = (page - 1) * newsPerPage;
                const end = start + newsPerPage;
                const paginatedNews = newsData.slice(start, end);
                
                paginatedNews.forEach(New => {
                    const newItem = document.createElement('div');
                    newItem.classList.add('bg-white', 'rounded-lg', 'shadow-lg', 'overflow-hidden');

                    newItem.innerHTML = `
                        <div class="bg-white flex items-center justify-center text-white rounded-lg overflow-hidden">
                            <img src="${New.AnhMinhHoa[0].LinkAnh}" alt="Tin tức ${New.MaTinTuc}">
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-xl mb-2 text-blue-900">${New.TieuDe}</h3>
                            <a href="/news/detail?id=${New.MaTinTuc}" class="text-blue-500 hover:text-blue-800 mt-2 block">Đọc thêm &rarr;</a>
                        </div>
                    `;
                    news.appendChild(newItem);
                });

                renderPagination(newsData.length, page);
            }

            function renderNewsSide() {
                newsSide.innerHTML = '';
                // const filteredOrders = status === 'all' ? ordersData : ordersData.filter(order => order.trang_thai === status);

                const paginatedNews = newsData.slice(0, 3);
                
                paginatedNews.forEach(New => {
                    const newItem = document.createElement('div');
                    newItem.classList.add('bg-white', 'rounded-lg', 'shadow-lg', 'overflow-hidden');

                    newItem.innerHTML = `
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <img src="${New.AnhMinhHoa[0].LinkAnh}" alt="Tin tức ${New.MaTinTuc}" class="w-full h-24 object-cover">
                            <div class="p-3 flex flex-col justify-center">
                                <h3 class="font-bold text-sm text-blue-900">${New.TieuDe}</h3>
                                <a href="/news/detail?id=${New.MaTinTuc}" class="text-blue-500 hover:text-blue-800 text-xs mt-1">Đọc thêm &rarr;</a>
                            </div>
                        </div>
                    `;
                    newsSide.appendChild(newItem);
                });
            }



            function renderPagination(totalNews, currentPage) {
                pagination.innerHTML = '';
                const totalPages = Math.ceil(totalNews / newsPerPage);
                
                for (let i = 1; i <= totalPages; i++) {
                    const pageButton = document.createElement('button');
                    pageButton.innerText = i;
                    pageButton.classList.add('px-4', 'py-2', 'rounded', 'mx-1');
                    pageButton.classList.add('bg-blue-500', 'text-white');
                    
                    pageButton.addEventListener('click', () => {
                        renderNews('all', i);
                        currentPage = i;
                    });

                    pagination.appendChild(pageButton);
                }
            }
        });
    </script>
</body>

</html>