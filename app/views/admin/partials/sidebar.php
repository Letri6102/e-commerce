<aside id="sidebar"
    class="bg-custom-background text-white w-64 flex flex-col h-full fixed md:relative md:translate-x-0 transform -translate-x-full transition-transform duration-300 ease-in-out z-30">
    <div class="flex items-center justify-center mb-4 pt-4">
        <img src="/public/image/logo.png" alt="Logo" class="w-10 h-10 mr-2">
        <h1 class="text-2xl font-bold">BKStoreAdmin</h1>
    </div>
    <div class="overflow-y-auto p-2">
        <div class="mt-6 flex flex-col gap-2">
            <div class="px-4 py-1 text-gray-400 text-sm">Bảng điều khiển</div>
            <a href="/admin"
                class="rounded-lg <?php echo ($page == 1) ? 'bg-black' : ''; ?> w-full px-4 py-2 flex items-center transition-transform duration-300 ease-in-out">
                <div class="flex gap-2 m-1 transform transition-transform duration-300 ease-in-out hover:translate-x-2">
                    <img src="/public/image/dashboard.svg" alt="home" class="w-6 h-6">
                    <div>Trang chủ</div>
                </div>
            </a>
            <div class="px-4 py-1 text-gray-400 text-sm">Quản lý & cài đặt</div>
            <a href="/admin/user"
                class="rounded-lg <?php echo ($page == 2) ? 'bg-black' : ''; ?> w-full px-4 py-2 flex items-center transition-transform duration-300 ease-in-out">
                <div class="flex gap-2 m-1 transform transition-transform duration-300 ease-in-out hover:translate-x-2">
                    <img src="/public/image/user.svg" alt="home" class="w-6 h-6">
                    <div>Quản lý người dùng</div>
                </div>
            </a>
            <a href="/admin/product"
                class="rounded-lg <?php echo ($page == 3) ? 'bg-black' : ''; ?> w-full px-4 py-2 flex items-center transition-transform duration-300 ease-in-out">
                <div class="flex gap-2 m-1 transform transition-transform duration-300 ease-in-out hover:translate-x-2">
                    <img src="/public/image/product.svg" alt="home" class="w-6 h-6">
                    <div>Quản lý sản phẩm</div>
                </div>
            </a>
            <a href="/admin/order"
                class="rounded-lg <?php echo ($page == 4) ? 'bg-black' : ''; ?> w-full px-4 py-2 flex items-center transition-transform duration-300 ease-in-out">
                <div class="flex gap-2 m-1 transform transition-transform duration-300 ease-in-out hover:translate-x-2">
                    <img src="/public/image/icons8-order-100.png" alt="home" class="w-6 h-6">
                    <div>Quản lý đơn hàng</div>
                </div>
            </a>
            <a href="/admin/request"
                class="rounded-lg <?php echo ($page == 7) ? 'bg-black' : ''; ?> w-full px-4 py-2 flex items-center transition-transform duration-300 ease-in-out">
                <div class="flex gap-2 m-1 transform transition-transform duration-300 ease-in-out hover:translate-x-2">
                    <img src="/public/image/icons8-invite-100.png" alt="home" class="w-6 h-6">
                    <div>Quản lý yêu cầu</div>
                </div>
            </a>
            <a href="/admin/promotion"
                class="rounded-lg <?php echo ($page == 8) ? 'bg-black' : ''; ?> w-full px-4 py-2 flex items-center transition-transform duration-300 ease-in-out">
                <div class="flex gap-2 m-1 transform transition-transform duration-300 ease-in-out hover:translate-x-2">
                    <img src="/public/image/icons8-discount-100.png" alt="home" class="w-6 h-6">
                    <div>Quản lý khuyến mãi</div>
                </div>
            </a>
            <a href="/admin/news"
                class="rounded-lg w-full <?php echo ($page == 9) ? 'bg-black' : ''; ?> px-4 py-2 flex items-center transition-transform duration-300 ease-in-out">
                <div class="flex gap-2 m-1 transform transition-transform duration-300 ease-in-out hover:translate-x-2">
                    <img src="/public/image/icons8-news-64.png" alt="home" class="w-6 h-6">
                    <div>Quản lý tin tức</div>
                </div>
            </a>
            <div>
                <a href="#" id="configLink"
                    class="rounded-lg <?php echo ($page == 10) ? 'bg-black' : ''; ?> w-full px-4 py-2 flex items-center justify-between transition-transform duration-300 ease-in-out"
                    onclick="toggleSubMenu(event)">
                    <div
                        class="flex gap-2 m-1 transform transition-transform duration-300 ease-in-out hover:translate-x-2">
                        <img src="/public/image/setting.svg" alt="home" class="w-6 h-6">
                        <div>Cài đặt hệ thống</div>
                    </div>
                    <div id="arrow" class="w-4 h-4 transform transition-transform duration-300 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 transition-transform duration-300 ease-in-out" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </a>
                <div id="subMenu" class="hidden pl-8 mt-2">
                    <a href="/admin/banner"
                        class="rounded-lg w-full px-4 py-2 flex items-center transition-transform duration-300 ease-in-out">
                        <div
                            class="flex gap-2 m-1 transform transition-transform duration-300 ease-in-out hover:translate-x-2">
                            <img src="/public/image/voucher.png" alt="home" class="w-6 h-6">
                            <div class="text-sm">Quản lý banner</div>
                        </div>
                    </a>
                    <a href="/admin/contact"
                        class="rounded-lg w-full px-4 py-2 flex items-center transition-transform duration-300 ease-in-out">
                        <div
                            class="flex gap-2 m-1 transform transition-transform duration-300 ease-in-out hover:translate-x-2">
                            <img src="/public/image/icons8-contact-64.png" alt="home" class="w-6 h-6">
                            <div class="text-sm">Quản lý liên hệ</div>
                        </div>
                    </a>
                    <a href="/admin/social"
                        class="rounded-lg w-full px-4 py-2 flex items-center transition-transform duration-300 ease-in-out">
                        <div
                            class="flex gap-2 m-1 transform transition-transform duration-300 ease-in-out hover:translate-x-2 items-center">
                            <img src="/public/image/icons8-group-skin-type-7-96.png" alt="home" class="w-6 h-6">
                            <div class="text-sm">Quản lý MXH</div>
                        </div>
                    </a>
                    <a href="/admin/partner"
                        class="rounded-lg w-full px-4 py-2 flex items-center transition-transform duration-300 ease-in-out">
                        <div
                            class="flex gap-2 m-1 transform transition-transform duration-300 ease-in-out hover:translate-x-2">
                            <img src="/public/image/icons8-discord-partner-server-owner-badge-100.png" alt="home"
                                class="w-6 h-6">
                            <div class="text-sm">Quản lý đối tác</div>
                        </div>
                    </a>
                    <a href="/admin/setting"
                        class="rounded-lg w-full px-4 py-2 flex items-center transition-transform duration-300 ease-in-out">
                        <div
                            class="flex gap-2 m-1 transform transition-transform duration-300 ease-in-out hover:translate-x-2">
                            <img src="/public/image/icons8-setting-100.png" alt="home" class="w-6 h-6">
                            <div class="text-sm">Cấu hình hệ thống</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</aside>
<script>
    function toggleSubMenu(event) {
        event.preventDefault();
        const subMenu = document.getElementById('subMenu');
        const arrow = document.getElementById('arrow');

        subMenu.classList.toggle('hidden');

        arrow.classList.toggle('rotate-180');
    }
</script>