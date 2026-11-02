import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const button = document.getElementById('mobile-menu-button');
    const drawer = document.getElementById('mobile-menu-drawer');
    const hamburgerIcon = document.getElementById('hamburger-icon');
    const closeIcon = document.getElementById('close-icon');

    if (button && drawer) {
        button.addEventListener('click', () => {
            const isExpanded = button.getAttribute('aria-expanded') === 'true';
            button.setAttribute('aria-expanded', String(!isExpanded));
            drawer.classList.toggle('hidden');
            if (hamburgerIcon && closeIcon) {
                hamburgerIcon.classList.toggle('hidden');
                closeIcon.classList.toggle('hidden');
            }
        });
    }
});
