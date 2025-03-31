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

    // Initialiser le gestionnaire de tutoriels (s'il est disponible)
    try {
        const TutorialManager = require('./tutorial').TutorialManager;
        window.tutorialManager = new TutorialManager();
    } catch (e) {
        console.log('Module de tutoriel non disponible');
    }

    // Initialiser le gestionnaire de paiements mobiles si on est sur une page de paiements
    try {
        if (window.location.pathname.includes('/payments')) {
            const MobilePaymentManager = require('./mobile-payments').MobilePaymentManager;
            window.mobilePaymentManager = new MobilePaymentManager();
        }
    } catch (e) {
        console.log('Module de paiement mobile non disponible');
    }

    // Enregistrer le service worker pour le mode hors ligne
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/service-worker.js')
                .then(registration => {
                    console.log('Service Worker enregistré avec succès:', registration.scope);
                })
                .catch(error => {
                    console.log('Échec de l\'enregistrement du Service Worker:', error);
                });
        });
    }
});
