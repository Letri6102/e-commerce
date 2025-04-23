function redirectToPage(id) {
  const targetUrl = `/product?id=${id}`;
  window.location.href = targetUrl;
}

function goBack() {
  window.history.back();
}

document.addEventListener("DOMContentLoaded", () => {
  function fetchCartItems() {
    fetch("api/user/cart")
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          displayCartItems(data.danh_sach_san_pham);
        } else {
          displayCartItems([]);
        }
      })
      .catch((error) => {
        console.error("Error fetching cart items:", error);
      });
  }

  function displayCartItems(products) {
    const cartContainer = document.getElementById("cart-items-container");
    cartContainer.innerHTML = "";

    if (products.length != 0) {
      document.getElementById("selectAllbox").classList.remove("hidden");
      document.getElementById("noProduct").classList.add("hidden");
    }

    products.forEach((product) => {
      const productDiv = document.createElement("div");
      productDiv.classList.add("pt-4");
      productDiv.innerHTML = `
                <div class="flex bg-white border rounded-lg h-36 p-4 product-item" data-id="${
                  product.id
                }">
                    <div>
                        <input type="checkbox" class="product-checkbox w-4 h-4 cursor-pointer">
                    </div>
                    <div class="flex-1">
                        <div class="flex">
                            <div class="w-28 h-28 cursor-pointer flex justify-center" onclick="redirectToPage(${
                              product.id
                            })">
                                <img class="h-full object-cover" src="${
                                  product.hinh_anh
                                }" alt="product">
                            </div>
                            <div class="flex-1">
                                <div class="flex flex-col justify-between h-full w-full">
                                    <div class="flex items-start gap-1 justify-between">
                                        <div class="text-sm lg:text-base flex-1 cursor-pointer" onclick="redirectToPage(${
                                          product.id
                                        })">${product.ten}</div>
                                        <button class="remove-btn w-6" onclick="showDeletePopup(${
                                          product.id
                                        })">
                                            <img class="w-6 object-contain" src="/public/image/trash.png" alt="trash">
                                        </button>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <div class="flex gap-2 text-sm">
                                            <div class="text-custom-blue font-bold pricesale">${formatCurrency(
                                              product.gia_khuyen_mai
                                            )}</div>
                                            <div class="price text-gray-600 items-center hidden lg:block">
                                                <del>${formatCurrency(
                                                  product.gia_goc
                                                )}</del>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <button class="bg-custom-background rounded-lg w-6 h-6 flex items-center justify-center text-white font-bold transition-transform duration-200 ease-in-out transform hover:scale-105 active:scale-95 decrease-btn"
                                                onclick="editAmount(${
                                                  product.id
                                                },-1)">
                                                -
                                            </button>
                                            <input type="number" id="quantity-${
                                              product.id
                                            }" value="${
        product.so_luong
      }" min="1" class="quantity-input text-center w-10 border border-gray-300 rounded-lg" readonly>
                                            <button class="bg-custom-background rounded-lg w-6 h-6 flex items-center justify-center text-white font-bold transition-transform duration-200 ease-in-out transform hover:scale-105 active:scale-95 increase-btn"
                                                onclick="editAmount(${
                                                  product.id
                                                },1)">
                                                +
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
`;
      cartContainer.appendChild(productDiv);
    });

    bindEventListeners();
  }

  function formatCurrency(amount) {
    return new Intl.NumberFormat("vi-VN", {
      style: "currency",
      currency: "VND",
    }).format(amount);
  }

  function bindEventListeners() {
    const selectAllCheckbox = document.getElementById("selectAll");
    const productCheckboxes = document.querySelectorAll(".product-checkbox");
    const totalAmountDisplay = document.getElementById("totalAmount");

    selectAllCheckbox.addEventListener("change", (event) => {
      productCheckboxes.forEach((checkbox) => {
        checkbox.checked = event.target.checked;
      });
      updateTotalAmount();
    });

    document.querySelectorAll(".increase-btn").forEach((button) => {
      button.addEventListener("click", () => {
        const input = button.previousElementSibling;
        let quantity = parseInt(input.value);
        input.value = quantity + 1;
        updateTotalAmount();
      });
    });

    document.querySelectorAll(".decrease-btn").forEach((button) => {
      button.addEventListener("click", () => {
        const input = button.nextElementSibling;
        let quantity = parseInt(input.value);
        if (quantity > 1) {
          input.value = quantity - 1;
          updateTotalAmount();
        }
      });
    });

    productCheckboxes.forEach((checkbox) => {
      checkbox.addEventListener("change", updateTotalAmount);
    });
  }

  function updateTotalAmount() {
    const totalAmountDisplay = document.getElementById("totalAmount");
    let total = 0;
    document
      .querySelectorAll(".product-checkbox:checked")
      .forEach((checkbox) => {
        const quantity = parseInt(
          checkbox.closest(".flex").querySelector(".quantity-input").value
        );
        const priceText = checkbox
          .closest(".flex")
          .querySelector(".pricesale")
          .innerText.replace("đ", "")
          .replace(".", "");
        const price = parseInt(priceText);
        total += price * quantity;
      });
    totalAmountDisplay.innerText = `Tạm tính: ${total.toLocaleString()}đ`;
  }

  fetchCartItems();
});

let productIdToDelete = null;

function showDeletePopup(productId) {
  productIdToDelete = productId;
  const deletePopup = document.getElementById("deletePopup");
  deletePopup.classList.remove("hidden");

  document
    .getElementById("cancelDelete")
    .addEventListener("click", hideDeletePopup);
  document
    .getElementById("confirmDelete")
    .addEventListener("click", handleDeleteProduct);
}

function hideDeletePopup() {
  productIdToDelete = null;
  document.getElementById("deletePopup").classList.add("hidden");
}

var notyf = new Notyf({
  duration: 3000,
  position: {
    x: "right",
    y: "top",
  },
});

function updateTotalAmount() {
  const totalAmountDisplay = document.getElementById("totalAmount");
  let total = 0;
  document.querySelectorAll(".product-checkbox:checked").forEach((checkbox) => {
    const quantity = parseInt(
      checkbox.closest(".flex").querySelector(".quantity-input").value
    );
    const priceText = checkbox
      .closest(".flex")
      .querySelector(".pricesale")
      .innerText.replace("đ", "")
      .replace(".", "");
    const price = parseInt(priceText);
    total += price * quantity;
  });
  totalAmountDisplay.innerText = `Tạm tính: ${total.toLocaleString()}đ`;
}

function handleDeleteProduct() {
  if (!productIdToDelete) return;

  fetch("api/cart/delete", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      ID_SP: productIdToDelete,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        const productElement = document.querySelector(
          `.product-item[data-id="${productIdToDelete}"]`
        );
        if (productElement) {
          const parentDiv = productElement.closest(".pt-4");
          if (parentDiv) {
            parentDiv.remove();
          } else {
            productElement.remove();
          }
        }
        const productElements = document.querySelectorAll(".product-item");
        if (productElements.length == 0) {
          document.getElementById("selectAllbox").classList.add("hidden");
          document.getElementById("noProduct").classList.remove("hidden");
        }
        hideDeletePopup();
        updateTotalAmount();
        notyf.success("Xoá sản phẩm khỏi giỏ hàng thành công!");
        CountCart();
      } else {
        notyf.error("Xoá sản phẩm khỏi giỏ hàng thất bại!");
      }
    })
    .catch((error) => {
      notyf.error("Đã xảy ra lỗi khi xoá sản phẩm!");
      console.log(error);
    });
}

function editAmount(productId, action) {
  const soluong =
    parseInt(document.getElementById(`quantity-${productId}`).value, 10) +
    action;

  if (soluong == 0) {
    return;
  }

  fetch("api/cart/update", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      ID_SP: productId,
      quantity: soluong,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        notyf.success("Cập nhật số lượng thành công!");
      } else {
        notyf.error("Cập nhật số lượng thất bại!");
      }
    })
    .catch((error) => {
      notyf.error("Đã xảy ra lỗi khi cập nhật số lượng!");
      console.error(error);
    });
}

function saveSelectedProducts() {
  const selectedProducts = [];
  document.querySelectorAll(".product-checkbox:checked").forEach((checkbox) => {
    const productItem = checkbox.closest(".product-item");
    const id = productItem.dataset.id;
    const name = productItem.querySelector(".text-sm").innerText;
    const priceText = productItem
      .querySelector(".price")
      .innerText.replace("đ", "")
      .replace(/\./g, "");
    const price = parseInt(priceText, 10);
    const priceTextSale = productItem
      .querySelector(".pricesale")
      .innerText.replace("đ", "")
      .replace(/\./g, "");
    const pricesale = parseInt(priceTextSale, 10);
    const quantity = parseInt(
      productItem.querySelector(".quantity-input").value,
      10
    );
    const imageUrl = productItem.querySelector("img").src;

    selectedProducts.push({
      id: id,
      name: name,
      price: price,
      pricesale: pricesale,
      quantity: quantity,
      imageUrl: imageUrl,
    });
  });

  localStorage.setItem("selectedProducts", JSON.stringify(selectedProducts));
  console.log("Selected products saved to localStorage:", selectedProducts);
}

document.getElementById("orderButton").addEventListener("click", () => {
  const selectedCheckboxes = document.querySelectorAll(
    ".product-checkbox:checked"
  );

  if (selectedCheckboxes.length === 0) {
    notyf.error("Vui lòng chọn sản phẩm cần mua!");
    return;
  }

  saveSelectedProducts();
  window.location.href = "/checkout/preview";
});
