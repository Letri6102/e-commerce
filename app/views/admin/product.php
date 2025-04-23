<?php
require_once dirname(__DIR__, 3) . '/config/db.php';
if(!($_SESSION["Role"] == 'Admin')){
    header("Location: /404");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/public/image/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="/public/css/admin.css">
    <link rel="stylesheet" href="/public/css/notyf.min.css">
    <link href="/public/css/tailwind.min.css" rel="stylesheet">
    <title>Quản lý sản phẩm | ADMIN TPC CONTAINER</title>
</head>

<body class="bg-gray-100">
    <div id="confirmModal" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
            <p class="text-lg mb-4 text-center">Bạn có muốn xoá sản phẩm này không?</p>
            <div class="flex justify-end gap-4">
                <button id="confirmDelete" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600">Có</button>
                <button id="cancelDelete" class="bg-gray-300 text-gray-800 py-2 px-4 rounded hover:bg-gray-400">Không</button>
            </div>
        </div>
    </div>
    <div id="editProductModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-40">
        <div class="bg-white p-6 rounded-lg w-11/12 lg:w-2/4 z-50 overflow-y-auto" style="max-height: 700px">
            <div class="flex justify-between items-start">
                <h2 class="text-xl font-bold mb-4">Cập nhật sản phẩm</h2>
                <button onclick="closeModalProduct()">✕</button>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Mã sản phẩm:</label>
                    <input type="text" id="idProduct" class="mt-2 mb-4 w-full p-2 border rounded" disabled>
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Tên sản phẩm:</label>
                    <input type="text" id="nameProduct" class="mt-2 mb-4 w-full p-2 border rounded">
                </div>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Loại:</label>
                    <select id="theloai" class="mt-2 mb-4 w-full p-2 border rounded">
                    </select>
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Thể tích:</label>
                    <input id="tacgia" class="mt-2 mb-4 w-full p-2 border rounded">
                </div>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Giá:</label>
                    <input id="gia" class="mt-2 mb-4 w-full p-2 border rounded">
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Tỷ lệ giảm giá:</label>
                    <input id="tylegiamgia" class="mt-2 mb-4 w-full p-2 border rounded">
                </div>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Dài - rộng - cao (bên trong):</label>
                    <input id="nxb" class="mt-2 mb-4 w-full p-2 border rounded">
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Dài - rộng - cao (bên ngoài):</label>
                    <input id="namxb" class="mt-2 mb-4 w-full p-2 border rounded">
                </div>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Cửa rộng cao:</label>
                    <input id="kichthuoc" class="mt-2 mb-4 w-full p-2 border rounded">
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Dài - rộng - cao (bên ngoài):</label>
                    <input id="sotrang" class="mt-2 mb-4 w-full p-2 border rounded">
                </div>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Trọng lượng:</label>
                    <input id="hinhthuc" class="mt-2 mb-4 w-full p-2 border rounded">
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Trọng tải:</label>
                    <input id="ngonngu" class="mt-2 mb-4 w-full p-2 border rounded">
                </div>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Từ khoá:</label>
                    <input id="tukhoa" class="mt-2 mb-4 w-full p-2 border rounded">
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Số lượng kho:</label>
                    <input id="soluongkho" class="mt-2 mb-4 w-full p-2 border rounded">
                </div>
            </div>
            <div class="w-full">
                <label for="mota" class="block text-sm font-medium text-gray-700">Mô tả:</label>
                <textarea id="mota" class="mt-2 mb-4 w-full p-2 border rounded" rows="3"></textarea>
            </div>
            <div class="w-full pb-6">
                <label for="hinhanh" class="block text-sm font-medium text-gray-700">Hình ảnh:</label>
                <div class="flex flex-wrap gap-4 mt-2" id="imagePreviewContainer">
                    
                    <div class="relative border-2 border-dashed border-gray-300 w-20 h-20 rounded-md flex items-center justify-center cursor-pointer hover:bg-gray-100">
                        <input type="file" id="uploadImage" class="opacity-0 absolute inset-0 cursor-pointer" accept="image/*" onchange="handleImageUpload(this)">
                        <span class="text-gray-400">+</span>
                    </div>
                </div>
            </div>
            <div class="flex justify-end space-x-4">
                <button onclick="handleUpdateProduct()" class="bg-green-500 text-white px-4 py-2 rounded">Cập nhật</button>
                <button onclick="deleteProduct()" class="bg-red-500 text-white px-8 py-2 rounded">Xoá</button>
                <button onclick="closeModalProduct()" class="bg-gray-500 text-white px-4 py-2 rounded">Thoát</button>
            </div>
        </div>
    </div>

    <div id="addProductModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-40">
        <div class="bg-white p-6 rounded-lg w-11/12 lg:w-2/4 z-50 overflow-y-auto" style="max-height: 700px">
            <div class="flex justify-between items-start">
                <h2 class="text-xl font-bold mb-4">Thêm sản phẩm</h2>
                <button onclick="closeModalAddProduct()">✕</button>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Tên sản phẩm:</label>
                    <input id="themnameProduct" class="mt-2 mb-4 w-full p-2 border rounded" placeholder="Nhập tên sản phẩm">
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Thể tích:</label>
                    <input id="themtacgia" class="mt-2 mb-4 w-full p-2 border rounded" placeholder="Nhập Thể tích">
                </div>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Thể loại:</label>
                    <select id="themtheloai" class="h-10 mt-2 mb-4 w-full p-2 border rounded" onchange="toggleCustomInput()">
                        <option value="other">Khác</option>
                    </select>
                    
                    <input type="text" id="customTheloai" class="h-10 mt-2 mb-4 w-full p-2 border rounded hidden" placeholder="Nhập thể loại khác...">
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Số lượng kho:</label>
                    <input id="themsoluongkho" class="mt-2 mb-4 w-full p-2 border rounded" placeholder="Nhập số lượng kho">
                </div>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Giá:</label>
                    <input id="themgia" class="mt-2 mb-4 w-full p-2 border rounded" placeholder="Nhập giá">
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Tỷ lệ giảm giá:</label>
                    <input id="themtylegiamgia" class="mt-2 mb-4 w-full p-2 border rounded" placeholder="Nhập tỷ lệ giảm giá">
                </div>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Dài - rộng - cao (bên trong):</label>
                    <input id="themnxb" class="mt-2 mb-4 w-full p-2 border rounded" placeholder="Nhập Dài - rộng - cao (bên trong)">
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Dài - rộng - cao (bên ngoài):</label>
                    <input id="themnamxb" class="mt-2 mb-4 w-full p-2 border rounded" placeholder="Nhập Dài - rộng - cao (bên ngoài)">
                </div>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Cửa rộng cao:</label>
                    <input id="themkichthuoc" class="mt-2 mb-4 w-full p-2 border rounded" placeholder="Nhập Cửa rộng cao">
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Số trang:</label>
                    <input id="themsotrang" class="mt-2 mb-4 w-full p-2 border rounded" placeholder="Nhập số trang">
                </div>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Trọng lượng:</label>
                    <input id="themhinhthuc" class="mt-2 mb-4 w-full p-2 border rounded" placeholder="Nhập Trọng lượng">
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Trọng tải:</label>
                    <input id="themngonngu" class="mt-2 mb-4 w-full p-2 border rounded" placeholder="Nhập Trọng tải">
                </div>
            </div>
            <div class="flex gap-2">
                <div class="w-full">
                    <label class="block text-sm font-medium text-gray-700">Từ khoá:</label>
                    <input id="themtukhoa" class="mt-2 mb-4 w-full p-2 border rounded" placeholder="Nhập từ khoá">
                </div>
                
            </div>
            <div class="w-full">
                <label for="mota" class="block text-sm font-medium text-gray-700">Mô tả:</label>
                <textarea id="themmota" class="mt-2 mb-4 w-full p-2 border rounded" rows="3" placeholder="Nhập mô tả"></textarea>
            </div>
            <div class="w-full pb-6">
                <label for="hinhanh" class="block text-sm font-medium text-gray-700">Hình ảnh:</label>
                <div id="previewImage" class="flex flex-wrap gap-4 mt-2">
                    
                    <div class="relative border-2 border-dashed border-gray-300 w-20 h-20 rounded-md flex items-center justify-center cursor-pointer hover:bg-gray-100">
                        <input type="file" id="uploadImage" class="opacity-0 absolute inset-0 cursor-pointer" accept="image/*" onchange="handleImageUploadAdd(this)">
                        <span class="text-gray-400">+</span>
                    </div>
                </div>
            </div>
            <div class="flex justify-end space-x-4">
                <button onclick="handleSaveProduct()" class="bg-green-500 text-white px-4 py-2 rounded">Cập nhật</button>
                <button onclick="closeModalAddProduct()" class="bg-gray-500 text-white px-4 py-2 rounded">Thoát</button>
            </div>
        </div>
    </div>

    <div class="h-screen block md:flex">
        <?php $page = 3; include './partials/sidebar.php'; ?>
        <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden"></div>
        <div class="flex-1 overflow-x-hidden">
            <div class="flex flex-col">
                <div class="bg-white fixed top-0 left-0 right-0 z-10">
                    <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/admin/partials/header.php'; ?>
                </div>
                <div class="overflow-y-auto overflow-x-hidden pt-16">
                    <main class="container mx-auto min-h-screen px-4 py-4">
                        <nav class="flex gap-2 text pb-4 text-sm text-gray-700">
                            <a href="/" class="cursor-pointer hover:text-blue-500 focus:outline-none">BkStore.Vn</a>
                            <div>&rsaquo;</div>
                            <a href="/admin" class="cursor-pointer hover:text-blue-500 focus:outline-none">Admin</a>
                            <div>&rsaquo;</div>
                            <div class="text-gray-500">Quản lý sản phẩm</div>
                        </nav>
                        <div class="pb-4">
                            <button class="flex items-center bg-custom-background-bl text-white px-4 py-2 rounded focus:outline-none" onclick="addProduct()">
                                <span class="mr-2">+</span> Thêm sản phẩm
                            </button>
                        </div>

                        <script>
                            const columnTitles = {
                                id: 'ID',
                                name: 'Tên sản phẩm',
                                theloai: 'Thể loại',
                                tacgia: "Thể tích",
                                price: 'Giá',
                                tylegiamgia: 'Giảm giá',
                                action: "Hành động"
                            };

                            let data = []
                            let categorys = []
                            
                            function clearImages() {
                                const imagePreviewContainer = document.getElementById('imagePreviewContainer');

                                if (imagePreviewContainer) {
                                    const imageDivs = imagePreviewContainer.querySelectorAll('div.relative.border.border-gray-300.p-1.rounded-md');
                                    imageDivs.forEach(imageDiv => {
                                        imageDiv.remove();
                                    });
                                } else {
                                    console.error("Không tìm thấy phần tử với id 'imagePreviewContainer'");
                                }
                            }

                            function editProduct(item) {
                                document.getElementById("editProductModal").classList.remove("hidden");
                                clearImages()
                                const parseItem= JSON.parse(decodeURIComponent(item));

                                document.getElementById("idProduct").value = parseItem.id;
                                document.getElementById("nameProduct").value = parseItem.name;
                                document.getElementById("theloai").value = parseItem.theloai; 
                                document.getElementById("tacgia").value = parseItem.tacgia; 
                                document.getElementById("gia").value = parseItem.price; 
                                document.getElementById("tylegiamgia").value = parseItem.tylegiamgia; 
                                document.getElementById("nxb").value = parseItem.nxb; 
                                document.getElementById("namxb").value = parseItem.namxb; 
                                document.getElementById("sotrang").value = parseItem.sotrang; 
                                document.getElementById("hinhthuc").value = parseItem.hinhthuc; 
                                document.getElementById("ngonngu").value = parseItem.ngonngu; 
                                document.getElementById("tukhoa").value = parseItem.tukhoa; 
                                document.getElementById("soluongkho").value = parseItem.soluongkho; 
                                document.getElementById("mota").value = parseItem.mota; 
                                document.getElementById("kichthuoc").value = parseItem.kichthuoc; 

                                const imagePreviewContainer = document.getElementById('imagePreviewContainer');

                                parseItem.hinhanh.forEach(imageSrc => {
                                    const imageDiv = document.createElement('div');
                                    imageDiv.classList.add('relative', 'border', 'border-gray-300', 'p-1', 'rounded-md');
                                    imageDiv.innerHTML = `
                                        <img src="/${imageSrc}" alt="Hình ảnh" class="w-16 h-16 object-cover rounded">
                                        <button class="delete-image absolute top-0.5 right-0.5 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 focus:outline-none">✕</button>
                                    `;
                                    imagePreviewContainer.appendChild(imageDiv);
                                });

                                attachDeleteEvent();
                            }

                            function attachDeleteEvent() {
                                const deleteButtons = document.querySelectorAll('.delete-image');
                                deleteButtons.forEach(button => {
                                    button.addEventListener('click', function () {
                                        const imageDiv = this.parentElement;
                                        imageDiv.remove();
                                    });
                                });
                            }

                            function closeModalProduct() {
                                document.getElementById("editProductModal").classList.add("hidden");
                            }

                            function addProduct() {
                                document.getElementById("addProductModal").classList.remove("hidden");
                            }

                            function closeModalAddProduct() {
                                document.getElementById("addProductModal").classList.add("hidden");
                            }

                            function toggleCustomInput() {
                                const select = document.getElementById("themtheloai");
                                const customInput = document.getElementById("customTheloai");
                                
                                if (select.value === "other") {
                                    customInput.classList.remove("hidden"); 
                                    customInput.focus();
                                } else {
                                    customInput.classList.add("hidden");
                                    customInput.value = ""; 
                                }
                            }

                            async function getItems() {
                                const response = await fetch(`/api/admin/product`, {
                                    method: 'GET',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    }
                                });

                                if (response.ok) {
                                    const dataItems = await response.json();
                                    if(!dataItems.success){
                                        if(dataItems.message === "Người dùng chưa đăng nhập"){
                                            window.location.href = '/auth/login'
                                            return
                                        }else if(dataItems.message === "Không có quyền truy cập"){
                                            window.location.href = '/404'
                                            return
                                        }
                                    }

                                    categorys = dataItems['product_category']
                                    const selectElement = document.getElementById("themtheloai");
                                    categorys.forEach((option, index) => {
                                        const optionElement = document.createElement("option");
                                        optionElement.value = option;
                                        optionElement.textContent = option;
                                        if (index === categorys.length - 1) {
                                            optionElement.selected = true;
                                        }
                                        selectElement.prepend(optionElement);
                                    });

                                    const selectElementEdit = document.getElementById("theloai");
                                    categorys.forEach((option, index) => {
                                        const optionElement = document.createElement("option");
                                        optionElement.value = option;
                                        optionElement.textContent = option;
                                        selectElementEdit.prepend(optionElement);
                                    });

                                    dataItems['product_list'].forEach(item => {
                                        data.push({
                                            id: item.id,
                                            name: item.ten,
                                            theloai: item.thong_tin_chi_tiet.the_loai,
                                            tacgia: item.thong_tin_chi_tiet.tac_gia,
                                            price: item.gia_san_pham,
                                            tylegiamgia: parseFloat(item.ty_le_giam_gia) / 100,
                                            nxb: item.thong_tin_chi_tiet.nha_xuat_ban,
                                            namxb: item.thong_tin_chi_tiet.nam_xuat_ban,
                                            sotrang: item.thong_tin_chi_tiet.so_trang,
                                            hinhthuc: item.thong_tin_chi_tiet.hinh_thuc,
                                            tukhoa: item.thong_tin_chi_tiet.tu_khoa,
                                            ngonngu: item.thong_tin_chi_tiet.ngon_ngu,
                                            soluongkho: item.so_luong_ton_kho,
                                            mota: item.mo_ta,
                                            kichthuoc: item.thong_tin_chi_tiet.kich_thuoc,
                                            hinhanh: item.hinh,
                                            action: [
                                                { label: 'Cập nhật', class: 'bg-green-500 text-white', onclick: 'editProduct' },
                                            ]
                                        });
                                    });

                                    const event = new CustomEvent('dataReady', { detail: dataItems });
                                    window.dispatchEvent(event);
                                } else {
                                    console.error("Lỗi khi lấy dữ liệu từ API:", response.status);
                                }
                            }

                            window.onload = async function() {
                                await getItems(); 
                            };

                        </script>
                        <?php
                            $title = "Quản lý sản phẩm";
                            include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/table.php';
                        ?>
                    </main>
                </div>
                <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/admin/partials/footer.php'; ?>
            </div>
        </div>
    </div>
    <script src="/public/js/notyf.min.js"></script>
    <script src="/public/js/sidebar.js"></script>
    <script>
        var notyf = new Notyf({
            duration: 3000,
            position: {
            x: 'right',
            y: 'top',
            },
        });


        function deleteProduct(){
            const modal = document.getElementById('confirmModal');
            modal.classList.remove('hidden');

            const id = document.getElementById('idProduct').value

            document.getElementById('confirmDelete').onclick = function() {
                hdelete()

                modal.classList.add('hidden');
            }

            document.getElementById('cancelDelete').onclick = function() {
                modal.classList.add('hidden');
            }
            
        }

        function handleImageUploadAdd(input) {
            const previewContainer = document.getElementById('previewImage');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    const imageWrapper = document.createElement('div');
                    imageWrapper.className = "relative border border-gray-300 p-1 rounded-md";

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = "Uploaded Image";
                    img.className = "w-16 h-16 object-cover rounded";

                    const removeButton = document.createElement('button');
                    removeButton.className = "absolute top-0.5 right-0.5 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 focus:outline-none";
                    removeButton.innerHTML = "✕";

                    removeButton.onclick = function () {
                        previewContainer.removeChild(imageWrapper);
                    };

                    imageWrapper.appendChild(img);
                    imageWrapper.appendChild(removeButton);

                    previewContainer.appendChild(imageWrapper);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        function handleImageUpload(input) {
            const previewContainer = document.getElementById('imagePreviewContainer');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    const imageWrapper = document.createElement('div');
                    imageWrapper.className = "relative border border-gray-300 p-1 rounded-md";

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = "Uploaded Image";
                    img.className = "w-16 h-16 object-cover rounded";

                    const removeButton = document.createElement('button');
                    removeButton.className = "absolute top-0.5 right-0.5 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 focus:outline-none";
                    removeButton.innerHTML = "✕";

                    removeButton.onclick = function () {
                        previewContainer.removeChild(imageWrapper);
                    };

                    imageWrapper.appendChild(img);
                    imageWrapper.appendChild(removeButton);

                    previewContainer.appendChild(imageWrapper);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        async function handleSaveProduct() {
            const apiUrl = '/api/admin/product';

            const name = document.getElementById('themnameProduct').value.trim();
            const author = document.getElementById('themtacgia').value.trim();
            const category = document.getElementById('themtheloai').value.trim();
            const customCategory = document.getElementById('customTheloai').value.trim();
            const quantity = document.getElementById('themsoluongkho').value.trim();
            const price = document.getElementById('themgia').value.trim();
            const discount = document.getElementById('themtylegiamgia').value.trim();
            const publisher = document.getElementById('themnxb').value.trim();
            const year = document.getElementById('themnamxb').value.trim();
            const size = document.getElementById('themkichthuoc').value.trim();
            const pages = document.getElementById('themsotrang').value.trim();
            const format = document.getElementById('themhinhthuc').value.trim();
            const language = document.getElementById('themngonngu').value.trim();
            const keywords = document.getElementById('themtukhoa').value.trim();
            const description = document.getElementById('themmota').value.trim();

            const finalCategory = category === 'other' ? customCategory : category;

            let errorMessage = "";

            if (!name) {
                notyf.error('Tên sản phẩm không được để trống.');
                return false;
            }
            if (!author) {
                notyf.error("Thể tích không được để trống.");
                return false;
            }
            if (!finalCategory) {
                notyf.error("Thể loại không được để trống.");
                return false;
            }
            if (!quantity || isNaN(quantity) || parseInt(quantity) <= 0) {
                notyf.error("Số lượng phải là số nguyên dương.");
                return false;
            }
            if (!price || isNaN(price) || parseFloat(price) <= 0) {
                notyf.error("Giá phải là số lớn hơn 0.");
                return false;
            }
            if (discount && (isNaN(discount) || parseFloat(discount) < 0 || parseFloat(discount) > 1)) {
                notyf.error("Tỷ lệ giảm giá phải nằm trong khoảng 0 - 1");
                return false;
            }

            if (!publisher) {
                notyf.error("Dài - rộng - cao (bên trong) không được để trống.");
                return false;
            }
            if (!year || isNaN(year) || parseInt(year) < 1000 || parseInt(year) > new Date().getFullYear()) {
                notyf.error("Dài - rộng - cao (bên ngoài) phải hợp lệ.");
                return false;
            }
            if (pages && (isNaN(pages) || parseInt(pages) <= 0)) {
                notyf.error("Số trang phải là số nguyên dương.");
                return false;
            }

            const previewContainer = document.getElementById('previewImage');
            const images = previewContainer.querySelectorAll('img');
            if (images.length === 0) {
                notyf.error("Bạn cần tải lên ít nhất một hình ảnh.");
                return false;
            }

            const formData = new FormData();
            formData.append('TenSp', name);
            formData.append('TacGia', author);
            formData.append('PhanLoai', finalCategory);
            formData.append('SoLuongKho', quantity);
            formData.append('Gia', price);
            formData.append('TyLeGiamGia', discount);
            formData.append('NXB', publisher);
            formData.append('NamXB', year);
            formData.append('KichThuoc', size);
            formData.append('SoTrang', pages);
            formData.append('HinhThuc', format);
            formData.append('NgonNgu', language);
            formData.append('TuKhoa', keywords);
            formData.append('MoTa', description);

            for (const img of images) {
                const response = await fetch(img.src);
                const blob = await response.blob();
                formData.append('product[]', blob, `image_${Date.now()}.jpg`);
            }

            try {
                const response = await fetch(apiUrl, {
                    method: 'POST',
                    body: formData,
                });

                if (!response.ok) {
                    notyf.error('Xảy ra lỗi khi thêm sản phẩm!');
                    return
                }

                const result = await response.json();
                if(result['success']){
                    notyf.success('Thêm sản phẩm thành công!');
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);   
                }else{
                    notyf.error('Thêm sản phẩm thất bại!');
                    return
                }
            } catch (error) {
                notyf.error('Xảy ra lỗi khi thêm sản phẩm!');
            }
        }

        async function handleUpdateProduct() {
            const apiUrl = '/api/admin/updateProduct';

            const idsp = document.getElementById('idProduct').value.trim();
            const name = document.getElementById('nameProduct').value.trim();
            const author = document.getElementById('tacgia').value.trim();
            const category = document.getElementById('theloai').value.trim();
            const quantity = document.getElementById('soluongkho').value.trim();
            const price = document.getElementById('gia').value.trim();
            const discount = document.getElementById('tylegiamgia').value.trim();
            const publisher = document.getElementById('nxb').value.trim();
            const year = document.getElementById('namxb').value.trim();
            const size = document.getElementById('kichthuoc').value.trim();
            const pages = document.getElementById('sotrang').value.trim();
            const format = document.getElementById('hinhthuc').value.trim();
            const language = document.getElementById('ngonngu').value.trim();
            const keywords = document.getElementById('tukhoa').value.trim();
            const description = document.getElementById('mota').value.trim();

            let errorMessage = "";

            if (!idsp) {
                notyf.error('Không tìm thấy ID sản phẩm.');
                return false;
            }
            if (!name) {
                notyf.error('Tên sản phẩm không được để trống.');
                return false;
            }
            if (!author) {
                notyf.error("Thể tích không được để trống.");
                return false;
            }
            if (!category) {
                notyf.error("Thể loại không được để trống.");
                return false;
            }
            if (!quantity || isNaN(quantity) || parseInt(quantity) <= 0) {
                notyf.error("Số lượng phải là số nguyên dương.");
                return false;
            }
            if (!price || isNaN(price) || parseFloat(price) <= 0) {
                notyf.error("Giá phải là số lớn hơn 0.");
                return false;
            }
            if (discount && (isNaN(discount) || parseFloat(discount) < 0 || parseFloat(discount) > 1)) {
                notyf.error("Tỷ lệ giảm giá phải nằm trong khoảng 0 - 1");
                return false;
            }

            if (!publisher) {
                notyf.error("Dài - rộng - cao (bên trong) không được để trống.");
                return false;
            }
            if (!year || isNaN(year) || parseInt(year) < 1000 || parseInt(year) > new Date().getFullYear()) {
                notyf.error("Dài - rộng - cao (bên ngoài) phải hợp lệ.");
                return false;
            }
            if (pages && (isNaN(pages) || parseInt(pages) <= 0)) {
                notyf.error("Số trang phải là số nguyên dương.");
                return false;
            }

            const previewContainer = document.getElementById('imagePreviewContainer');
            const images = previewContainer.querySelectorAll('img');
            if (images.length === 0) {
                notyf.error("Bạn cần tải lên ít nhất một hình ảnh.");
                return false;
            }

            const formData = new FormData();
            formData.append('ID_SP', idsp);
            formData.append('TenSp', name);
            formData.append('TacGia', author);
            formData.append('PhanLoai', category);
            formData.append('SoLuongKho', quantity);
            formData.append('Gia', price);
            formData.append('TyLeGiamGia', discount);
            formData.append('NXB', publisher);
            formData.append('NamXB', year);
            formData.append('KichThuoc', size);
            formData.append('SoTrang', pages);
            formData.append('HinhThuc', format);
            formData.append('NgonNgu', language);
            formData.append('TuKhoa', keywords);
            formData.append('MoTa', description);

            for (const img of images) {
                const response = await fetch(img.src);
                const blob = await response.blob();
                formData.append('product[]', blob, `image_${Date.now()}.jpg`);
            }

            try {
                const response = await fetch(apiUrl, {
                    method: 'POST',
                    body: formData,
                });

                if (!response.ok) {
                    notyf.error('Xảy ra lỗi khi cập nhật sản phẩm!');
                    return
                }

                const result = await response.json();
                if(result['success']){
                    notyf.success('Cập nhật sản phẩm thành công!');
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);   
                }else{
                    notyf.error('Cập nhật sản phẩm thất bại!');
                    return
                }
            } catch (error) {
                notyf.error('Xảy ra lỗi khi cập nhật sản phẩm!');
            }
        }

        function hdelete(){
            const idSocial = document.getElementById("idProduct").value;

            if(!idSocial){
                notyf.error("Không tìm thấy mã sản phẩm!")
                return;
            }

            fetch('/api/admin/deleteProduct', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    ID_SP: idSocial
                }),
            })
            .then(response => {
                if (!response.ok) {
                    notyf.error("Đã xảy ra lỗi khi xoá sản phẩm")
                    return false;
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    notyf.success("Xoá sản phẩm thành công!")
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    notyf.error("Xoá sản phẩm thất bại!")
                }
            })
            .catch(error => {
                notyf.error("Đã xảy ra lỗi khi xoá sản phẩm")
            });


        }


    </script>
</body>

</html>