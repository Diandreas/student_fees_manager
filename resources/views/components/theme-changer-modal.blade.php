<!-- Modal pour personnaliser les couleurs du thème -->
<div class="modal fade" id="theme-modal" tabindex="-1" aria-labelledby="themeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold" id="themeModalLabel">
                    <i class="fas fa-palette me-2"></i>Personnalisation du thème
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="theme-form">
                    <div class="mb-3">
                        <label for="primary-color" class="form-label">Couleur principale</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-square" id="primary-color-preview"></i></span>
                            <input type="color" class="form-control form-control-color w-100" id="primary-color" value="#0A3D62">
                        </div>
                        <div class="form-text">Couleur utilisée pour les en-têtes, boutons principaux, etc.</div>
                    </div>

                    <div class="mb-3">
                        <label for="secondary-color" class="form-label">Couleur secondaire</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-square" id="secondary-color-preview"></i></span>
                            <input type="color" class="form-control form-control-color w-100" id="secondary-color" value="#1E5B94">
                        </div>
                        <div class="form-text">Couleur utilisée pour les éléments secondaires</div>
                    </div>

                    <div class="mb-3">
                        <label for="accent-color" class="form-label">Couleur d'accent</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-square" id="accent-color-preview"></i></span>
                            <input type="color" class="form-control form-control-color w-100" id="accent-color" value="#D4AF37">
                        </div>
                        <div class="form-text">Couleur utilisée pour mettre en valeur certains éléments</div>
                    </div>

                    <div class="mb-3">
                        <label for="dark-blue" class="form-label">Couleur foncée</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-square" id="dark-blue-preview"></i></span>
                            <input type="color" class="form-control form-control-color w-100" id="dark-blue" value="#071E3D">
                        </div>
                        <div class="form-text">Couleur utilisée pour le pied de page et certains textes</div>
                    </div>
                </form>

                <div class="mt-3">
                    <h6 class="mb-2">Aperçu</h6>
                    <div class="d-flex gap-2 mb-2">
                        <button class="btn btn-sm preview-primary">Bouton principal</button>
                        <button class="btn btn-sm preview-secondary">Bouton secondaire</button>
                        <button class="btn btn-sm preview-accent">Bouton accent</button>
                    </div>
                    <div class="preview-card p-2 rounded">
                        <div class="preview-header p-2 rounded">En-tête</div>
                        <div class="preview-content p-2 mt-1">
                            <p class="mb-1 preview-text">Texte d'exemple</p>
                            <div class="preview-accent-element"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary-custom" id="save-theme">
                    <i class="fas fa-save me-2"></i>Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const primaryColor = document.getElementById('primary-color');
    const secondaryColor = document.getElementById('secondary-color');
    const accentColor = document.getElementById('accent-color');
    const darkBlue = document.getElementById('dark-blue');
    
    const primaryPreview = document.getElementById('primary-color-preview');
    const secondaryPreview = document.getElementById('secondary-color-preview');
    const accentPreview = document.getElementById('accent-color-preview');
    const darkBluePreview = document.getElementById('dark-blue-preview');
    
    const previewPrimary = document.querySelector('.preview-primary');
    const previewSecondary = document.querySelector('.preview-secondary');
    const previewAccent = document.querySelector('.preview-accent');
    const previewHeader = document.querySelector('.preview-header');
    const previewCard = document.querySelector('.preview-card');
    const previewText = document.querySelector('.preview-text');
    const previewAccentElement = document.querySelector('.preview-accent-element');
    
    // Initialiser les couleurs de l'aperçu
    updatePreview();
    
    // Mettre à jour l'aperçu lorsque les couleurs changent
    primaryColor.addEventListener('input', updatePreview);
    secondaryColor.addEventListener('input', updatePreview);
    accentColor.addEventListener('input', updatePreview);
    darkBlue.addEventListener('input', updatePreview);
    
    // Fonction pour mettre à jour l'aperçu
    function updatePreview() {
        // Mettre à jour les icônes de prévisualisation
        primaryPreview.style.color = primaryColor.value;
        secondaryPreview.style.color = secondaryColor.value;
        accentPreview.style.color = accentColor.value;
        darkBluePreview.style.color = darkBlue.value;
        
        // Mettre à jour les boutons
        previewPrimary.style.backgroundColor = primaryColor.value;
        previewPrimary.style.color = 'white';
        previewPrimary.style.border = 'none';
        
        previewSecondary.style.backgroundColor = 'transparent';
        previewSecondary.style.color = primaryColor.value;
        previewSecondary.style.border = `1px solid ${primaryColor.value}`;
        
        previewAccent.style.backgroundColor = accentColor.value;
        previewAccent.style.color = darkBlue.value;
        previewAccent.style.border = 'none';
        
        // Mettre à jour la carte de prévisualisation
        previewCard.style.border = `1px solid ${secondaryColor.value}20`;
        previewCard.style.backgroundColor = 'white';
        
        previewHeader.style.backgroundColor = primaryColor.value;
        previewHeader.style.color = 'white';
        
        previewText.style.color = darkBlue.value;
        
        previewAccentElement.style.backgroundColor = accentColor.value;
        previewAccentElement.style.height = '10px';
        previewAccentElement.style.width = '50px';
        previewAccentElement.style.borderRadius = '5px';
    }
    
    // Enregistrer les modifications
    document.getElementById('save-theme').addEventListener('click', function() {
        // Mettre à jour les variables CSS
        document.documentElement.style.setProperty('--primary-color', primaryColor.value);
        document.documentElement.style.setProperty('--secondary-color', secondaryColor.value);
        document.documentElement.style.setProperty('--accent-color', accentColor.value);
        document.documentElement.style.setProperty('--dark-blue', darkBlue.value);
        
        // Stocker les préférences dans localStorage
        localStorage.setItem('theme-primary', primaryColor.value);
        localStorage.setItem('theme-secondary', secondaryColor.value);
        localStorage.setItem('theme-accent', accentColor.value);
        localStorage.setItem('theme-dark-blue', darkBlue.value);
        
        // Fermer le modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('theme-modal'));
        modal.hide();
        
        // Afficher une notification
        alert('Les couleurs du thème ont été mises à jour avec succès !');
    });
});
</script> 