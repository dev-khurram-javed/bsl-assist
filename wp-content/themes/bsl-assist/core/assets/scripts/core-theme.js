const blocks = (name, callback) => {
    const instances = document.querySelectorAll(`[data-block="${name}"]`);
    instances.forEach(i => {
        const blockId = i.dataset.blockId || '';
        const data = blockId && typeof window[blockId] !== 'undefined' ? window[blockId].data : null;

        if (callback) callback(i, data);
    });
}

const components = (name, callback) => {
    // for (const [name, callback] of Object.entries(_anvilEntities.components)) {
    const instances = document.querySelectorAll(`[data-component="${name}"]`);
    instances.forEach(i => callback(i));
    // }
}

window.coreTheme = {
    blocks,
    components
}

// Import modules.
import Swiper from 'swiper/bundle';

window.packages = { swiper: Swiper };