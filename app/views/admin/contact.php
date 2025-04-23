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
    <title>Quản lý liên hệ | ADMIN BKSTORE</title>
</head>

<body class="bg-gray-100">
    <div id="confirmModal" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
            <p class="text-lg mb-4 text-center">Bạn có muốn xoá liên hệ này không?</p>
            <div class="flex justify-end gap-4">
                <button id="confirmDelete" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600">Có</button>
                <button id="cancelDelete" class="bg-gray-300 text-gray-800 py-2 px-4 rounded hover:bg-gray-400">Không</button>
            </div>
        </div>
    </div>
    <div id="editContactModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-40">
        <div class="bg-white p-6 rounded-lg w-11/12 lg:w-2/4 z-50 overflow-y-auto" style="max-height: 700px">
            <div class="flex justify-between items-start">
                <h2 class="text-xl font-bold mb-4">Cập nhật liên hệ</h2>
                <button onclick="closeContactModal()">✕</button>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Mã liên hệ:</label>
                    <input type="text" id="idContact" class="mt-2 mb-4 w-full p-2 border rounded" disabled>
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Loại:</label>
                    <input type="text" id="loai" class="mt-2 mb-4 w-full p-2 border rounded">
                </div>
            </div>
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Thông tin liên hệ:</label>
                    <input id="thongtin" class="mt-2 mb-4 w-full p-2 border rounded h-10">
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
                <label for="hinhanh" class="block text-sm font-medium text-gray-700">Hình ảnh:</label>
                <div class="flex flex-wrap gap-4 mt-2" id="imagePreviewContainer">
                    
                    <div id="updateThemAnh" class="relative border-2 border-dashed border-gray-300 w-20 h-20 rounded-md flex items-center justify-center cursor-pointer hover:bg-gray-100 hidden">
                        <input type="file" id="uploadImage" class="opacity-0 absolute inset-0 cursor-pointer" accept="image/*" onchange="handleImageUpload(this)">
                        <span class="text-gray-400">+</span>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-4 pt-6">
                <button onclick="updateContact()" class="bg-green-500 text-white px-4 py-2 rounded">Cập nhật</button>
                <button onclick="deleteContact()" class="bg-red-500 text-white px-8 py-2 rounded">Xoá</button>
                <button onclick="closeContactModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Thoát</button>
            </div>
        </div>
    </div>

    <div id="addContactModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-40">
        <div class="bg-white p-6 rounded-lg w-11/12 lg:w-2/4 z-50 overflow-y-auto" style="max-height: 700px">
            <div class="flex justify-between items-start">
                <h2 class="text-xl font-bold mb-4">Thêm liên hệ</h2>
                <button onclick="closeModalAddContact()">✕</button>
            </div>
           
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Loại:</label>
                    <input type="text" id="themloai" class="mt-2 mb-4 w-full p-2 border rounded" placeholder="Nhập loại">
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Thông tin liên hệ:</label>
                    <input id="themthongtin" class="mt-2 mb-4 w-full p-2 border rounded h-10" placeholder="Nhập thông tin liên hệ">
                </div>
            </div>
            
            <div class="w-full">
                <label for="hinhanh" class="block text-sm font-medium text-gray-700">Hình ảnh:</label>
                <div id="previewImage" class="flex flex-wrap gap-4 mt-2">
                    <div id="updateThemAnhAdd" class="relative border-2 border-dashed border-gray-300 w-20 h-20 rounded-md flex items-center justify-center cursor-pointer hover:bg-gray-100">
                        <input type="file" id="addUploadImage" class="opacity-0 absolute inset-0 cursor-pointer" accept="image/*" onchange="handleImageUploadAdd(this)">
                        <span class="text-gray-400">+</span>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-4 pt-6">
                <button onclick="updateAddContact()" class="bg-green-500 text-white px-4 py-2 rounded">Lưu</button>
                <button onclick="closeModalAddContact()" class="bg-gray-500 text-white px-4 py-2 rounded">Thoát</button>
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
                            <div class="text-gray-500">Quản lý liên hệ</div>
                        </nav>
                        <div class="pb-4">
                            <button class="flex items-center bg-custom-background-bl text-white px-4 py-2 rounded focus:outline-none" onclick="addContact()">
                                <span class="mr-2">+</span> Thêm liên hệ
                            </button>
                        </div>

                        <script>
                            const columnTitles = {
                                id: 'ID',
                                loai: "Loại",
                                thongtin: "Thông tin liên hệ",
                                trangthai: "Trạng thái",
                                action: 'Hành động'
                            };

                            let data = [];

                            function editContact(item) {
                                document.getElementById("editContactModal").classList.remove("hidden");
                                const parseItem= JSON.parse(decodeURIComponent(item));
                                
                                document.getElementById("idContact").value = parseItem.id;
                                document.getElementById("loai").value = parseItem.loai;
                                document.getElementById("thongtin").value = parseItem.thongtin;
                                document.getElementById("trangthai").value = parseItem.trangthai; 
                                
                                const imagePreviewContainer = document.getElementById('imagePreviewContainer');
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

                            function closeContactModal() {
                                document.getElementById("editContactModal").classList.add("hidden");
                                document.getElementById('imagePreviewContainer').innerHTML = `
                                    <div id="updateThemAnh" class="relative border-2 border-dashed border-gray-300 w-20 h-20 rounded-md flex items-center justify-center cursor-pointer hover:bg-gray-100 hidden">
                                        <input type="file" id="uploadImage" class="opacity-0 absolute inset-0 cursor-pointer" accept="image/*" onchange="handleImageUpload(this)">
                                        <span class="text-gray-400">+</span>
                                    </div>
                                `;

                                document.getElementById('updateThemAnh').classList.add('hidden');
                            }

                            function addContact() {
                                document.getElementById("addContactModal").classList.remove("hidden");
                                document.getElementById('updateThemAnhAdd').classList.remove('hidden');
                            }

                            function closeModalAddContact() {
                                document.getElementById("addContactModal").classList.add("hidden");
                            }

                            async function getData() {
                                const response = await fetch(`${window.location.origin}/api/contact`, {
                                    method: 'GET',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    }
                                });

                                if (response.ok) {
                                    const dataContact = await response.json();
                                    dataContact.danh_sach_lien_he.forEach(contact => {
                                        data.push({
                                            id: contact.MaThongTin,
                                            loai: contact.Loai,
                                            thongtin: contact.ThongTin,
                                            trangthai: contact.TrangThai,
                                            hinhanh: contact.Anh,
                                            action: [
                                                { label: 'Cập nhật', class: 'bg-green-500 text-white', onclick: 'editContact' },
                                            ]
                                        });
                                    });

                                    const event = new CustomEvent('dataReady', { detail: dataContact });
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

        function deleteContact(){
            const modal = document.getElementById('confirmModal');
            modal.classList.remove('hidden');

            document.getElementById('confirmDelete').onclick = function() {
                modal.classList.add('hidden');
                const MaContact = Number(document.getElementById("idContact").value.trim());
                if (!/^\d+$/.test(MaContact)) {
                    return notyf.error("ID không hợp lệ. Vui lòng kiểm tra lại.");
                }

                const formData = {
                    MaContact: MaContact
                };
                fetch(`${window.location.origin}/api/contact/delete`, {
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

        async function updateContact() {
            const MaContact = Number(document.getElementById("idContact").value.trim());
            const TrangThai = document.getElementById("trangthai").value.trim();
            const Loai = document.getElementById("loai").value.trim();
            const ThongTin = document.getElementById("thongtin").value.trim();
            const fileInput = document.getElementById("uploadImage");
            const file = fileInput.files[0];

            if (!/^\d+$/.test(MaContact)) {
                return notyf.error("ID không hợp lệ. Vui lòng kiểm tra lại.");
            }

            if (Loai.length > 255) {
                return notyf.error("loại quá dài.");
            }

            if (ThongTin.length > 500) {
                return notyf.error("thông tin quá dài.");
            }

            if (TrangThai !== "Đang hiện" && TrangThai !== "Đang ẩn") {
                return notyf.error("Trạng thái không hợp lệ!");
            }
            
            const formData = new FormData();
            formData.append("MaContact", MaContact);
            formData.append("Loai", Loai);
            formData.append("ThongTin", ThongTin);
            formData.append("TrangThai", TrangThai);
            if (file) {
                formData.append("file", file);
            }

            try {
                const response = await fetch('/api/contact/update', { 
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

        async function updateAddContact() {
            const Loai = document.getElementById("themloai").value.trim();
            const ThongTin = document.getElementById("themthongtin").value.trim();
            const fileInput = document.getElementById("addUploadImage");
            const file = fileInput.files[0];

            if (Loai.length > 255 || Loai.length == 0) {
                return notyf.error("loại không hợp lệ");
            }

            if (ThongTin.length > 500 || ThongTin.length == 0) {
                return notyf.error("thông tin không hợpleej");
            }
            if (!file) {
                return notyf.error("không có hình ảnh");
            }
            
            const formData = new FormData();
            formData.append("Loai", Loai);
            formData.append("ThongTin", ThongTin);
            if (file) {
                formData.append("file", file);
            }

            try {
                const response = await fetch(`${window.location.origin}/api/contact`, {
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

    </script>
</body>

</html>