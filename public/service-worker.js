// Service Worker pour Student Fees Manager

const CACHE_NAME = 'sfm-cache-v1';
const urlsToCache = [
    '/',
    '/dashboard',
    '/payments',
    '/payments/create',
    '/payments/quick',
    '/css/app.css',
    '/js/app.js',
    '/images/default-student.png',
    '/images/logo.png',
    '/favicon.ico',
    'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css'
];

// Installation du Service Worker
self.addEventListener('install', event => {
    console.log('Service Worker: Installation');
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Service Worker: Mise en cache des ressources');
                return cache.addAll(urlsToCache);
            })
    );
});

// Activation et nettoyage des anciens caches
self.addEventListener('activate', event => {
    console.log('Service Worker: Activation');
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) {
                        console.log('Service Worker: Suppression de l\'ancien cache', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    return self.clients.claim();
});

// Interception des requêtes réseau
self.addEventListener('fetch', event => {
    // Seuls les GET sont mis en cache
    if (event.request.method !== 'GET') return;

    // Ignorer les requêtes vers l'API sauf pour la recherche d'étudiants 
    // qui peut être utile hors ligne
    if (event.request.url.includes('/api/') &&
        !event.request.url.includes('/api/students/search')) {
        return;
    }

    event.respondWith(
        caches.match(event.request)
            .then(response => {
                // Cache hit - retourner la réponse
                if (response) {
                    console.log('Service Worker: Ressource trouvée dans le cache:', event.request.url);

                    // Tenter de mettre à jour le cache en arrière-plan
                    // mais retourner immédiatement la version mise en cache
                    fetch(event.request)
                        .then(networkResponse => {
                            if (networkResponse.ok) {
                                caches.open(CACHE_NAME)
                                    .then(cache => cache.put(event.request, networkResponse));
                            }
                        })
                        .catch(error => console.log('Échec de la mise à jour du cache:', error));

                    return response;
                }

                console.log('Service Worker: Ressource non trouvée dans le cache:', event.request.url);

                // Pas dans le cache, faire la requête réseau
                return fetch(event.request)
                    .then(response => {
                        // Vérifier si on a reçu une réponse valide
                        if (!response || response.status !== 200 || response.type !== 'basic') {
                            return response;
                        }

                        // Cloner la réponse car elle ne peut être utilisée qu'une fois
                        const responseToCache = response.clone();

                        caches.open(CACHE_NAME)
                            .then(cache => {
                                console.log('Service Worker: Mise en cache de nouvelle ressource:', event.request.url);
                                cache.put(event.request, responseToCache);
                            });

                        return response;
                    })
                    .catch(error => {
                        console.log('Service Worker: Erreur réseau pour:', event.request.url, error);

                        // Si c'est une page HTML, retourner la page hors ligne
                        if (event.request.headers.get('accept').includes('text/html')) {
                            return caches.match('/offline.html');
                        }

                        // Pour les autres ressources, retourner une erreur
                        return new Response('Ressource non disponible hors ligne', {
                            status: 503,
                            statusText: 'Service Unavailable',
                            headers: new Headers({
                                'Content-Type': 'text/plain'
                            })
                        });
                    });
            })
    );
});

// Gérer les messages du client (comme les paiements hors ligne)
self.addEventListener('message', event => {
    if (event.data.type === 'PENDING_PAYMENT') {
        // Stocker le paiement pour synchronisation
        const pendingPayments = JSON.parse(localStorage.getItem('pendingPayments') || '[]');
        pendingPayments.push(event.data.payment);
        localStorage.setItem('pendingPayments', JSON.stringify(pendingPayments));

        // Confirmer la réception
        event.source.postMessage({
            type: 'PAYMENT_CACHED',
            id: event.data.payment.id
        });
    }
}); 