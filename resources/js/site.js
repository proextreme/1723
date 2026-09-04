/**
 * Public-site interactions: the mobile navigation drawer and the cookie
 * consent banner. Everything degrades to a usable state without JavaScript.
 */

function initNavToggle() {
    const toggle = document.querySelector('[data-nav-toggle]');
    const drawer = document.querySelector('[data-nav-drawer]');

    if (!toggle || !drawer) {
        return;
    }

    const setOpen = (open) => {
        toggle.setAttribute('aria-expanded', String(open));
        drawer.hidden = !open;
        document.documentElement.classList.toggle('overflow-hidden', open);
    };

    toggle.addEventListener('click', () => {
        setOpen(toggle.getAttribute('aria-expanded') !== 'true');
    });

    drawer.addEventListener('click', (event) => {
        if (event.target.closest('a')) {
            setOpen(false);
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setOpen(false);
        }
    });
}

function initCookieConsent() {
    const banner = document.querySelector('[data-cookie-consent]');

    if (!banner) {
        return;
    }

    let accepted = false;
    try {
        accepted = localStorage.getItem('cookie-consent') === 'accepted';
    } catch (error) {
        accepted = false;
    }

    if (accepted) {
        banner.remove();
        return;
    }

    banner.hidden = false;

    banner.querySelector('[data-cookie-accept]')?.addEventListener('click', () => {
        try {
            localStorage.setItem('cookie-consent', 'accepted');
        } catch (error) {
            /* private mode — the banner simply reappears next visit */
        }
        banner.remove();
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initNavToggle();
    initCookieConsent();
});
