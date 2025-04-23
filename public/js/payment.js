// document.addEventListener("DOMContentLoaded", function () {
//   const selectedProducts =
//     JSON.parse(localStorage.getItem("selectedProducts")) || [];
//   if (selectedProducts.length === 0) {
//     window.location.href = "/404";
//     return;
//   }

//   const customerInfo = JSON.parse(localStorage.getItem("customerInfo"));
//   if (!customerInfo || Object.keys(customerInfo).length === 0) {
//     window.location.href = "/404";
//   } else {
//     const totalAmountDiv = document.getElementById("totalAmount");
//     totalAmountDiv.innerText = `Tạm tính: ${customerInfo.tongtien.toLocaleString()}đ`;

//     const totalCountDiv = document.getElementById("totalCount");
//     totalCountDiv.innerText = `${customerInfo.soluong}`;

//     const tienhangDiv = document.getElementById("tienhang");
//     tienhangDiv.innerText = `${customerInfo.tongtien.toLocaleString()}đ`;

//     const tongtienDiv = document.getElementById("tongtien");
//     tongtienDiv.innerText = `${customerInfo.tongtien.toLocaleString()}đ`;

//     document.getElementById("tenkhachhang").value = customerInfo.customerName;
//     document.getElementById("diachi").value = customerInfo.customerAddress;
//     document.getElementById("sdt").value = customerInfo.customerPhone;
//   }

//test
document.addEventListener("DOMContentLoaded", function () {
  const selectedProducts =
    JSON.parse(localStorage.getItem("selectedProducts")) || [];
  if (selectedProducts.length === 0) {
    window.location.href = "/404";
    return;
  }

  const customerInfo = JSON.parse(localStorage.getItem("customerInfo"));

  if (
    !customerInfo ||
    !customerInfo.customerName ||
    !customerInfo.customerPhone ||
    !customerInfo.customerAddress
  ) {
    // Nếu thiếu thông tin cơ bản thì chuyển người dùng về trang nhập thông tin
    window.location.href = "/checkout/customerinfo";
    return;
  }

  // Nếu đầy đủ, cho phép hiện dữ liệu và tiếp tục thanh toán
  document.getElementById("tenkhachhang").value = customerInfo.customerName;
  document.getElementById("diachi").value = customerInfo.customerAddress;
  document.getElementById("sdt").value = customerInfo.customerPhone;

  const codRadio = document.getElementById("COD");
  const bankRadio = document.getElementById("Bank");
  const discountInput = document.querySelector(
    'input[placeholder="Nhập mã giảm giá"]'
  );
  const applyButton = document.querySelector("button.bg-gray-200");

  codRadio.checked = true;

  codRadio.addEventListener("change", function () {
    if (this.checked) {
      bankRadio.checked = false;
    }
  });

  bankRadio.addEventListener("change", function () {
    if (this.checked) {
      codRadio.checked = false;
    }
  });

  discountInput.addEventListener("input", function () {
    if (this.value.trim() === "") {
      applyButton.classList.remove("bg-custom-background", "text-white");
      applyButton.classList.add("bg-gray-200");
      applyButton.disabled = true;
    } else {
      applyButton.classList.add("bg-custom-background", "text-white");
      applyButton.classList.remove("bg-gray-200");
      applyButton.disabled = false;
    }
  });
});
