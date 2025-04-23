<header class="bg-white border-b text-black p-4 flex items-center justify-between relative z-10">
    <button id="sidebar-toggle" class="md:hidden text-gray-700 focus:outline-none mr-2">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
        </svg>
    </button>
    <div></div>
    <div class="relative">
        <img src="<?=$_SESSION["Avatar"]?>"
            alt="User Avatar" class="w-8 h-8 rounded-full cursor-pointer ml-3" id="avatar">
        <div id="dropdown" class="absolute right-0 top-10 bg-white border rounded-lg shadow-lg hidden">
            <div class="pt-4 pb-4 pr-3 pl-3 w-72">
                <div class="flex gap-2 w-full">
                    <img src="<?=$_SESSION["Avatar"]?>"
                        alt="User Avatar" class="w-10 h-10 rounded-full cursor-pointer">
                    <div class="flex-1">
                        <div class="font-bold"><?php echo $_SESSION["Ten"]; ?></div>
                        <div class="text-sm text-gray-700"><?php echo $_SESSION["email"]; ?></div>
                    </div>
                </div>
                <div class="text-xs text-gray-500 pt-2">Quản lý hệ thống</div>
                <ul class="pt-2 text-sm">
                    <li>
                        <a href="/" class="px-3 py-2 hover:bg-gray-100 cursor-pointer rounded-lg flex gap-1 items-center">
                            <img src="/public/image/home.png" alt="user" class="h-6 opacity-50">
                            <div>Quay về trang chủ</div>
                        </a>
                    </li>
                    <li class="px-3 py-2 hover:bg-gray-100 cursor-pointer rounded-lg flex gap-1 items-center"
                        id="logout-btn">
                        <img src="/public/image/exit.png" alt="exit" class="h-6">
                        <div>Đăng xuất</div>
                    </li>
                </ul>
            </div>

        </div>
    </div>
    <script>
        document.getElementById("avatar").addEventListener("click", function () {
            const dropdown = document.getElementById("dropdown");
            dropdown.classList.toggle("hidden");
        });

        document.getElementById("logout-btn").addEventListener("click", function () {
            fetch('/api/auth/logout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(() => {
                    window.location.href = '/admin/login';
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                });
        });
    </script>
</header>
