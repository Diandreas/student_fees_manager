/**
 * Script d'initialisation du changeur de thème
 * Charge les préférences de couleurs stockées localement
 */
document.addEventListener('DOMContentLoaded', function () {
    // Vérifier s'il y a des préférences de couleurs stockées
    const storedPrimaryColor = localStorage.getItem('theme-primary');
    const storedSecondaryColor = localStorage.getItem('theme-secondary');
    const storedAccentColor = localStorage.getItem('theme-accent');
    const storedDarkBlue = localStorage.getItem('theme-dark-blue');

    // Appliquer les couleurs stockées s'il y en a
    if (storedPrimaryColor) {
        document.documentElement.style.setProperty('--primary-color', storedPrimaryColor);

        // Mettre à jour les champs du formulaire s'ils existent
        const primaryColorInput = document.getElementById('primary-color');
        if (primaryColorInput) {
            primaryColorInput.value = storedPrimaryColor;
        }
    }

    if (storedSecondaryColor) {
        document.documentElement.style.setProperty('--secondary-color', storedSecondaryColor);

        const secondaryColorInput = document.getElementById('secondary-color');
        if (secondaryColorInput) {
            secondaryColorInput.value = storedSecondaryColor;
        }
    }

    if (storedAccentColor) {
        document.documentElement.style.setProperty('--accent-color', storedAccentColor);

        const accentColorInput = document.getElementById('accent-color');
        if (accentColorInput) {
            accentColorInput.value = storedAccentColor;
        }
    }

    if (storedDarkBlue) {
        document.documentElement.style.setProperty('--dark-blue', storedDarkBlue);

        const darkBlueInput = document.getElementById('dark-blue');
        if (darkBlueInput) {
            darkBlueInput.value = storedDarkBlue;
        }
    }

    // Initialiser l'aperçu si on est sur la page avec le modal
    const themeModal = document.getElementById('theme-modal');
    if (themeModal) {
        const primaryPreview = document.getElementById('primary-color-preview');
        const secondaryPreview = document.getElementById('secondary-color-preview');
        const accentPreview = document.getElementById('accent-color-preview');
        const darkBluePreview = document.getElementById('dark-blue-preview');

        if (primaryPreview && storedPrimaryColor) {
            primaryPreview.style.color = storedPrimaryColor;
        }

        if (secondaryPreview && storedSecondaryColor) {
            secondaryPreview.style.color = storedSecondaryColor;
        }

        if (accentPreview && storedAccentColor) {
            accentPreview.style.color = storedAccentColor;
        }

        if (darkBluePreview && storedDarkBlue) {
            darkBluePreview.style.color = storedDarkBlue;
        }
    }
}); 