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

/**
 * Horizontal sliders (e.g. Front Covers). The viewport is a native
 * scroll-snap container, so it already works by touch or trackpad with no
 * JS. This only wires the arrow buttons: each click scrolls by exactly one
 * slide, and a button disables itself once its end of the track is reached.
 */
function initSliders() {
    document.querySelectorAll('[data-slider]').forEach((root) => {
        const viewport = root.querySelector('[data-slider-viewport]');
        const track = root.querySelector('[data-slider-track]');
        const prev = root.querySelector('[data-slider-prev]');
        const next = root.querySelector('[data-slider-next]');

        if (!viewport || !track || !prev || !next) {
            return;
        }

        const scrollByOneSlide = (direction) => {
            const slide = track.firstElementChild;
            if (!slide) {
                return;
            }
            const gap = parseFloat(getComputedStyle(track).columnGap || '0') || 0;
            viewport.scrollBy({ left: direction * (slide.getBoundingClientRect().width + gap), behavior: 'smooth' });
        };

        const updateArrows = () => {
            const maxScroll = viewport.scrollWidth - viewport.clientWidth - 1;
            prev.disabled = viewport.scrollLeft <= 0;
            next.disabled = maxScroll <= 0 || viewport.scrollLeft >= maxScroll;
        };

        prev.addEventListener('click', () => scrollByOneSlide(-1));
        next.addEventListener('click', () => scrollByOneSlide(1));
        viewport.addEventListener('scroll', updateArrows, { passive: true });
        window.addEventListener('resize', updateArrows);
        updateArrows();
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
    initSliders();
    initCookieBar();
});
