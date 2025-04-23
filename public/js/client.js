// js cho swipper
const swiper = new Swiper(".swiper-container", {
  loop: true,
  slidesPerView: 1,
  spaceBetween: 10,
  autoplay: {
    delay: 2500,
    disableOnInteraction: false,
  },
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  on: {
    slideChange: function () {
      const slides = document.querySelectorAll(".swiper-slide");
      slides.forEach((slide, index) => {
        if (index === this.activeIndex) {
          slide.style.opacity = 1;
        } else {
          slide.style.opacity = 0;
        }
      });
    },
  },
});

// js cho swipper product
const swiper2 = new Swiper(".swiper-container-product", {
  loop: true,
  slidesPerGroup: 1,
  spaceBetween: 12,
  autoplay: {
    delay: 4000,
    disableOnInteraction: false,
  },
  on: {
    slideChange: function () {
      const slides = document.querySelectorAll(".swiper-slide-product");
      slides.forEach((slide, index) => {
        if (
          index >= this.activeIndex &&
          index < this.activeIndex + this.params.slidesPerView
        ) {
          slide.style.opacity = 1;
        } else {
          slide.style.opacity = 0;
        }
      });
    },
  },
  slideClass: "swiper-slide-product",
  breakpoints: {
    1024: {
      slidesPerView: 5,
    },
    768: {
      slidesPerView: 3,
    },
    0: {
      slidesPerView: 2,
      spaceBetween: 5,
    },
  },
});

// js cho swipper trang product
const swiper3 = new Swiper(".swiper-container-product-detail", {
  loop: true,
  slidesPerView: 1,
  spaceBetween: 10,
  navigation: {
    nextEl: ".swiper-button-pr-next",
    prevEl: ".swiper-button-pr-prev",
  },
  on: {
    slideChange: function () {
      const slides = document.querySelectorAll(".swiper-slide-product-detail");
      slides.forEach((slide, index) => {
        if (index === this.activeIndex) {
          slide.style.opacity = 1;
        } else {
          slide.style.opacity = 0;
        }
      });
    },
  },
  slideClass: "swiper-slide-product-detail",
});

function goBack() {
  window.history.back();
}

function goPayment() {
  const customerName = document.getElementById("customerName").value;
  const customerPhone = document.getElementById("customerPhone").value;
  const customerAddress = document.getElementById("customerAddress").value;

  if (!customerName || !customerPhone || !customerAddress) {
    notyf.error("Vui lòng điền đầy đủ thông tin nhận hàng!");
    return;
  }

  window.location.href = "/checkout/payment";
}

var notyf = new Notyf({
  duration: 3000,
  position: {
    x: "right",
    y: "top",
  },
});
