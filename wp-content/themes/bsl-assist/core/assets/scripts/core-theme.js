const blocks = (name, callback) => {
    const instances = document.querySelectorAll(`[data-block="${name}"]`);
    instances.forEach(i => {
        const blockId = i.dataset.blockId || '';
        const data = blockId && typeof window[blockId] !== 'undefined' ? window[blockId].data : null;

        if (callback) callback(i, data);
    });
}

const components = (name, callback) => {
    const instances = document.querySelectorAll(`[data-component="${name}"]`);
    instances.forEach(i => callback(i));
}

window.coreTheme = {
    blocks,
    components
}

// Import modules.
import Swiper from 'swiper/bundle';

window.packages = { swiper: Swiper };

// Animations
const appearElements = [];

// Store the main elements.
console.log(document.querySelectorAll('[data-appear]'));
document.querySelectorAll('[data-appear]').forEach(parentEl => {
    const { appear: threshold } = parentEl.dataset;
    const parent = {
        el: parentEl,
        threshold,
        children: []
    };

    // Store the children.
    parentEl.querySelectorAll('[data-appear-child]')
        .forEach(childEl => {

            // Bail early if it's a multilevel child.
            if (childEl.closest('[data-appear]') !== parentEl) return;

            const { appearChild: timeout } = childEl.dataset;
            const child = {
                el: childEl,
                timeout: Number(timeout)
            };

            parent.children.push(child);
        });

    // Store the main element.
    appearElements.push(parent);
});

/** Checks and triggers the appear animation. */
const checkAppear = () => {
    const { scrollY, innerHeight } = window;
    const e = new Event('appear');

    // Check each item.
    for (let x = 0; x < appearElements.length; x++) {
        const parent = appearElements[x];
        let threshold = Number(parent.threshold.replace('vh', ''));
        threshold = scrollY + (innerHeight - (innerHeight * threshold / 100));

        const offsetTop = parent.el.getBoundingClientRect().top + document.documentElement.scrollTop;
        if (threshold >= offsetTop) {
            parent.el.classList.add('animate-appear');
            parent.el.dispatchEvent(e);

            for (let child of parent.children) {
                setTimeout(() => {
                    child.el.classList.add('animate-appear');
                    child.el.dispatchEvent(e);
                }, child.timeout);
            }

            // Remove the item from the list.
            appearElements.splice(x, 1);
            x--;
        }
    }
};

// Check on load.
checkAppear();

// Check appear elements on scroll.
window.addEventListener('scroll', checkAppear);