// SIPKJ Laravel Native Blade Application JavaScript Helpers
document.addEventListener('DOMContentLoaded', () => {
    // Mobile Drawer Toggle
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileDrawer = document.getElementById('mobileDrawer');
    const mobileBackdrop = document.getElementById('mobileBackdrop');
    const closeDrawerBtn = document.getElementById('closeDrawerBtn');

    function openDrawer() {
        if (mobileDrawer) mobileDrawer.classList.remove('-translate-x-full');
        if (mobileBackdrop) mobileBackdrop.classList.remove('hidden');
    }

    function closeDrawer() {
        if (mobileDrawer) mobileDrawer.classList.add('-translate-x-full');
        if (mobileBackdrop) mobileBackdrop.classList.add('hidden');
    }

    if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', openDrawer);
    if (closeDrawerBtn) closeDrawerBtn.addEventListener('click', closeDrawer);
    if (mobileBackdrop) mobileBackdrop.addEventListener('click', closeDrawer);

    // Auto-dismiss Alerts after 5s
    const alerts = document.querySelectorAll('.auto-dismiss');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 400);
        }, 4500);
    });
});
