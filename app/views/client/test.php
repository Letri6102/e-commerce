<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Star Rating Module</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="/public/css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/swiper-bundle.min.css">
    <link rel="stylesheet" href="/public/css/client.css">
    <link rel="stylesheet" href="/public/css/notyf.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <!-- Nút mở bảng đánh giá -->
  <button id="open-rating" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600">
    Đánh giá
  </button>

  <!-- Bảng đánh giá (ẩn mặc định) -->
  <div id="rating-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
      <h2 class="text-lg font-bold text-gray-800 mb-4">Đánh giá sản phẩm:</h2>
      <div class="flex space-x-1">
        <button class="star text-gray-400 hover:text-yellow-500" data-value="1">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 inline-block" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
            </svg>
        </button>
        <!-- Repeat the button for 2, 3, 4, and 5 stars -->
        <button class="star text-gray-400 hover:text-yellow-500" data-value="2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 inline-block" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
            </svg>
        </button>
        <button class="star text-gray-400 hover:text-yellow-500" data-value="3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 inline-block" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
            </svg>
        </button>
        <button class="star text-gray-400 hover:text-yellow-500" data-value="4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 inline-block" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
            </svg>
        </button>
        <button class="star text-gray-400 hover:text-yellow-500" data-value="5">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 inline-block" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
            </svg>
        </button>
      </div>
      <p id="rating-output" class="text-gray-700 mt-4">Rate: <span class="font-bold">0</span> stars</p>
      <div class="mt-4 flex justify-end space-x-2">
        <button id="close-rating" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400">Đóng</button>
        <button onclick="saving()" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">Lưu</button>
      </div>
    </div>
  </div>
  <script src="/public/js/notyf.min.js"></script>

  <script>
    var notyf = new Notyf({
        duration: 3000,
        position: {
        x: 'right',
        y: 'top',
        },
    });
    // Hiển thị bảng đánh giá khi nhấn nút "Đánh giá"
    const openRatingBtn = document.getElementById('open-rating');
    const ratingModal = document.getElementById('rating-modal');
    const closeRatingBtn = document.getElementById('close-rating');

    openRatingBtn.addEventListener('click', () => {
      ratingModal.classList.remove('hidden');
    });

    // Ẩn bảng đánh giá khi nhấn "Đóng"
    closeRatingBtn.addEventListener('click', () => {
      ratingModal.classList.add('hidden');
    });

    // Xử lý logic đánh giá sao
    document.querySelectorAll('.star').forEach((star) => {
      star.addEventListener('click', function () {
        const rating = this.getAttribute('data-value');
        document.getElementById('rating-output').querySelector('span').textContent = rating;

        // Reset tất cả sao về mặc định
        document.querySelectorAll('.star').forEach((el) => {
          el.classList.remove('text-yellow-500');
          el.classList.add('text-gray-400');
        });

        // Tô màu các sao đã chọn
        for (let i = 0; i < rating; i++) {
          document.querySelectorAll('.star')[i].classList.add('text-yellow-500');
          document.querySelectorAll('.star')[i].classList.remove('text-gray-400');
        }
      });
    });

    function saving() {
            const data = {
                ID_SP: 1,
                Sosao: 1,
                NoiDung: ""
            };
            console.log(data)
            fetch(`${window.location.origin}/api/user/review`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            })
            .then(response => response.json())
            .then(data => {
                console.log(data)
                if (data.success == true) {
                    notyf.success('Thêm vào sản phẩm vào giỏ hàng thành công!');

                    CountCart();
                } else {
                    notyf.error('Thêm vào sản phẩm vào giỏ hàng thất bại!');
                }
            })
            .catch(error => {
                notyf.error('Đã xảy ra lỗi khi thêm sản phẩm vào giỏ hàng!');
            });
        }
  </script>
</body>
</html>
