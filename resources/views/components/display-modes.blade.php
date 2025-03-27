{{-- resources/views/components/display-modes.blade.php --}}
@props([
    'id' => 'displayContainer', 
    'defaultMode' => 'list',
    'savePreference' => true,
    'animateTransition' => true,
    'showLabels' => false,
    'customModes' => null,
    'themeColor' => 'primary'
])

@php
    $baseClass = 'display-mode-switcher d-flex align-items-center mb-3 rounded p-1 bg-light';
    $btnClass = "btn-sm mx-1 d-flex align-items-center justify-content-center";
    $activeClass = "btn-$themeColor";
    $inactiveClass = "btn-outline-$themeColor";
    
    // Modes par défaut - suppression du mode grid
    $modes = [
        'list' => [
            'icon' => 'fas fa-list',
            'label' => 'Liste'
        ],
        'card' => [
            'icon' => 'fas fa-grip-horizontal',
            'label' => 'Cartes'
        ]
    ];
    
    // Fusionner avec des modes personnalisés si fournis
    if ($customModes && is_array($customModes)) {
        $modes = array_merge($modes, $customModes);
    }
@endphp

<div {{ $attributes->merge(['class' => $baseClass]) }} data-target="{{ $id }}" id="switcher-{{ $id }}">
    @foreach ($modes as $mode => $config)
        <button type="button" 
            class="btn {{ $mode === $defaultMode ? $activeClass : $inactiveClass }} {{ $btnClass }}"
            data-mode="{{ $mode }}" 
            title="{{ $config['label'] }}">
            <i class="{{ $config['icon'] }}"></i>
            @if($showLabels)
                <span class="ms-1 d-none d-sm-inline">{{ $config['label'] }}</span>
            @endif
        </button>
    @endforeach
</div>

{{-- Ajouter le CSS nécessaire pour les modes d'affichage --}}
<style>
    /* Styles de base pour tous les conteneurs */
    [id="{{ $id }}"] {
        transition: all 0.3s ease-in-out;
    }
    
    /* Style pour le mode liste */
    [id="{{ $id }}"].mode-list .list-item {
        display: flex;
        width: 100%;
        margin-bottom: 0.5rem;
        border-bottom: 1px solid #eee;
        padding-bottom: 0.5rem;
    }
    
    /* Style pour le mode carte */
    [id="{{ $id }}"].mode-card {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    [id="{{ $id }}"].mode-card .list-item {
        flex: 0 0 calc(33.333% - 1rem);
        display: flex;
        flex-direction: column;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        border-radius: 0.25rem;
        overflow: hidden;
        transition: transform 0.2s;
    }
    
    [id="{{ $id }}"].mode-card .list-item:hover {
        transform: translateY(-5px);
    }
    
    /* Responsive */
    @media (max-width: 992px) {
        [id="{{ $id }}"].mode-card .list-item {
            flex: 0 0 calc(50% - 1rem);
        }
    }
    
    @media (max-width: 576px) {
        [id="{{ $id }}"].mode-card .list-item {
            flex: 0 0 100%;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        initDisplayModeSwitcher('{{ $id }}', '{{ $defaultMode }}', {{ $savePreference ? 'true' : 'false' }}, {{ $animateTransition ? 'true' : 'false' }});
    });

    function initDisplayModeSwitcher(containerId, defaultMode, savePreference, animateTransition) {
        const container = document.getElementById(containerId);
        const switcher = document.querySelector(`[data-target="${containerId}"]`);
        
        if (!container || !switcher) {
            console.warn(`DisplayModeSwitcher: Container or switcher for ${containerId} not found`);
            return;
        }
        
        // Définir les classes d'état actif/inactif
        const activeClass = switcher.querySelector('.btn').classList[1];
        const inactiveClass = switcher.querySelector('.btn:not(.' + activeClass + ')').classList[1];
        
        // Fonction pour appliquer un mode
        function applyMode(mode) {
            // Supprimer toutes les classes de mode existantes
            const modeClasses = Array.from(container.classList)
                .filter(className => className.startsWith('mode-'));
            
            modeClasses.forEach(className => container.classList.remove(className));
            
            // Ajouter la nouvelle classe de mode
            container.classList.add('mode-' + mode);
            
            // Mettre à jour les boutons actifs
            switcher.querySelectorAll('.btn').forEach(btn => {
                if (btn.getAttribute('data-mode') === mode) {
                    btn.classList.remove(inactiveClass);
                    btn.classList.add(activeClass);
                } else {
                    btn.classList.remove(activeClass);
                    btn.classList.add(inactiveClass);
                }
            });
            
            // Déclencher un événement personnalisé
            container.dispatchEvent(new CustomEvent('displayModeChanged', { 
                detail: { mode: mode }
            }));
            
            // Si l'animation est activée, ajouter une petite animation
            if (animateTransition) {
                container.style.opacity = '0';
                setTimeout(() => {
                    container.style.opacity = '1';
                }, 150);
            }
        }
        
        // Initialiser avec le mode par défaut
        if (!container.classList.contains('mode-' + defaultMode)) {
            container.classList.add('mode-' + defaultMode);
        }
        
        // Récupérer le mode sauvegardé si disponible et si savePreference est activé
        if (savePreference) {
            const savedMode = localStorage.getItem('display-mode-' + containerId);
            if (savedMode) {
                applyMode(savedMode);
            }
        }
        
        // Ajouter les écouteurs d'événements aux boutons
        switcher.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const mode = this.getAttribute('data-mode');
                applyMode(mode);
                
                // Sauvegarder la préférence si l'option est activée
                if (savePreference) {
                    localStorage.setItem('display-mode-' + containerId, mode);
                }
            });
        });
        
        // Exposer l'API publique
        window.displayModeSwitcher = window.displayModeSwitcher || {};
        window.displayModeSwitcher[containerId] = {
            setMode: applyMode,
            getMode: () => {
                const modeClass = Array.from(container.classList)
                    .find(className => className.startsWith('mode-'));
                return modeClass ? modeClass.replace('mode-', '') : defaultMode;
            },
            getContainer: () => container,
            getSwitcher: () => switcher
        };
    }
</script>