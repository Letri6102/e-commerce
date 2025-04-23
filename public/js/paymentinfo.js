document.addEventListener("DOMContentLoaded", function () {
  const loading = document.getElementById("loading");
  setTimeout(() => {
    loading.classList.add("hidden");
  }, 1000);
});
