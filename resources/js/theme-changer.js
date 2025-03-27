/**
 * Student Fees Manager - Changement de thème
 * Ce fichier permet de changer les couleurs du thème dynamiquement
 */

document.addEventListener('DOMContentLoaded', function () {
    // Éléments DOM
    const themeForm = document.getElementById('theme-form');
    const themeModal = document.getElementById('theme-modal');

    // Couleurs par défaut
    const defaultColors = {
        primary: '#0A3D62',
        secondary: '#1E5B94',
        success: '#28a745',
        info: '#17a2b8',
        warning: '#ffc107',
        danger: '#dc3545',
        light: '#F5F7FA',
        dark: '#071E3D',
        accent: '#D4AF37',
        primaryText: '#0A3D62',
        secondaryText: '#4A4A4A',
        mutedText: '#6c757d',
        borderColor: '#E0E7EF'
    };

    // Charger les couleurs sauvegardées ou les couleurs par défaut
    const savedTheme = localStorage.getItem('student-fees-theme');
    let currentTheme = savedTheme ? JSON.parse(savedTheme) : defaultColors;

    // Appliquer le thème actuel au chargement
    applyTheme(currentTheme);

    // Créer le bouton de paramètres de thème s'il n'existe pas
    if (!document.getElementById('theme-settings-button')) {
        createThemeButton();
    }

    // Créer le formulaire modal s'il n'existe pas
    if (!document.getElementById('theme-modal')) {
        createThemeModal();
    }

    // Fonction pour appliquer le thème
    function applyTheme(theme) {
        const root = document.documentElement;

        // Appliquer les couleurs principales
        root.style.setProperty('--primary', theme.primary);
        root.style.setProperty('--secondary', theme.secondary);
        root.style.setProperty('--success', theme.success);
        root.style.setProperty('--info', theme.info);
        root.style.setProperty('--warning', theme.warning);
        root.style.setProperty('--danger', theme.danger);
        root.style.setProperty('--light', theme.light);
        root.style.setProperty('--dark', theme.dark);
        root.style.setProperty('--accent', theme.accent);

        // Appliquer les variantes RGB pour les opacités
        root.style.setProperty('--bs-primary-rgb', hexToRgb(theme.primary));
        root.style.setProperty('--bs-secondary-rgb', hexToRgb(theme.secondary));
        root.style.setProperty('--bs-success-rgb', hexToRgb(theme.success));
        root.style.setProperty('--bs-info-rgb', hexToRgb(theme.info));
        root.style.setProperty('--bs-warning-rgb', hexToRgb(theme.warning));
        root.style.setProperty('--bs-danger-rgb', hexToRgb(theme.danger));

        // Appliquer les couleurs de texte
        root.style.setProperty('--text-primary', theme.primaryText);
        root.style.setProperty('--text-secondary', theme.secondaryText);
        root.style.setProperty('--text-muted', theme.mutedText);

        // Appliquer les couleurs de fond correspondantes
        root.style.setProperty('--bg-primary', theme.primary);
        root.style.setProperty('--bg-secondary', theme.secondary);

        // Appliquer les couleurs de bordure
        root.style.setProperty('--border-color', theme.borderColor);

        // Sauvegarder le thème dans le localStorage
        localStorage.setItem('student-fees-theme', JSON.stringify(theme));

        // Mettre à jour le formulaire si disponible
        updateFormValues(theme);
    }

    // Créer le bouton de paramètres de thème
    function createThemeButton() {
        const button = document.createElement('button');
        button.id = 'theme-settings-button';
        button.className = 'btn btn-sm btn-light rounded-circle position-fixed';
        button.style.cssText = 'bottom: 20px; right: 20px; width: 45px; height: 45px; z-index: 1050; box-shadow: 0 2px 10px rgba(0,0,0,0.1);';
        button.innerHTML = '<i class="fas fa-palette"></i>';
        button.setAttribute('data-bs-toggle', 'modal');
        button.setAttribute('data-bs-target', '#theme-modal');

        document.body.appendChild(button);
    }

    // Créer le formulaire modal
    function createThemeModal() {
        const modal = document.createElement('div');
        modal.id = 'theme-modal';
        modal.className = 'modal fade';
        modal.setAttribute('tabindex', '-1');
        modal.setAttribute('aria-labelledby', 'themeModalLabel');
        modal.setAttribute('aria-hidden', 'true');

        modal.innerHTML = `
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="themeModalLabel">Personnaliser le thème</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="theme-form">
              <div class="row mb-4">
                <div class="col-md-12 mb-3">
                  <h6 class="border-bottom pb-2">Couleurs principales</h6>
                </div>
                
                <div class="col-md-3 mb-3">
                  <label for="primary-color" class="form-label">Couleur principale</label>
                  <div class="input-group">
                    <input type="color" class="form-control form-control-color" id="primary-color" value="${currentTheme.primary}">
                    <input type="text" class="form-control" id="primary-color-text" value="${currentTheme.primary}">
                  </div>
                </div>
                
                <div class="col-md-3 mb-3">
                  <label for="secondary-color" class="form-label">Couleur secondaire</label>
                  <div class="input-group">
                    <input type="color" class="form-control form-control-color" id="secondary-color" value="${currentTheme.secondary}">
                    <input type="text" class="form-control" id="secondary-color-text" value="${currentTheme.secondary}">
                  </div>
                </div>
                
                <div class="col-md-3 mb-3">
                  <label for="accent-color" class="form-label">Couleur d'accent</label>
                  <div class="input-group">
                    <input type="color" class="form-control form-control-color" id="accent-color" value="${currentTheme.accent}">
                    <input type="text" class="form-control" id="accent-color-text" value="${currentTheme.accent}">
                  </div>
                </div>
                
                <div class="col-md-3 mb-3">
                  <label for="dark-color" class="form-label">Couleur sombre</label>
                  <div class="input-group">
                    <input type="color" class="form-control form-control-color" id="dark-color" value="${currentTheme.dark}">
                    <input type="text" class="form-control" id="dark-color-text" value="${currentTheme.dark}">
                  </div>
                </div>
              </div>
              
              <div class="row mb-4">
                <div class="col-md-12 mb-3">
                  <h6 class="border-bottom pb-2">Couleurs sémantiques</h6>
                </div>
                
                <div class="col-md-3 mb-3">
                  <label for="success-color" class="form-label">Succès</label>
                  <div class="input-group">
                    <input type="color" class="form-control form-control-color" id="success-color" value="${currentTheme.success}">
                    <input type="text" class="form-control" id="success-color-text" value="${currentTheme.success}">
                  </div>
                </div>
                
                <div class="col-md-3 mb-3">
                  <label for="info-color" class="form-label">Information</label>
                  <div class="input-group">
                    <input type="color" class="form-control form-control-color" id="info-color" value="${currentTheme.info}">
                    <input type="text" class="form-control" id="info-color-text" value="${currentTheme.info}">
                  </div>
                </div>
                
                <div class="col-md-3 mb-3">
                  <label for="warning-color" class="form-label">Avertissement</label>
                  <div class="input-group">
                    <input type="color" class="form-control form-control-color" id="warning-color" value="${currentTheme.warning}">
                    <input type="text" class="form-control" id="warning-color-text" value="${currentTheme.warning}">
                  </div>
                </div>
                
                <div class="col-md-3 mb-3">
                  <label for="danger-color" class="form-label">Danger</label>
                  <div class="input-group">
                    <input type="color" class="form-control form-control-color" id="danger-color" value="${currentTheme.danger}">
                    <input type="text" class="form-control" id="danger-color-text" value="${currentTheme.danger}">
                  </div>
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-12 mb-3">
                  <h6 class="border-bottom pb-2">Actions rapides</h6>
                </div>
                
                <div class="col-md-6 mb-3">
                  <button type="button" class="btn btn-sm btn-outline-secondary w-100" id="reset-theme">
                    <i class="fas fa-undo me-2"></i>Restaurer les couleurs par défaut
                  </button>
                </div>
                
                <div class="col-md-6 mb-3">
                  <button type="button" class="btn btn-sm btn-outline-primary w-100" id="preview-theme">
                    <i class="fas fa-eye me-2"></i>Prévisualiser
                  </button>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="button" class="btn btn-primary" id="save-theme">Enregistrer</button>
          </div>
        </div>
      </div>
    `;

        document.body.appendChild(modal);

        // Initialiser les écouteurs d'événements
        initThemeModalEvents();
    }

    // Initialiser les écouteurs d'événements du modal
    function initThemeModalEvents() {
        const colorInputs = document.querySelectorAll('#theme-form input[type="color"]');
        const textInputs = document.querySelectorAll('#theme-form input[type="text"]');
        const resetButton = document.getElementById('reset-theme');
        const previewButton = document.getElementById('preview-theme');
        const saveButton = document.getElementById('save-theme');

        // Synchroniser les champs couleur et texte
        colorInputs.forEach(input => {
            input.addEventListener('input', function () {
                const textInput = document.getElementById(this.id + '-text');
                textInput.value = this.value;
            });
        });

        textInputs.forEach(input => {
            if (input.id.endsWith('-text')) {
                input.addEventListener('input', function () {
                    const colorInput = document.getElementById(this.id.replace('-text', ''));
                    if (isValidHexColor(this.value)) {
                        colorInput.value = this.value;
                    }
                });
            }
        });

        // Bouton pour réinitialiser le thème
        resetButton.addEventListener('click', function () {
            updateFormValues(defaultColors);
        });

        // Bouton pour prévisualiser le thème
        previewButton.addEventListener('click', function () {
            const theme = getThemeFromForm();
            applyTheme(theme);
        });

        // Bouton pour sauvegarder le thème
        saveButton.addEventListener('click', function () {
            const theme = getThemeFromForm();
            applyTheme(theme);

            // Fermer le modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('theme-modal'));
            modal.hide();
        });
    }

    // Mettre à jour les valeurs du formulaire
    function updateFormValues(theme) {
        for (const [key, value] of Object.entries(theme)) {
            let inputId;

            switch (key) {
                case 'primary': inputId = 'primary-color'; break;
                case 'secondary': inputId = 'secondary-color'; break;
                case 'success': inputId = 'success-color'; break;
                case 'info': inputId = 'info-color'; break;
                case 'warning': inputId = 'warning-color'; break;
                case 'danger': inputId = 'danger-color'; break;
                case 'dark': inputId = 'dark-color'; break;
                case 'accent': inputId = 'accent-color'; break;
                default: continue;
            }

            const colorInput = document.getElementById(inputId);
            const textInput = document.getElementById(inputId + '-text');

            if (colorInput) colorInput.value = value;
            if (textInput) textInput.value = value;
        }
    }

    // Récupérer le thème à partir du formulaire
    function getThemeFromForm() {
        return {
            primary: document.getElementById('primary-color').value,
            secondary: document.getElementById('secondary-color').value,
            success: document.getElementById('success-color').value,
            info: document.getElementById('info-color').value,
            warning: document.getElementById('warning-color').value,
            danger: document.getElementById('danger-color').value,
            dark: document.getElementById('dark-color').value,
            accent: document.getElementById('accent-color').value,
            light: currentTheme.light,
            primaryText: currentTheme.primaryText,
            secondaryText: currentTheme.secondaryText,
            mutedText: currentTheme.mutedText,
            borderColor: currentTheme.borderColor
        };
    }

    // Vérifier si une chaîne est une couleur hexadécimale valide
    function isValidHexColor(color) {
        return /^#([0-9A-F]{3}){1,2}$/i.test(color);
    }

    // Convertir une couleur hexadécimale en RGB
    function hexToRgb(hex) {
        hex = hex.replace('#', '');

        // Convertir les couleurs au format court (#RGB) en format long (#RRGGBB)
        if (hex.length === 3) {
            hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
        }

        const r = parseInt(hex.substring(0, 2), 16);
        const g = parseInt(hex.substring(2, 4), 16);
        const b = parseInt(hex.substring(4, 6), 16);

        return `${r}, ${g}, ${b}`;
    }
}); 