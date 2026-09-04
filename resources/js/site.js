/**
 * Public-site interactions: the mobile navigation drawer and the cookie
 * consent banner. Everything degrades to a usable state without JavaScript.
 */

function initNavDrawer() {
    const toggles = document.querySelectorAll('[data-nav-toggle]');
    const drawer = document.querySelector('[data-nav-drawer]');

    if (!toggles.length || !drawer) {
        return;
    }

    const openToggle = document.querySelector('[data-nav-toggle="open"]');

    const setOpen = (open) => {
        drawer.hidden = !open;
        document.documentElement.classList.toggle('is-nav-open', open);
        openToggle?.setAttribute('aria-expanded', String(open));
    };

    toggles.forEach((toggle) => {
        toggle.addEventListener('click', () => {
            setOpen(drawer.hidden);
        });
    });

    drawer.addEventListener('click', (event) => {
        if (event.target.closest('a')) {
            setOpen(false);
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !drawer.hidden) {
            setOpen(false);
        }
    });
}

function initCookieBar() {
    const bar = document.querySelector('[data-cookie-bar]');

    if (!bar) {
        return;
    }

    let accepted = false;
    try {
        accepted = localStorage.getItem('cookie-consent') === 'accepted';
    } catch {
        accepted = false;
    }

    if (accepted) {
        bar.remove();
        return;
    }

    bar.hidden = false;

    bar.querySelector('[data-cookie-accept]')?.addEventListener('click', () => {
        try {
            localStorage.setItem('cookie-consent', 'accepted');
        } catch {
            /* private mode — the bar simply reappears next visit */
        }
        bar.remove();
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initNavDrawer();
    initCookieBar();
});
