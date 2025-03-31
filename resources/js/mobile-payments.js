/**
 * Mobile Payments - Module pour optimiser l'expérience de paiement sur appareils mobiles
 */

class MobilePaymentManager {
    constructor() {
        this.isOnline = navigator.onLine;
        this.pendingPayments = JSON.parse(localStorage.getItem('pendingPayments') || '[]');
        this.syncInProgress = false;

        // Écouter les changements d'état de connexion
        window.addEventListener('online', this.handleOnlineStatusChange.bind(this));
        window.addEventListener('offline', this.handleOnlineStatusChange.bind(this));
    }

    /**
     * Initialiser l'interface mobile pour les paiements
     */
    init() {
        // Vérifier si nous sommes sur un appareil mobile
        if (this.isMobileDevice()) {
            this.setupMobileUI();
            this.registerServiceWorker();
        }

        // Si nous sommes sur la page de paiement
        if (window.location.pathname.includes('/payments/create') ||
            window.location.pathname.includes('/payments/quick')) {
            this.initQuickPaymentForm();
        }

        // Vérifier s'il y a des paiements en attente à synchroniser
        if (this.isOnline && this.pendingPayments.length > 0) {
            this.syncPendingPayments();
        }
    }

    /**
     * Détecter si l'utilisateur est sur un appareil mobile
     */
    isMobileDevice() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ||
            window.innerWidth <= 768;
    }

    /**
     * Configurer l'interface pour les appareils mobiles
     */
    setupMobileUI() {
        // Ajouter une classe au body pour les styles spécifiques au mobile
        document.body.classList.add('mobile-device');

        // Ajouter un bouton flottant pour accéder rapidement à la création de paiement
        if (!document.getElementById('quick-payment-fab') &&
            !window.location.pathname.includes('/payments/create')) {
            const fab = document.createElement('button');
            fab.id = 'quick-payment-fab';
            fab.className = 'fixed bottom-4 right-4 bg-primary-600 text-white rounded-full p-4 shadow-lg z-50';
            fab.innerHTML = '<i class="fas fa-plus"></i>';
            fab.addEventListener('click', () => {
                window.location.href = '/payments/quick';
            });
            document.body.appendChild(fab);
        }

        // Afficher un indicateur de connexion
        this.updateConnectionIndicator();
    }

    /**
     * Mettre à jour l'indicateur de connexion
     */
    updateConnectionIndicator() {
        let indicator = document.getElementById('connection-indicator');

        if (!indicator) {
            indicator = document.createElement('div');
            indicator.id = 'connection-indicator';
            indicator.className = 'fixed top-0 right-0 m-2 px-2 py-1 text-xs rounded z-50';
            document.body.appendChild(indicator);
        }

        if (this.isOnline) {
            indicator.className = 'fixed top-0 right-0 m-2 px-2 py-1 text-xs rounded z-50 bg-green-500 text-white';
            indicator.innerHTML = 'En ligne';

            // Masquer après 3 secondes
            setTimeout(() => {
                indicator.style.display = 'none';
            }, 3000);
        } else {
            indicator.className = 'fixed top-0 right-0 m-2 px-2 py-1 text-xs rounded z-50 bg-orange-500 text-white';
            indicator.innerHTML = 'Hors ligne - Les paiements seront synchronisés plus tard';
            indicator.style.display = 'block';
        }
    }

    /**
     * Gérer les changements d'état de connexion
     */
    handleOnlineStatusChange(event) {
        this.isOnline = navigator.onLine;
        this.updateConnectionIndicator();

        if (this.isOnline && this.pendingPayments.length > 0) {
            this.syncPendingPayments();
        }
    }

    /**
     * Initialiser le formulaire de paiement rapide pour mobile
     */
    initQuickPaymentForm() {
        const form = document.getElementById('payment-form');

        if (!form) return;

        // Ajouter un champ de recherche autocomplete pour les étudiants
        const studentSelector = document.getElementById('student-selector');
        if (studentSelector) {
            // Activer la recherche instantanée
            studentSelector.addEventListener('input', this.debounce(this.searchStudents.bind(this), 300));
        }

        // Capturer la soumission du formulaire
        form.addEventListener('submit', this.handlePaymentSubmit.bind(this));

        // Ajouter un lecteur de code QR si la caméra est disponible
        if ('mediaDevices' in navigator && 'getUserMedia' in navigator.mediaDevices) {
            this.addQRScanner();
        }
    }

    /**
     * Chercher des étudiants en fonction de la saisie
     */
    searchStudents(event) {
        const query = event.target.value;
        if (query.length < 2) return;

        const resultsContainer = document.getElementById('student-search-results');
        if (!resultsContainer) return;

        // En mode hors ligne, rechercher dans la cache
        if (!this.isOnline) {
            const cachedStudents = JSON.parse(localStorage.getItem('cachedStudents') || '[]');
            const filteredStudents = cachedStudents.filter(student =>
                student.fullName.toLowerCase().includes(query.toLowerCase()) ||
                student.student_id.toLowerCase().includes(query.toLowerCase())
            );

            this.renderStudentResults(filteredStudents, resultsContainer);
            return;
        }

        // En ligne, faire une requête AJAX
        fetch(`/api/students/search?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(students => {
                // Mettre en cache pour utilisation hors ligne
                localStorage.setItem('cachedStudents', JSON.stringify(students));
                this.renderStudentResults(students, resultsContainer);
            })
            .catch(error => {
                console.error('Erreur lors de la recherche:', error);
                resultsContainer.innerHTML = '<p class="text-red-500">Erreur lors de la recherche. Vérifiez votre connexion.</p>';
            });
    }

    /**
     * Afficher les résultats de recherche d'étudiants
     */
    renderStudentResults(students, container) {
        if (students.length === 0) {
            container.innerHTML = '<p class="py-2 px-4 text-gray-500">Aucun étudiant trouvé</p>';
            return;
        }

        container.innerHTML = '';

        students.forEach(student => {
            const studentElement = document.createElement('div');
            studentElement.className = 'py-2 px-4 border-b hover:bg-gray-50 cursor-pointer';
            studentElement.innerHTML = `
                <div class="font-medium">${student.fullName}</div>
                <div class="text-sm text-gray-500">${student.student_id} • ${student.field ? student.field.name : 'N/A'}</div>
            `;

            studentElement.addEventListener('click', () => {
                document.getElementById('student-id').value = student.id;
                document.getElementById('student-selector').value = student.fullName;
                container.innerHTML = '';
            });

            container.appendChild(studentElement);
        });
    }

    /**
     * Ajouter un scanner de code QR
     */
    addQRScanner() {
        const scannerButton = document.createElement('button');
        scannerButton.type = 'button';
        scannerButton.className = 'absolute right-2 top-2 p-2 bg-gray-200 rounded-full';
        scannerButton.innerHTML = '<i class="fas fa-qrcode"></i>';
        scannerButton.addEventListener('click', this.activateQRScanner.bind(this));

        const studentSelectorContainer = document.getElementById('student-selector').parentNode;
        studentSelectorContainer.style.position = 'relative';
        studentSelectorContainer.appendChild(scannerButton);
    }

    /**
     * Activer le scanner de code QR
     */
    activateQRScanner() {
        // Cette fonction utiliserait une bibliothèque comme jsQR ou un scanner natif
        // Exemple simplifié:
        alert('La fonctionnalité de scan QR sera bientôt disponible.');
    }

    /**
     * Gérer la soumission du formulaire de paiement
     */
    handlePaymentSubmit(event) {
        // Si hors ligne, stocker le paiement localement
        if (!this.isOnline) {
            event.preventDefault();

            const formData = new FormData(event.target);
            const paymentData = {
                student_id: formData.get('student_id'),
                amount: formData.get('amount'),
                payment_date: formData.get('payment_date') || new Date().toISOString().split('T')[0],
                description: formData.get('description'),
                receipt_number: 'TEMP-' + Date.now(),
                created_at: new Date().toISOString(),
                synced: false
            };

            this.addPendingPayment(paymentData);

            alert('Vous êtes hors ligne. Le paiement a été enregistré localement et sera synchronisé dès que vous serez en ligne.');
            window.location.href = '/payments';
        }
    }

    /**
     * Ajouter un paiement en attente à synchroniser
     */
    addPendingPayment(paymentData) {
        this.pendingPayments.push(paymentData);
        localStorage.setItem('pendingPayments', JSON.stringify(this.pendingPayments));

        // Mettre à jour le compteur de paiements en attente
        this.updatePendingCounter();
    }

    /**
     * Mettre à jour le compteur de paiements en attente
     */
    updatePendingCounter() {
        const pendingCount = this.pendingPayments.length;

        let badge = document.getElementById('pending-payments-badge');
        if (!badge && pendingCount > 0) {
            badge = document.createElement('span');
            badge.id = 'pending-payments-badge';
            badge.className = 'absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center';

            const paymentLink = document.querySelector('a[href*="/payments"]');
            if (paymentLink) {
                paymentLink.style.position = 'relative';
                paymentLink.appendChild(badge);
            }
        }

        if (badge) {
            badge.textContent = pendingCount;
            badge.style.display = pendingCount > 0 ? 'flex' : 'none';
        }
    }

    /**
     * Synchroniser les paiements en attente
     */
    syncPendingPayments() {
        if (this.syncInProgress || this.pendingPayments.length === 0) return;

        this.syncInProgress = true;

        const paymentToSync = [...this.pendingPayments];
        const syncResults = [];

        // Créer une promesse pour chaque paiement à synchroniser
        const syncPromises = paymentToSync.map(payment =>
            fetch('/api/payments/sync', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(payment)
            })
                .then(response => response.json())
                .then(result => {
                    syncResults.push({
                        payment,
                        success: true,
                        result
                    });

                    // Retirer le paiement synchronisé de la liste des paiements en attente
                    this.pendingPayments = this.pendingPayments.filter(p =>
                        p.receipt_number !== payment.receipt_number
                    );
                })
                .catch(error => {
                    console.error('Erreur lors de la synchronisation:', error);
                    syncResults.push({
                        payment,
                        success: false,
                        error
                    });
                })
        );

        // Attendre que toutes les synchronisations soient terminées
        Promise.all(syncPromises)
            .finally(() => {
                localStorage.setItem('pendingPayments', JSON.stringify(this.pendingPayments));
                this.updatePendingCounter();
                this.syncInProgress = false;

                // Afficher un résumé des résultats
                const successCount = syncResults.filter(r => r.success).length;
                if (successCount > 0) {
                    alert(`${successCount} paiement(s) synchronisé(s) avec succès.`);
                }
            });
    }

    /**
     * Enregistrer le service worker pour le mode hors ligne
     */
    registerServiceWorker() {
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js')
                .then(registration => {
                    console.log('Service Worker enregistré avec succès:', registration);
                })
                .catch(error => {
                    console.log('Erreur lors de l\'enregistrement du Service Worker:', error);
                });
        }
    }

    /**
     * Fonction utilitaire pour debounce
     */
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
}

// Exporter une instance du gestionnaire de paiements mobiles
export const mobilePaymentManager = new MobilePaymentManager();

// Initialiser lors du chargement du DOM
document.addEventListener('DOMContentLoaded', () => {
    mobilePaymentManager.init();
}); 