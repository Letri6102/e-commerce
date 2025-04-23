const sidebarToggle = document.getElementById('sidebar-toggle');
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');
const avatar = document.getElementById('avatar');

sidebarToggle.addEventListener('click', () => {
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
});

document.addEventListener('click', (event) => {
    const isClickInsideSidebar = sidebar.contains(event.target);
    const isClickInsideAvatar = avatar.contains(event.target);
    const isClickInsideToggle = sidebarToggle.contains(event.target);
    const isClickInsideOverlay = overlay.contains(event.target);

    if (!isClickInsideSidebar && !isClickInsideAvatar && !isClickInsideToggle) {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    }

    if (isClickInsideOverlay) {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    }
});
