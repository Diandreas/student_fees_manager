import './bootstrap';

import Alpine from 'alpinejs';
import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css';
import 'tippy.js/animations/scale.css';

window.Alpine = Alpine;
Alpine.start();

// Configuration globale pour tippy.js
tippy.setDefaultProps({
    arrow: true,
    placement: 'top',
    animation: 'scale',
    theme: 'light-border',
});

// Initialiser tippy sur tous les éléments avec l'attribut title
document.addEventListener('DOMContentLoaded', () => {
    tippy('[title]');
});
