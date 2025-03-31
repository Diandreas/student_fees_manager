import introJs from 'intro.js';
import 'intro.js/introjs.css';
import 'intro.js/themes/introjs-modern.css';

class TutorialManager {
    constructor() {
        this.seenTutorials = JSON.parse(localStorage.getItem('seenTutorials') || '{}');
        this.currentPath = window.location.pathname;
        this.tutorials = {
            // Tutoriel du tableau de bord
            '/dashboard': {
                steps: [
                    {
                        element: '#dashboard-stats',
                        intro: 'Voici votre tableau de bord avec les statistiques clés de votre école.',
                        position: 'bottom'
                    },
                    {
                        element: '#recent-activities',
                        intro: 'Consultez les dernières activités de votre école ici.',
                        position: 'top'
                    },
                    {
                        element: '#sidebar-toggle',
                        intro: 'Utilisez ce bouton pour afficher ou masquer la barre latérale sur mobile.',
                        position: 'right'
                    }
                ]
            },
            // Tutoriel de la liste des étudiants
            '/students': {
                steps: [
                    {
                        element: '.search-form',
                        intro: 'Recherchez rapidement un étudiant par son nom ou son identifiant.',
                        position: 'bottom'
                    },
                    {
                        element: '.filter-options',
                        intro: 'Filtrez les étudiants par campus, filière ou statut de paiement.',
                        position: 'bottom'
                    },
                    {
                        element: '.student-actions',
                        intro: 'Accédez rapidement aux actions pour chaque étudiant.',
                        position: 'left'
                    }
                ]
            },
            // Tutoriel de création de paiement
            '/payments/create': {
                steps: [
                    {
                        element: '#student-selector',
                        intro: 'Commencez par sélectionner un étudiant. Vous pouvez rechercher par nom ou ID.',
                        position: 'bottom'
                    },
                    {
                        element: '#payment-amount',
                        intro: 'Entrez le montant du paiement.',
                        position: 'right'
                    },
                    {
                        element: '#payment-method',
                        intro: 'Sélectionnez la méthode de paiement utilisée.',
                        position: 'left'
                    },
                    {
                        element: '#payment-submit',
                        intro: 'Cliquez ici pour enregistrer le paiement et générer un reçu.',
                        position: 'top'
                    }
                ]
            }
        };

        // Ajouter plus de tutoriels selon les fonctionnalités
    }

    // Vérifier si un tutoriel a déjà été vu
    hasSeen(path) {
        return this.seenTutorials[path] === true;
    }

    // Marquer un tutoriel comme vu
    markAsSeen(path) {
        this.seenTutorials[path] = true;
        localStorage.setItem('seenTutorials', JSON.stringify(this.seenTutorials));
    }

    // Démarrer le tutoriel pour la page actuelle si disponible
    startTutorialIfAvailable() {
        // Trouver le tutoriel qui correspond le mieux au chemin actuel
        const tutorialPath = Object.keys(this.tutorials).find(path =>
            this.currentPath.startsWith(path) || this.currentPath === path
        );

        if (tutorialPath && !this.hasSeen(tutorialPath)) {
            this.startTutorial(tutorialPath);
        }
    }

    // Démarrer un tutoriel spécifique
    startTutorial(path) {
        if (!this.tutorials[path]) return;

        const intro = introJs();
        intro.setOptions({
            steps: this.tutorials[path].steps,
            showProgress: true,
            showBullets: true,
            showStepNumbers: false,
            exitOnOverlayClick: false,
            nextLabel: 'Suivant',
            prevLabel: 'Précédent',
            skipLabel: 'Ignorer',
            doneLabel: 'Terminé'
        });

        intro.onexit(() => {
            this.markAsSeen(path);
        });

        intro.start();
    }

    // Réinitialiser les tutoriels vus
    resetTutorials() {
        localStorage.removeItem('seenTutorials');
        this.seenTutorials = {};
    }
}

// Exporter une instance du gestionnaire de tutoriels
export const tutorialManager = new TutorialManager();

// Vérifier si on doit afficher un tutoriel au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    // Petit délai pour laisser la page se charger complètement
    setTimeout(() => {
        tutorialManager.startTutorialIfAvailable();
    }, 500);

    // Ajouter un bouton dans le profil utilisateur pour réinitialiser les tutoriels
    const resetButton = document.getElementById('reset-tutorials');
    if (resetButton) {
        resetButton.addEventListener('click', (e) => {
            e.preventDefault();
            tutorialManager.resetTutorials();
            alert('Tous les tutoriels ont été réinitialisés. Ils s\'afficheront à nouveau lors de votre prochaine visite des pages concernées.');
        });
    }
}); 