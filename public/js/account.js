var notyf = new Notyf({
  duration: 3000,
  position: {
    x: "right",
    y: "top",
  },
});

let currentPage = 1;
const recordsPerPage = 2;
let diaryData = [];

document.addEventListener("DOMContentLoaded", function () {
  fetchUserData();
});

document
  .getElementById("changePasswordForm")
  .addEventListener("submit", function (event) {
    event.preventDefault();
    const oldPassword = document.getElementById("old-password").value;
    const newPassword = document.getElementById("new-password").value;
    const confirmPassword = document.getElementById("auth-password").value;

    if (newPassword !== confirmPassword) {
      notyf.error("Mật khẩu mới và mật khẩu xác nhận không khớp.");
      return;
    }

    const button = document.getElementById("change-password-btn");
    button.textContent = "Đang xử lý...";
    button.disabled = true;

    fetch(`${window.location.origin}/api/auth/change`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        current_password: oldPassword,
        new_password: newPassword,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          notyf.success(data.message);
          document.getElementById("changePasswordForm").reset();
        } else {
          notyf.error("Lỗi: " + data.message);
        }
      })
      .catch((error) => {
        notyf.error("Đã xảy ra lỗi:", error);
      })
      .finally(() => {
        button.textContent = "Thay đổi";
        button.disabled = false;
      });
  });

function fetchUserData() {
  fetch(`${window.location.origin}/api/user/info`, {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
    },
    credentials: "same-origin",
  })
    .then((response) => response.json())
    .then((data) => {
      console.log(data);
      try {
        document.getElementById("full-name").value = data.thong_tin.name;
        if (data.thong_tin.phone) {
          document.getElementById("phone").value = data.thong_tin.phone;
        }
        if (data.thong_tin.address) {
          document.getElementById("address").value = data.thong_tin.address;
        }
        if (!data.thong_tin.sex) {
          document.getElementById("other").checked = true;
          document.getElementById("Male").checked = false;
          document.getElementById("Female").checked = false;
        } else if (data.thong_tin.sex == "Male") {
          document.getElementById("other").checked = false;
          document.getElementById("Male").checked = true;
          document.getElementById("Female").checked = false;
        } else {
          document.getElementById("other").checked = false;
          document.getElementById("Male").checked = false;
          document.getElementById("Female").checked = true;
        }
        document.getElementById("level").value = data.thong_tin.role;
        diaryData = data.nhat_ky || [];
        displayDiary();
      } catch (error) {
        console.error("Lỗi phân tích JSON:", error);
        notyf.error("Lỗi phản hồi máy chủ");
      }
    })
    .catch((error) => {
      console(error);
      notyf.error("Lỗi kết nối" + error);
    });
}

function updateUserInfo() {
  const name = document.getElementById("full-name").value;
  const sex = document.querySelector('input[name="gender"]:checked')
    ? document.querySelector('input[name="gender"]:checked').value
    : "";
  const phone = document.getElementById("phone").value;
  const address = document.getElementById("address").value;

  fetch(`${window.location.origin}/api/user/info`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    credentials: "same-origin",
    body: JSON.stringify({ name, sex, phone, address }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        notyf.success(data.message);
        fetchUserData();
      } else {
        notyf.error(data.message);
      }
    })
    .catch((error) => {
      notyf.error(data.error);
    });
}

function displayDiary() {
  const diaryTable = document.getElementById("diaryTable");
  diaryTable.innerHTML = "";
  const startIndex = (currentPage - 1) * recordsPerPage;
  const endIndex = Math.min(startIndex + recordsPerPage, diaryData.length);
  const currentRecords = diaryData.slice(startIndex, endIndex);

  currentRecords.forEach((record) => {
    const row = document.createElement("tr");
    row.classList.add("border-b");

    const timeCell = document.createElement("td");
    timeCell.classList.add("px-4", "py-2", "text-gray-700");
    timeCell.textContent = record.ThoiGian;

    const contentCell = document.createElement("td");
    contentCell.classList.add("px-4", "py-2", "text-gray-700");
    contentCell.textContent = record.NoiDung;

    row.appendChild(timeCell);
    row.appendChild(contentCell);
    diaryTable.appendChild(row);
  });

  const paginationContainer = document.getElementById("paginationContainer");
  if (diaryData.length > recordsPerPage) {
    paginationContainer.classList.remove("hidden");
  } else {
    paginationContainer.classList.add("hidden");
  }

  const currentPageDisplay = document.getElementById("currentPageDisplay");
  currentPageDisplay.textContent = `${currentPage}`;

  document.getElementById("prevPage").classList.remove("hidden");
  document.getElementById("nextPage").classList.remove("hidden");
  if (currentPage === 1) {
    document.getElementById("prevPage").classList.add("hidden");
  }
  if (endIndex >= diaryData.length) {
    document.getElementById("nextPage").classList.add("hidden");
  }
}

function previousPage() {
  if (currentPage > 1) {
    currentPage--;
    displayDiary();
  }
}

function nextPage() {
  if (currentPage * recordsPerPage < diaryData.length) {
    currentPage++;
    displayDiary();
  }
}
