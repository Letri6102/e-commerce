<?php
require_once dirname(__DIR__, 3) . '/config/db.php';

if($TrangThaiBaoTri && $_SESSION['Role'] != 'Admin'){
    header("Location: /maintain");
    exit;
}

$type = $_GET['type'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/public/image/logo.png" type="image/x-icon">
    <link href="/public/css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/client.css">
    <link rel="stylesheet" href="/public/css/notyf.min.css">
    <title>Thông tin đặt hàng | BKSTORE</title>
</head>

<body class="bg-gray-100">
    <!-- <div id="loading" class="fixed inset-0 flex items-center justify-center bg-white z-50">
        <div class="relative w-14 h-14">
            <img src="/public/image/logo.png" alt="Loading" class="absolute inset-0 w-8 h-8 mx-auto my-auto">
            <div class="loader border-8 rounded-full animate-spin"></div>
        </div>
    </div> -->
    <div class="h-screen">
        <header id="header-content" class="sticky top-0 z-50">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/client/partials/header.php'; ?>
        </header>
        <div class="overflow-y-auto">
            <main class="container max-w-2xl mx-auto min-h-screen pt-16 pb-24 px-2 lg:px-0">
                <div class="pt-6">
                    <div class="relative flex justify-center items-center">
                        <button class="absolute left-0 material-icons" onclick="goBack()">
                            <img src="/public/image/arrow.png" alt="arrow" class="w-8 h-8">
                        </button>
                        <div class="font-bold text-lg pb-2">
                            Thông tin
                        </div>
                    </div>
                    <hr>
                    <div class="flex gap-5 pt-2 pb-2 font-bold text-gray-400">
                        <div class="w-1/2 text-center border-b-4 p-2 cursor-pointer" style="border-color: #0887B3;">
                            1. THÔNG TIN
                        </div>
                        <div class="w-1/2 text-center border-b-4 border-gray-200 p-2 cursor-pointer" onclick="Order()">
                            2. THANH TOÁN
                        </div>
                    </div>
                    <div id="product-container"></div>
                    <div class="pb-2 pt-6 font-bold text-custom-blue">THÔNG TIN KHÁCH HÀNG</div>
                    <div class="bg-white rounded-lg border h-18 w-full p-4 pl-6 pr-6">
                        <div class="flex gap-4">
                            <div class="w-1/2">
                                <div class="text-gray-600 mb-2">
                                    Tên khách hàng
                                </div>
                                <input id="customerName" type="text" class="border rounded-lg w-full p-2" value="<?= $_SESSION['Ten'] ?>">
                            </div>
                            <div class="w-1/2">
                                <div class="text-gray-600 mb-2">
                                    Số điện thoại
                                </div>
                                <input id="customerPhone" type="text" class="border rounded-lg w-full p-2" value="<?=$_SESSION['SDT']?>">
                            </div>
                        </div>
                        <div class="text-gray-600 mb-2 mt-2">
                            Địa chỉ nhận hàng
                        </div>
                        <input id="customerAddress" type="text" class="border rounded-lg w-full p-2" placeholder="Nhập địa chỉ" value="<?=$_SESSION['DiaChi']?>">
                    </div>
                </div>
            </main>
        </div>
        <div class="flex items-center justify-center">
            <div class="max-w-2xl w-full fixed bottom-0">
                <div class="flex items-center justify-between bg-white border w-full p-4 rounded-lg shadow-lg h-18">
                    <div>
                        <div class="font-bold text-custom-blue" id="totalAmount">Tạm tính: 0đ</div>
                        <div class="text-xs">Chưa bao gồm mã giảm giá</div>
                    </div>
                    <button class="bg-custom-background text-white font-bold rounded-lg p-2" onclick="Order()">Tiếp tục</button>
                </div>
            </div>
        </div>

    </div>
    <script src="/public/js/notyf.min.js"></script>
    <script>
        let totalAmount = 0;
        let soluong = 0;
        document.addEventListener('DOMContentLoaded', () => {
            try{
                const customerInfo = JSON.parse(localStorage.getItem('customerInfo'));
                document.getElementById('customerAddress').value = customerInfo.customerAddress;
            } catch (error) {}
            


            const selectedProducts = JSON.parse(localStorage.getItem('selectedProducts')) || [];
            if (selectedProducts.length === 0) {
                window.location.href = '/404';
                return;
            }
            

            const checkoutContainer = document.getElementById('product-container');

            selectedProducts.forEach(product => {
                const productDiv = document.createElement('div');
                productDiv.innerHTML = `
                    <div class="pt-4">
                        <div class="flex bg-white border rounded-lg h-36 p-4">
                            <div class="flex-1">
                                <div class="flex">
                                    <div class="w-28 h-28 cursor-pointer flex justify-center" onclick="redirectToPage(${product.id})">
                                        <img class="h-full object-cover" src="${product.imageUrl}" alt="product">
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex flex-col justify-between h-full">
                                            <div class="flex justify-between items-start gap-4">
                                                <div>${product.name}</div>
                                            </div>
                                            <div class="flex justify-between items-start gap-4">
                                                <div class="flex gap-2">
                                                    <div class="text-custom-blue font-bold price">${product.pricesale.toLocaleString()}đ</div>
                                                    <div class="text-gray-600 items-center">
                                                        <del>${product.price.toLocaleString()}đ</del>
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-2 text-gray-500">
                                                    Số lượng: ${product.quantity}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                checkoutContainer.appendChild(productDiv);

                totalAmount += product.quantity * product.pricesale;
                soluong += product.quantity;
            });


            const totalAmountDiv = document.getElementById('totalAmount');
            totalAmountDiv.innerText = `Tạm tính: ${totalAmount.toLocaleString()}đ`;
        });

        var notyf = new Notyf({
            duration: 3000,
            position: {
            x: 'right',
            y: 'top',
            },
        });

        function isValidPhoneNumber(phone) {
            const trimmedPhone = phone.trim();
            const phoneRegex = /^(0|\+84)(\d{9}|\d{8})$/;

            return phoneRegex.test(trimmedPhone);
        }



        function Order(){
            const customerName = document.getElementById('customerName').value;
            const customerPhone = document.getElementById('customerPhone').value;
            const customerAddress = document.getElementById('customerAddress').value;

            if(!customerName || !customerPhone || !customerAddress){
                notyf.error("Vui lòng điền đầy đủ thông tin nhận hàng!")
                return
            }

            if(!isValidPhoneNumber(customerPhone)){
                notyf.error("Vui lòng nhập số điện thoại hợp lệ!")
                return
            }

            const info = {
                customerName: customerName,
                customerPhone: customerPhone,
                customerAddress: customerAddress,
                tongtien: totalAmount,
                soluong:soluong
            }
            localStorage.setItem('customerInfo', JSON.stringify(info));
            
            const type = '<?php echo $type; ?>'
            
            window.location.href = `/checkout/payment?type=${type}`
        }

    </script>
    <!-- <script src="/public/js/paymentinfo.js"></script> -->
    <script src="/public/js/client.js"></script>
</body>

</html>