<footer
    class="flex items-center justify-between bg-white border-t font-medium text-gray-500 text-center p-4 w-full fixed bottom-0 block lg:hidden rounded-t-xl shadow-lg text-xs z-40">
    <div class="flex flex-col gap-1 items-center justify-center cursor-pointer footer-item <?php echo ($page == 1) ? 'text-custom-blue' : ''; ?>" onclick="goHome()">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M3 9.75L12 3l9 6.75v9.75A1.5 1.5 0 0119.5 21h-15A1.5 1.5 0 013 19.5V9.75z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 21V12h6v9" />
        </svg>
        <div>Trang chủ</div>
    </div>
    <div class="flex flex-col gap-1 items-center justify-center cursor-pointer footer-item <?php echo ($page == 4) ? 'text-custom-blue' : ''; ?>" onclick="goKhuyenMai()">
        <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20.1367 10H4.13672V18C4.13672 21 5.13672 22 8.13672 22H16.1367C19.1367 22 20.1367 21 20.1367 18V10Z" 
                stroke="currentColor" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
            <path d="M21.668 7V8C21.668 9.1 21.138 10 19.668 10H4.66797C3.13797 10 2.66797 9.1 2.66797 8V7C2.66797 5.9 3.13797 5 4.66797 5H19.668C21.138 5 21.668 5.9 21.668 7Z" 
                stroke="currentColor" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
            <path d="M11.8068 4.99945H6.28678C5.94678 4.62945 5.95678 4.05945 6.31678 3.69945L7.73678 2.27945C8.10678 1.90945 8.71678 1.90945 9.08678 2.27945L11.8068 4.99945Z" 
                stroke="currentColor" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
            <path d="M18.0395 4.99945H12.5195L15.2395 2.27945C15.6095 1.90945 16.2195 1.90945 16.5895 2.27945L18.0095 3.69945C18.3695 4.05945 18.3795 4.62945 18.0395 4.99945Z" 
                stroke="currentColor" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
            <path d="M9.10938 10V15.14C9.10938 15.94 9.98938 16.41 10.6594 15.98L11.5994 15.36C11.9394 15.14 12.3694 15.14 12.6994 15.36L13.5894 15.96C14.2494 16.4 15.1394 15.93 15.1394 15.13V10H9.10938Z" 
                stroke="currentColor" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
        <div>Khuyến mãi</div>
    </div>
    <div class="flex flex-col gap-1 items-center justify-center cursor-pointer footer-item <?php echo ($page == 5) ? 'text-custom-blue' : ''; ?>" onclick="goTinTuc()">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="18" height="18" rx="2" />
            <line x1="3" y1="9" x2="21" y2="9" />
            <line x1="3" y1="15" x2="21" y2="15" />
            <line x1="7" y1="3" x2="7" y2="21" />
            <line x1="3" y1="17" x2="7" y2="17" />
        </svg>
        <div>Tin tức</div>
    </div>
    <div class="flex flex-col gap-1 items-center justify-center cursor-pointer footer-item <?php echo ($page == 2) ? 'text-custom-blue' : ''; ?>" onclick="goNotice()">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 18a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2c0-1.38 1-2.5 2-2.5V11a7 7 0 1 1 14 0v4.5c1 0 2 1.12 2 2.5z"/>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
        </svg>
        <div>Thông báo</div>
    </div>
    <div class="flex flex-col gap-1 items-center justify-center cursor-pointer footer-item <?php echo ($page == 3) ? 'text-custom-blue' : ''; ?>" onclick="goTaiKhoan()">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z" 
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            <path d="M20.5901 22C20.5901 18.13 16.7402 15 12.0002 15C7.26015 15 3.41016 18.13 3.41016 22" 
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
        <div>Tài khoản</div>
    </div>
</footer>
<footer class="bg-white border-t text-white p-12 hidden lg:block">
    <div class="container max-w-screen-1200 mx-auto flex">
        <div class="w-1/3 pr-8">
            <div class="flex items-center gap-2 text-3xl font-bold text-custom-blue cursor-pointer pb-4"
                onclick="window.location.href='/'">
                <img src="/public/image/logo.png" alt="logo web" class="w-8 h-8" id="logo">
                <div>TPC Company</div>
            </div>
            <div class="text-black text-xs pb-2">
                <p>Container Trọng Phúc</p>
                <p>CÔNG TY TNHH CƠ KHÍ Ô TÔ VÀ CONTAINER TRỌNG PHÚC </p>
                <p>Địa chỉ văn phòng: M7, khu dân cư Thới An, đường Lê Thị Riêng, phường Thới An, Quận 12, TPHCM</p>
            </div>
            <div class="text-black text-xs pb-3">
                Địa chỉ kho bãi: 797 Lê Thị Riêng, Phường Thới An, Quận 12, TPHCM.
                <br>
                <p>Website: Containertrongphuc.vn</p>
                <p>Số Điện Thoại: 0913888845</p>
                <p>Email: nguyenthanhbinh@containertrongphuc.vn</p>
            </div>
            <div class="text-gray-800 pb-2">Kết nối với chúng tôi</div>
            <div class="flex items-center gap-3 container-mxh">
    
            </div>
        </div>
        <div>
            <div class="flex">
                <div class="pr-16 pl-10">
                    <div class="text-gray-700 pb-2 font-bold">Thông tin và chính sách</div>
                    <div class="text-xs text-black flex flex-col gap-1">
                        <a href="">Mua hàng và thanh toán Online</a>
                        <a href="">Chính sách giao hàng</a>
                        <a href="">Tra thông tin bảo hành</a>
                        <a href="">Tra cứu hoá đơn điện tử</a>
                        <a href="">Thông tin hoá đơn mua hàng</a>
                        <a href="">Thông tin ưu đãi</a>
                    </div>
                </div>
                <div class="pr-16">
                    <div class="text-gray-700 pb-2 font-bold">Dịch vụ và thông tin khác</div>
                    <div class="text-xs text-black flex flex-col gap-1">
                        <a href="">Điều khoản sử dụng</a>
                        <a href="">Chính sách bảo mật thông tin cá nhân</a>
                        <a href="">Ưu đãi thanh toán</a>
                        <a href="">Giới thiệu Trọng Phúc Container</a>
                    </div>
                </div>
                <div>
                    <div class="text-gray-700 pb-2 font-bold">Tổng đài hỗ trợ miễn phí</div>
                    <div class="text-xs text-black flex flex-col gap-1">
                        <span>Gọi mua hàng <b>1800.0008</b> (7h30-23h00)</span>
                        <span>Gọi khiếu nại <b>1800.0007</b> (8h30-23h00)</span>
                        <span>Gọi bảo hành <b>1800.0006</b> (7h30-23h00)</span>
                    </div>
                </div>
            </div>
            <div class="text-black pl-10">
                <div class="pt-5">
                    Đối tác kết hợp
                </div>
                <div class="pt-2 flex flex-wrap gap-10 container-doitac"></div>
            </div>
        </div>
    </div>
</footer>
<script>
    function goNotice() {
        window.location.href = "/notice"
    }

    function goHome() {
        window.location.href = "/"
    }

    function goKhuyenMai() {
        window.location.href = "/promotion"
    }

    function goTinTuc() {
        window.location.href = "/news"
    }

    function goTaiKhoan() {
        window.location.href = "/account"
    }
    
    try{
        function getinfo(){
            let mang_xa_hoi = [];
            let doi_tac = [];

            fetch('/api/system/footer')
            .then(response => response.json())
            .then(data => {
                mang_xa_hoi = data.mang_xa_hoi;
                doi_tac = data.doi_tac;
                const thong_tin_lien_he = data.thong_tin_lien_he;
            })
            .catch(error => {
                console.error('Error fetching items:', error);
            })
            .finally(() => {
                const socialIconsContainer = document.querySelector('.container-mxh');
                mang_xa_hoi.forEach(icon => {
                    const anchor = document.createElement('a');
                    anchor.href = icon.Link;
                    anchor.target = '_blank';
                    anchor.rel = 'noopener noreferrer';

                    const img = document.createElement('img');
                    img.src = icon.Image;
                    img.alt = "mxh";
                    img.classList.add('w-8', 'h-8');
                    anchor.appendChild(img);

                    socialIconsContainer.appendChild(anchor);
                });

                const partnerIconsContainer = document.querySelector('.container-doitac');
                doi_tac.forEach(icon => {
                    const anchor = document.createElement('a');
                    anchor.href = icon.link;
                    anchor.target = '_blank';
                    anchor.rel = 'noopener noreferrer';

                    const img = document.createElement('img');
                    img.src = icon.image;
                    img.alt = icon.Ten;
                    img.classList.add('h-8');
                    anchor.appendChild(img);

                    partnerIconsContainer.appendChild(anchor);
                });

                
            });
        }

        getinfo();
    } catch {}
</script>