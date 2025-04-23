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
    <title>Quản lý tin tức | ADMIN BKSTORE</title>
</head>

<body class="bg-gray-100">
    <div id="confirmModal" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
            <p class="text-lg mb-4 text-center">Bạn có muốn xoá tintuc này không?</p>
            <div class="flex justify-end gap-4">
                <button id="confirmDelete" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600">Có</button>
                <button id="cancelDelete" class="bg-gray-300 text-gray-800 py-2 px-4 rounded hover:bg-gray-400">Không</button>
            </div>
        </div>
    </div>
    <div id="editNewsModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-40">
        <div class="bg-white p-6 rounded-lg w-11/12 lg:w-2/4 z-50 overflow-y-auto" style="max-height: 700px">
            <div class="flex justify-between items-start">
                <h2 class="text-xl font-bold mb-4">Cập nhật tin tức</h2>
                <button onclick="closeNewsModal()">✕</button>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Mã tin tức:</label>
                    <input type="text" id="idNews" class="mt-2 mb-4 w-full p-2 border rounded" disabled>
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Tiêu đề:</label>
                    <input type="text" id="tieude" class="mt-2 mb-4 w-full p-2 border rounded">
                </div>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Thời gian tạo:</label>
                    <input id="thoigiantao" class="mt-2 mb-4 w-full p-2 border rounded" disabled>
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Trạng thái:</label>
                    <select id="trangthai" class="mt-2 mb-4 w-full p-2 border rounded h-10">
                        <option value="Đang hiện">Đang hiện</option>
                        <option value="Đang ẩn">Đang ẩn</option>
                    </select>
                </div>
            </div>

            <div class="w-full">
                <label class="block text-sm font-medium text-gray-700">Từ khoá:</label>
                <input id="tukhoa" class="mt-2 mb-4 w-full p-2 border rounded">
            </div>

            <div class="w-full">
                <label class="block text-sm font-medium text-gray-700">Mô tả tiêu đề:</label>
                <input type="text" id="motatintuc" class="mt-2 mb-4 w-full p-2 border rounded" placeholder="Nhập mô tả tiêu đề">
            </div>

            <div class="w-full">
                <label for="mota" class="block text-sm font-medium text-gray-700">Nội dung:</label>
                <textarea id="noidung" class="mt-2 mb-4 w-full p-2 border rounded" rows="3" placeholder="Nhập mô tả"></textarea>
            </div>
            
            <div class="w-full">
                <label for="hinhanh" class="block text-sm font-medium text-gray-700">Hình ảnh:</label>
                <div class="flex flex-wrap gap-4 mt-2" id="imagePreviewContainer">
                    
                    <div class="relative border-2 border-dashed border-gray-300 w-20 h-20 rounded-md flex items-center justify-center cursor-pointer hover:bg-gray-100">
                        <input type="file" id="uploadImage" class="opacity-0 absolute inset-0 cursor-pointer" accept="image/*" onchange="handleImageUpload(this)">
                        <span class="text-gray-400">+</span>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-4 pt-6">
                <button onclick="updateNews()" class="bg-green-500 text-white px-4 py-2 rounded">Cập nhật</button>
                <button onclick="deleteNews()" class="bg-red-500 text-white px-8 py-2 rounded">Xoá</button>
                <button onclick="closeNewsModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Thoát</button>
            </div>
        </div>
    </div>

    <div id="addNewsModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-40">
        <div class="bg-white p-6 rounded-lg w-11/12 lg:w-2/4 z-50 overflow-y-auto" style="max-height: 700px">
            <div class="flex justify-between items-start">
                <h2 class="text-xl font-bold mb-4">Thêm tin tức</h2>
                <button onclick="closeModalAddNews()">✕</button>
            </div>
           
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Tiêu đề:</label>
                    <input type="text" id="themtieude" class="mt-2 mb-4 w-full p-2 border rounded" placeholder="Nhập tiêu đề">
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Từ khoá:</label>
                    <input id="themtukhoa" class="mt-2 mb-4 w-full p-2 border rounded" placeholder="Nhập từ khoá">
                </div>
            </div>

            <div class="w-full">
                <label class="block text-sm font-medium text-gray-700">Mô tả tiêu đề:</label>
                <input type="text" id="themmotatintuc" class="mt-2 mb-4 w-full p-2 border rounded" placeholder="Nhập mô tả tiêu đề">
            </div>
            
            <div class="w-full">
                <label for="mota" class="block text-sm font-medium text-gray-700">Nội dung:</label>
                <textarea id="themnoidung" class="mt-2 mb-4 w-full p-2 border rounded" rows="3" placeholder="Nhập mô tả"></textarea>
            </div>
            
            <div class="w-full">
                <label for="hinhanh" class="block text-sm font-medium text-gray-700">Hình ảnh:</label>
                <div id="previewImage" class="flex flex-wrap gap-4 mt-2">
                    <div class="relative border-2 border-dashed border-gray-300 w-20 h-20 rounded-md flex items-center justify-center cursor-pointer hover:bg-gray-100">
                        <input type="file" id="addUploadImage" class="opacity-0 absolute inset-0 cursor-pointer" accept="image/*" onchange="handleImageUploadAdd(this)">
                        <span class="text-gray-400">+</span>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-4 pt-6">
                <button onclick="updateAddNews()" class="bg-green-500 text-white px-4 py-2 rounded">Lưu</button>
                <button onclick="closeModalAddNews()" class="bg-gray-500 text-white px-4 py-2 rounded">Thoát</button>
            </div>
        </div>
    </div>


    <div class="h-screen block md:flex">
        <?php $page = 9; include './partials/sidebar.php'; ?>
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
                            <div class="text-gray-500">Quản lý tin tức</div>
                        </nav>
                        <div class="pb-4">
                            <button class="flex items-center bg-custom-background-bl text-white px-4 py-2 rounded focus:outline-none" onclick="addNews()">
                                <span class="mr-2">+</span> Thêm tin tức
                            </button>
                        </div>

                        <script>
                            const columnTitles = {
                                id: 'ID',
                                tieude: "Tiêu đề",
                                thoigiantao: "Thời gian tạo",
                                trangthai: "Trạng thái",
                                action: 'Hành động'
                            };

                            let data = [];
                            let AnhMuonXoa = []; 

                            function editNews(item) {
                                document.getElementById("editNewsModal").classList.remove("hidden");
                                const parseItem= JSON.parse(decodeURIComponent(item));
                                
                                document.getElementById("idNews").value = parseItem.id;
                                document.getElementById("tieude").value = parseItem.tieude;
                                document.getElementById("thoigiantao").value = parseItem.thoigiantao;
                                document.getElementById("trangthai").value = parseItem.trangthai; 
                                document.getElementById("tukhoa").value = parseItem.tukhoa;  
                                document.getElementById("noidung").value = parseItem.noidung;              
                                document.getElementById("motatintuc").value = parseItem.mota;              
                            
                                const imagePreviewContainer = document.getElementById('imagePreviewContainer');

                                parseItem.hinhanh.forEach(imageSrc => {
                                    const imageDiv = document.createElement('div');
                                    imageDiv.classList.add('relative', 'border', 'border-gray-300', 'p-1', 'rounded-md');
                                    imageDiv.innerHTML = `
                                        <img src="${imageSrc}" alt="Hình ảnh" class="w-16 h-16 object-cover rounded">
                                        <button class="delete-image absolute top-0.5 right-0.5 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 focus:outline-none">✕</button>
                                    `;
                                    imagePreviewContainer.appendChild(imageDiv);
                                });
                                AnhMuonXoa = parseItem.hinhanh;
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

                            function closeNewsModal() {
                                document.getElementById("editNewsModal").classList.add("hidden");
                                document.getElementById('imagePreviewContainer').innerHTML = `
                                    <div class="relative border-2 border-dashed border-gray-300 w-20 h-20 rounded-md flex items-center justify-center cursor-pointer hover:bg-gray-100">
                                        <input type="file" id="uploadImage" class="opacity-0 absolute inset-0 cursor-pointer" accept="image/*" onchange="handleImageUpload(this)">
                                        <span class="text-gray-400">+</span>
                                    </div>
                                `;
                            }

                            function addNews() {
                                document.getElementById("addNewsModal").classList.remove("hidden");
                            }

                            function closeModalAddNews() {
                                document.getElementById("addNewsModal").classList.add("hidden");
                            }

                            async function getData() {
                                const response = await fetch(`${window.location.origin}/api/news`, {
                                    method: 'GET',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    }
                                });

                                if (response.ok) {
                                    const dataNews = await response.json();
                                    dataNews.danh_sach_tin_tuc.forEach(news => {
                                        data.push({
                                            id: news.MaTinTuc,
                                            tieude: news.TieuDe,
                                            thoigiantao: news.ThoiGianTao,
                                            trangthai: news.TrangThai,
                                            tukhoa: news.TuKhoa,
                                            noidung: news.NoiDung,
                                            hinhanh: news.Anh,
                                            mota: news.MoTa,    
                                            action: [
                                                { label: 'Cập nhật', class: 'bg-green-500 text-white', onclick: 'editNews' },
                                            ]
                                        });
                                    });

                                    const event = new CustomEvent('dataReady', { detail: dataNews });
                                    window.dispatchEvent(event);
                                } else {
                                    console.error("Lỗi khi lấy dữ liệu từ API:", response.status);
                                }
                            }

                            window.onload = async function() {
                                await getData(); 
                            };


                        </script>
                        <?php
                            $title = "Quản lý tin tức";
                            include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/table.php';
                        ?>
                    </main>
                </div>
                <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/admin/partials/footer.php'; ?>
            </div>
        </div>
    </div>
    <script src="/public/js/sidebar.js"></script>
    <script src="/public/js/notyf.min.js"></script>
    <script>
        var notyf = new Notyf({
            duration: 3000,
            position: {
            x: 'right',
            y: 'top',
            },
        });

        function deleteNews(){
            const modal = document.getElementById('confirmModal');
            modal.classList.remove('hidden');

            document.getElementById('confirmDelete').onclick = function() {
                modal.classList.add('hidden');
                const MaTinTuc = Number(document.getElementById("idNews").value.trim());
                if (!/^\d+$/.test(MaTinTuc)) {
                    return notyf.error("ID không hợp lệ. Vui lòng kiểm tra lại.");
                }

                const formData = {
                    MaTinTuc: MaTinTuc
                };
                fetch(`${window.location.origin}/api/news/delete`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        notyf.success(data.message);
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    } else {
                        notyf.error(data.message);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    notyf.error("Không thể kết nối đến server. Vui lòng thử lại sau.");
                });
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

        async function updateNews() {
            const apiUrl = '/api/news/update';
            const MaTinTuc = document.getElementById("idNews").value.trim();
            const TieuDe = document.getElementById("tieude").value.trim();
            const TuKhoa = document.getElementById("themtukhoa").value.trim();
            const MoTaTinTuc = document.getElementById("motatintuc").value.trim();
            const NoiDung = document.getElementById("noidung").value.trim();
            const TrangThai = document.getElementById("trangthai").value.trim();

            const previewContainer = document.getElementById('imagePreviewContainer');
            const images = previewContainer.querySelectorAll('img');
            if (images.length === 0) {
                return notyf.error("Bạn cần tải lên ít nhất một hình ảnh.");
            }

            const formData = new FormData();
            formData.append('TuKhoa', TuKhoa);
            formData.append('MoTaTinTuc', MoTaTinTuc);
            formData.append('NoiDung', NoiDung);
            formData.append('TieuDe', TieuDe);
            formData.append('MaTinTuc', MaTinTuc);
            formData.append('TrangThai', TrangThai);
            
            for (const anh of AnhMuonXoa) {
                formData.append('AnhMuonXoa[]', anh);
            }
            

            for (const img of images) {
                const response = await fetch(img.src);
                const blob = await response.blob();
                formData.append('product[]', blob, `image_${Date.now()}.jpg`);
                formData.append(`MoTaHinhAnh[]`, "");
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
                    notyf.success(result.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);   
                }else{
                    notyf.error(result.message);
                    return
                }
            } catch (error) {
                notyf.error('Xảy ra lỗi khi thêm sản phẩm!');
            }
        }

        async function updateAddNews() {
            const apiUrl = '/api/news';
            const TieuDe = document.getElementById("themtieude").value.trim();
            const TuKhoa = document.getElementById("themtukhoa").value.trim();
            const MoTaTinTuc = document.getElementById("themmotatintuc").value.trim();
            const NoiDung = document.getElementById("themnoidung").value.trim();

            const previewContainer = document.getElementById('previewImage');
            const images = previewContainer.querySelectorAll('img');
            if (images.length === 0) {
                return notyf.error("Bạn cần tải lên ít nhất một hình ảnh.");
            }

            const formData = new FormData();
            formData.append('TuKhoa', TuKhoa);
            formData.append('MoTaTinTuc', MoTaTinTuc);
            formData.append('NoiDung', NoiDung);
            formData.append('TieuDe', TieuDe);

            for (const img of images) {
                const response = await fetch(img.src);
                const blob = await response.blob();
                formData.append('product[]', blob, `image_${Date.now()}.jpg`);
                formData.append(`MoTaHinhAnh[]`, "");
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
                    notyf.success(result.message);
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


    </script>
</body>

</html>