<?php
require_once dirname(__DIR__, 4) . '/config/db.php';
?>
<div class="fixed w-full bg-custom-background border-b text-black p-4 top-0 z-50">
    <div class="container max-w-screen-1200 mx-auto flex items-center justify-between">
        <div class="flex items-center justify-between gap-2 text-2xl font-bold text-white cursor-pointer"
            onclick="window.location.href='/'">
            <img src="/public/image/logo.png" alt="logo web" class="w-8 h-8">
            <div class="hidden md:block">TPC COMPANY</div>
        </div>
        <div class="flex items-center mx-4 ml-6 flex-1 justify-center">
            <input id="searchInput" type="text" placeholder="Tìm kiếm sản phẩm..."
                class="border rounded-l-md p-2 h-10 w-full lg:w-4/6 focus:outline-none">
            <button onclick="goSearch()" class="bg-white text-white rounded-r-md p-2 hover:bg-gray-200 h-10">
                <img src="/public/image/search.png" alt="Search" class="w-5 h-5">
            </button>
        </div>
        <?php if (isset($_SESSION["email"])) { ?>
            <div id="avatar-container" class="flex items-center gap-1 relative">
                <div class="relative">
                    <div class="relative ml-4 cursor-pointer pr-1 hidden md:block" id="notice">
                        <img src="/public/image/bell.png" alt="Bell" class="w-8 h-8">
                        <span id="cart-notice"
                            class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">3</span>
                    </div>
                    <div id="dropdown-notice" class="absolute right-0 top-10 bg-white border rounded-lg shadow-lg hidden">
                        <div class="pt-4 pb-4 pr-3 pl-3 w-80 h-80 overflow-y-auto">
                            <div class="font-bold">Thông báo</div>
                            <div id="notifications" class="flex flex-col gap-1 pt-2"></div>
                        </div>
                    </div>
                </div>
                <div class="relative ml-4 cursor-pointer pr-1" onclick="goCart()">
                    <img src="/public/image/cart.png" alt="Shopping Cart" class="w-8 h-8">
                    <span id="cart-count" 
                        class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center"></span>
                </div>
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
                            <div class="text-xs text-gray-500 pt-2">Quản lý tài khoản</div>
                            <ul class="pt-2 text-sm">
                                <li>
                                    <a href="/my/profile" class="px-3 py-2 hover:bg-gray-100 cursor-pointer rounded-lg flex gap-1 items-center">
                                        <img src="/public/image/user.png" alt="user" class="h-6">
                                        <div>Thông tin tài khoản</div>
                                    </a>
                                </li>
                                <?php if($_SESSION['Role'] == 'Admin'){ ?>
                                    <li>
                                        <a href="/admin" class="px-3 py-2 hover:bg-gray-100 cursor-pointer rounded-lg flex gap-1 items-center">
                                            <img src="/public/image/icons8-admin-96.png" alt="user" class="h-6">
                                            <div>Quản lý hệ thống</div>
                                        </a>
                                    </li>
                                <?php } else { ?>
                                    <li>
                                        <a href="/my/order" class="px-3 py-2 hover:bg-gray-100 cursor-pointer rounded-lg flex gap-1 items-center">
                                            <img src="/public/image/his.png" alt="his" class="h-6">
                                            <div>Lịch sử mua hàng</div>
                                        </a>
                                    </li>
                                <?php } ?>
                                <li>
                                    <a href="/my/account" class="px-3 py-2 hover:bg-gray-100 cursor-pointer rounded-lg flex gap-1 items-center">
                                        <img src="/public/image/pw.png" alt="warranty" class="h-6">
                                        <div>Thay đổi mật khẩu</div>
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
            </div>
        <?php } else { ?>
            <div id="avatar-container" class="flex items-center gap-1">
                <a href="/auth/login" class="block md:hidden text-white hover:text-black transition duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="11" />
                        <circle cx="12" cy="7" r="4" />
                        <path d="M5 20c0-7 15-7 15 0" />
                    </svg>
                </a>

                <a href="/auth/login"
                    class="text-white border border-white rounded-md px-2 py-1 hover:bg-white hover:text-black transition duration-300 font-bold hidden md:block"><span>ĐĂNG
                        NHẬP</span></a>
            </div>
        <?php } ?>
    </div>
</div>
<script>
    function goSearch(){
        const inputElement = document.getElementById('searchInput').value;
        window.location.href = `/search?keyword=${inputElement}`
    }
    
    document.getElementById('searchInput').addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
            goSearch();
        }
    });
    try {
        function CountCart(){
            let countCart = 0;
            const mail = <?php echo json_encode( $_SESSION["email"]); ?>;
            if(!mail){
                return
            }

            fetch('/api/user/cart')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        countCart = data.danh_sach_san_pham.length;
                    } else {
                        countCart = 0;
                    }
                })
                .catch(error => {
                    console.error('Error fetching cart items:', error);
                    countCart = 0;
                })
                .finally(() => {
                    const cartCountElement = document.getElementById('cart-count');
                    if (cartCountElement) {
                        cartCountElement.textContent = countCart;
                    }
                });
        }

        function CountNotice(){
            let countNotice = 0;
            let Notice = [];
            const mail = <?php echo json_encode( $_SESSION["email"]); ?>;
            if(!mail){
                return
            }

            fetch('/api/user/notice')
                .then(response => response.json())
                .then(data => {
                    if (data.sucess) {
                        countNotice = data.notice_list.filter(item => item.TrangThai === "Unread").length;
                        Notice = data.notice_list;
                    } else {
                        countNotice = 0;
                    }
                })
                .catch(error => {
                    console.error('Error fetching cart items:', error);
                    countNotice = 0;
                })
                .finally(() => {
                    const cartNoticeElement = document.getElementById('cart-notice');
                    if (cartNoticeElement) {
                        cartNoticeElement.textContent = countNotice;
                    }
                    if(Notice.length){
                        const notificationsContainer = document.getElementById("notifications");
                        Notice.forEach(order => {
                            const notificationItem = document.createElement("div");
                            notificationItem.onclick = function () {
                                go(order.type, order.ID_Redirect, order.MaThongBao)
                            };
                            notificationItem.className = `flex gap-2 cursor-pointer rounded-md py-2 px-1 ${order.TrangThai === "Unread" ? 'bg-blue-100' : ''}`;

                            notificationItem.innerHTML = `
                                <div class="flex justify-center items-center rounded-full bg-custom-background h-12 w-12">
                                    <img src="/public/image/notice.png" alt="Bell" class="h-10 w-10">
                                </div>
                                <div class="text-sm text-gray-800 flex-1">
                                    <div class="flex flex-col justify-between h-full">
                                        <div>${order.noi_dung}</div>
                                        <div class="text-xs text-gray-500">${order.NgayThongBao}</div>
                                    </div>
                                </div>
                            `;

                            notificationsContainer.appendChild(notificationItem);
                        });
                    }

                    if(Notice.length === 0){
                        const notificationsContainer = document.getElementById("notifications");
                        const notificationItem = document.createElement("div");
                        notificationItem.className = `flex flex-col items-center justify-center gap-2 pt-10`;
                        notificationItem.innerHTML = `
                            <img src="/public/image/icons8-sad-100.png" alt="sad-icon" class="w-8 h-8">
                            <div class="text-center text-gray-500">Chưa có thông báo nào!</div>
                        `
                        notificationsContainer.appendChild(notificationItem);
                    }
                });
        }

        function go(type,id,idthongbao){
            if(type === "Đơn hàng"){
                fetch('/api/user/noticeInfo', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        MaThongBao: idthongbao
                    }),
                })
                .then(response => {
                    
                    return response.json();
                })
                .catch(error => {})
                .finally(() => {
                    window.location.href = `/my/order/detail?id=${id}`
                })
            }else if(type === "Yêu cầu"){
                fetch('/api/user/noticeInfo', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        MaThongBao: idthongbao
                    }),
                })
                .then(response => {
                    
                    return response.json();
                })
                .catch(error => {})
                .finally(() => {
                    window.location.href = `/suggest/detail?id=${id}`
                })
            }

        }

        CountCart();

        CountNotice();
        

        function goCart() {
            window.location.href = "/cart"
        }

        document.getElementById("avatar").addEventListener("click", function () {
            const dropdown = document.getElementById("dropdown");
            dropdown.classList.toggle("hidden");
        });

        document.getElementById("notice").addEventListener("click", function () {
            const dropdown_notice = document.getElementById("dropdown-notice");
            dropdown_notice.classList.toggle("hidden");
        });

        window.addEventListener("click", function (e) {
            const avatar = document.getElementById("avatar");
            const dropdown = document.getElementById("dropdown");
            if (!avatar.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add("hidden");
            }

            const notice = document.getElementById("notice");
            const dropdown_notice = document.getElementById("dropdown-notice");
            if (!notice.contains(e.target) && !dropdown_notice.contains(e.target)) {
                dropdown_notice.classList.add("hidden");
            }
        });
        document.getElementById("logout-btn").addEventListener("click", function () {
            fetch('/api/auth/logout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(() => {
                    window.location.href = '/';
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                });
        });
    } catch { }
</script>