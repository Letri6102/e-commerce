<?php
error_reporting(0);
session_start();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/public/image/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="/public/css/admin.css">
    <link href="/public/css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/notyf.min.css">
    <title>Quản lý đối tác | ADMIN BKSTORE</title>
</head>

<body class="bg-gray-100">
    <div id="confirmModal" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
            <p class="text-lg mb-4 text-center">Bạn có muốn xoá đối tác này không?</p>
            <div class="flex justify-end gap-4">
                <button id="confirmDelete" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600">Có</button>
                <button id="cancelDelete" class="bg-gray-300 text-gray-800 py-2 px-4 rounded hover:bg-gray-400">Không</button>
            </div>
        </div>
    </div>
    <div id="editPartnerModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-40">
        <div class="bg-white p-6 rounded-lg w-11/12 lg:w-2/4 z-50 overflow-y-auto" style="max-height: 700px">
            <div class="flex justify-between items-start">
                <h2 class="text-xl font-bold mb-4">Cập nhật đối tác</h2>
                <button onclick="closePartnerModal()">✕</button>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Mã mạng xã hội:</label>
                    <input type="text" id="idPartner" class="mt-2 mb-4 w-full p-2 border rounded" disabled>
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Tên đối tác:</label>
                    <input type="text" id="namePartner" class="mt-2 mb-4 w-full p-2 border rounded">
                </div>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Trạng thái:</label>
                    <select id="trangthai" class="mt-2 mb-4 w-full p-2 border rounded h-10">
                        <option value="Đang hiện">Đang hiện</option>
                        <option value="Đang ẩn">Đang ẩn</option>
                    </select>
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Liên kết:</label>
                    <input id="lienket" class="mt-2 mb-4 w-full p-2 border rounded h-10">
                </div>
            </div>

            <div class="w-full">
                <label for="hinhanh" class="block text-sm font-medium text-gray-700">Hình ảnh:</label>
                <div class="flex flex-wrap gap-4 mt-2" id="imageUpdate">
                    <div id="updateThemAnh"  class="relative border-2 border-dashed border-gray-300 w-20 h-20 rounded-md flex items-center justify-center cursor-pointer hover:bg-gray-100 hidden">
                        <input type="file" id="uploadImage" class="opacity-0 absolute inset-0 cursor-pointer" accept="image/*" onchange="handleImageUpload(this)">
                        <span class="text-gray-400">+</span>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-4 pt-6">
                <button onclick="handleupdatePartner()" class="bg-green-500 text-white px-4 py-2 rounded">Cập nhật</button>
                <button onclick="deleteProduct()" class="bg-red-500 text-white px-8 py-2 rounded">Xoá</button>
                <button onclick="closePartnerModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Thoát</button>
            </div>
        </div>
    </div>

    <div id="addPartnerModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-40">
        <div class="bg-white p-6 rounded-lg w-11/12 lg:w-2/4 z-50 overflow-y-auto" style="max-height: 700px">
            <div class="flex justify-between items-start">
                <h2 class="text-xl font-bold mb-4">Thêm đối tác</h2>
                <button onclick="closeModalAddPartner()">✕</button>
            </div>
           
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Tên đối tác:</label>
                    <input type="text" id="themnamePartner" class="mt-2 mb-4 w-full p-2 border rounded" placeholder="Nhập tên đối tác">
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Liên kết:</label>
                    <input id="themlienket" class="mt-2 mb-4 w-full p-2 border rounded h-10" placeholder="Nhập liên kết">
                </div>
            </div>
            
            <div class="w-full">
                <label for="hinhanh" class="block text-sm font-medium text-gray-700">Hình ảnh:</label>
                <div class="flex flex-wrap gap-4 mt-2" id="imageAdd">
                    <div id="updateThemAnhAdd" class="relative border-2 border-dashed border-gray-300 w-20 h-20 rounded-md flex items-center justify-center cursor-pointer hover:bg-gray-100">
                        <input type="file" class="opacity-0 absolute inset-0 cursor-pointer" accept="image/*" onchange="handleImageUploadAdd(this)">
                        <span class="text-gray-400">+</span>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-4 pt-6">
                <button onclick="hadd()" class="bg-green-500 text-white px-4 py-2 rounded">Lưu</button>
                <button onclick="closeModalAddPartner()" class="bg-gray-500 text-white px-4 py-2 rounded">Thoát</button>
            </div>
        </div>
    </div>


    <div class="h-screen block md:flex">
        <?php $page = 10; include './partials/sidebar.php'; ?>
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
                            <div class="text-gray-500">Quản lý đối tác</div>
                        </nav>
                        <div class="pb-4">
                            <button class="flex items-center bg-custom-background-bl text-white px-4 py-2 rounded focus:outline-none" onclick="addPartner()">
                                <span class="mr-2">+</span> Thêm đối tác
                            </button>
                        </div>

                        <script>
                            const columnTitles = {
                                id: 'ID',
                                tendoitac: "Tên đối tác",
                                lienket: "Liên kết", 
                                trangthai: "Trạng thái",
                                action: 'Hành động'
                            };

                            let data = [];

                            function editPartner(item) {
                                document.getElementById("editPartnerModal").classList.remove("hidden");
                                const parseItem= JSON.parse(decodeURIComponent(item));
                                
                                document.getElementById("idPartner").value = parseItem.id;
                                document.getElementById("namePartner").value = parseItem.tendoitac;    
                                document.getElementById("lienket").value = parseItem.lienket;
                                document.getElementById("trangthai").value = parseItem.trangthai;
                                
                                const imagePreviewContainer = document.getElementById('imageUpdate');

                                if(parseItem.hinhanh){
                                    const imageDiv = document.createElement('div');
                                    imageDiv.classList.add('relative', 'border', 'border-gray-300', 'p-1', 'rounded-md');
                                    imageDiv.innerHTML = `
                                        <img src="${parseItem.hinhanh}" alt="Hình ảnh" class="w-16 h-16 object-cover rounded">
                                        <button class="delete-image absolute top-0.5 right-0.5 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 focus:outline-none">✕</button>
                                    `;
                                    imagePreviewContainer.appendChild(imageDiv);
                                }else{
                                    document.getElementById('updateThemAnh').classList.remove('hidden');
                                }

                                attachDeleteEvent();
                            }

                            function attachDeleteEvent() {
                                const deleteButtons = document.querySelectorAll('.delete-image');
                                deleteButtons.forEach(button => {
                                    button.addEventListener('click', function () {
                                        const imageDiv = this.parentElement;
                                        imageDiv.remove();

                                        const addImageButton = document.getElementById('updateThemAnh');
                                        addImageButton.classList.remove('hidden');
                                    });
                                });
                            }

                            function closePartnerModal() {
                                document.getElementById("editPartnerModal").classList.add("hidden");
                                
                                const imagePreviewContainer = document.getElementById('imageUpdate');
                                const images = imagePreviewContainer.querySelectorAll('img');
                                images.forEach(image => image.parentElement.remove());

                                document.getElementById('updateThemAnh').classList.add('hidden');
                            }

                            function addPartner() {
                                document.getElementById("addPartnerModal").classList.remove("hidden");
                                document.getElementById('updateThemAnhAdd').classList.remove('hidden');
                            }

                            function closeModalAddPartner() {
                                document.getElementById("addPartnerModal").classList.add("hidden");

                                const imagePreviewContainer = document.getElementById('imageAdd');
                                const images = imagePreviewContainer.querySelectorAll('img');
                                images.forEach(image => image.parentElement.remove());


                                const fileInput = document.querySelector('#updateThemAnhAdd input[type="file"]');
                                fileInput.value = "";
                            }

                            async function getItems() {
                                const response = await fetch(`/api/partner`, {
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

                                    dataItems.danh_sach_doi_tac.forEach(item => {
                                        data.push({
                                            id: item.MaDoiTac,
                                            tendoitac: item.Ten,
                                            lienket: item.link,
                                            hinhanh: item.image,
                                            trangthai: item.TrangThai,
                                            action: [
                                                { label: 'Cập nhật', class: 'bg-green-500 text-white', onclick: 'editPartner' },
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
                            $title = "Quản lý liên hệ";
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

        function handleImageUpload(input) {
            const previewContainer = document.getElementById('imageUpdate');

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

                        const fileInput = document.querySelector('#updateThemAnh input[type="file"]');
                        fileInput.value = "";

                        document.getElementById('updateThemAnh').classList.remove('hidden');
                    };

                    imageWrapper.appendChild(img);
                    imageWrapper.appendChild(removeButton);

                    previewContainer.appendChild(imageWrapper);
                };

                reader.readAsDataURL(input.files[0]);

                document.getElementById('updateThemAnh').classList.add('hidden');
            }
        }

        function handleImageUploadAdd(input) {
            const previewContainer = document.getElementById('imageAdd');

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

                        const fileInput = document.querySelector('#updateThemAnhAdd input[type="file"]');
                        fileInput.value = "";

                        document.getElementById('updateThemAnhAdd').classList.remove('hidden');
                    };

                    imageWrapper.appendChild(img);
                    imageWrapper.appendChild(removeButton);

                    previewContainer.appendChild(imageWrapper);
                };

                reader.readAsDataURL(input.files[0]);
                document.getElementById('updateThemAnhAdd').classList.add('hidden');
            }
        }

        async function handleupdatePartner() {
            const apiUrl = '/api/partner/update';

            const idPartner = document.getElementById('idPartner').value.trim();
            const namePartner = document.getElementById('namePartner').value.trim();
            const trangthai = document.getElementById('trangthai').value.trim();
            const lienket = document.getElementById('lienket').value.trim();


            if(!idPartner || !namePartner || !trangthai || !lienket){
                notyf.error("Vui lòng nhập đầy đủ thông tin!");
                return false;
            }
            
            const previewContainer = document.getElementById('imageUpdate');
            const images = previewContainer.querySelectorAll('img');
            if (images.length === 0) {
                notyf.error("Bạn cần tải lên ít nhất một hình ảnh.");
                return false;
            }

            const formData = new FormData();
            formData.append('MaDoiTac', idPartner);
            formData.append('Ten', namePartner);
            formData.append('LienKet', lienket);
            formData.append('TrangThai', trangthai);

            for (const img of images) {
                const response = await fetch(img.src);
                const blob = await response.blob();
                formData.append('file', blob, `image_${Date.now()}.jpg`);
            }

            try {
                const response = await fetch(apiUrl, {
                    method: 'POST',
                    body: formData,
                });

                if (!response.ok) {
                    notyf.error('Xảy ra lỗi khi cập nhật đối tác!');
                    return
                }

                const result = await response.json();
                if(result['success']){
                    notyf.success('Cập nhật đối tác thành công!');
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);   
                }else{
                    notyf.error('Cập nhật đối tác thất bại!');
                    return
                }
            } catch (error) {
                notyf.error('Xảy ra lỗi khi cập nhật đối tác!');
                console.log(error)
            }
        }

        function hdelete(){
            const MaDoiTac = document.getElementById("idPartner").value;

            if(!MaDoiTac){
                notyf.error("Không tìm thấy mã đối tác!")
                return;
            }

            fetch('/api/partner/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    MaDoiTac: MaDoiTac
                }),
            })
            .then(response => {
                if (!response.ok) {
                    notyf.error("Đã xảy ra lỗi khi xoá đối tác")
                    return false;
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    notyf.success("Xoá đối tác thành công!")
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    notyf.error("Xoá đối tác thất bại!")
                }
            })
            .catch(error => {
                notyf.error("Đã xảy ra lỗi khi xoá đối tác")
            });


        }

        function isValidURL(string) {
            const regex = /^(https?:\/\/)?([a-zA-Z0-9\-]+\.)+[a-zA-Z]{2,}(:\d+)?(\/.*)?$/;
            return regex.test(string);
        }


        async function hadd() {
            const apiUrl = '/api/partner';

            const namePartner = document.getElementById('themnamePartner').value.trim();
            const lienket = document.getElementById('themlienket').value.trim();


            if( !namePartner || !lienket){
                notyf.error("Vui lòng nhập đầy đủ thông tin!");
                return false;
            }

            if(!isValidURL(lienket)){
                notyf.error("Vui lòng nhập liên kết hợp lệ!");
                return false;
            }
            
            const previewContainer = document.getElementById('imageAdd');
            const images = previewContainer.querySelectorAll('img');
            if (images.length === 0) {
                notyf.error("Bạn cần tải lên ít nhất một hình ảnh.");
                return false;
            }

            const formData = new FormData();
            formData.append('Ten', namePartner);
            formData.append('LienKet', lienket);

            for (const img of images) {
                const response = await fetch(img.src);
                const blob = await response.blob();
                formData.append('file', blob, `image_${Date.now()}.jpg`);
            }

            try {
                const response = await fetch(apiUrl, {
                    method: 'POST',
                    body: formData,
                });

                if (!response.ok) {
                    notyf.error('Xảy ra lỗi khi thêm đối tác!');
                    return
                }

                const result = await response.json();
                if(result['success']){
                    notyf.success('Thêm đối tác thành công!');
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);   
                }else{
                    notyf.error('Thêm đối tác thất bại!');
                    return
                }
            } catch (error) {
                notyf.error('Xảy ra lỗi khi thêm đối tác!');
                console.log(error)
            }
        }

        function deleteProduct(){
            const modal = document.getElementById('confirmModal');
            modal.classList.remove('hidden');

            document.getElementById('confirmDelete').onclick = function() {
                hdelete()

                modal.classList.add('hidden');
            }

            document.getElementById('cancelDelete').onclick = function() {
                modal.classList.add('hidden');
            }
            
        }

    </script>
</body>

</html>