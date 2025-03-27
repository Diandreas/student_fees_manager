<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold text-primary-custom">Thèmes rapides</h5>
        <p class="text-muted mb-0">Appliquez un thème pré-configuré en un clic</p>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-auto mb-3">
                <div class="theme-preview" data-theme="classic">
                    <div class="theme-colors">
                        <span style="background-color: #0A3D62"></span>
                        <span style="background-color: #1E5B94"></span>
                        <span style="background-color: #D4AF37"></span>
                    </div>
                    <div class="theme-name">Classique</div>
                    <button class="btn btn-sm btn-primary-custom apply-theme" data-theme="classic" data-primary="#0A3D62" data-secondary="#1E5B94" data-accent="#D4AF37" data-header="#071E3D">Appliquer</button>
                </div>
            </div>
            
            <div class="col-auto mb-3">
                <div class="theme-preview" data-theme="modern">
                    <div class="theme-colors">
                        <span style="background-color: #1A237E"></span>
                        <span style="background-color: #3949AB"></span>
                        <span style="background-color: #00BCD4"></span>
                    </div>
                    <div class="theme-name">Moderne</div>
                    <button class="btn btn-sm btn-primary-custom apply-theme" data-theme="modern" data-primary="#1A237E" data-secondary="#3949AB" data-accent="#00BCD4" data-header="#0D1B2A">Appliquer</button>
                </div>
            </div>
            
            <div class="col-auto mb-3">
                <div class="theme-preview" data-theme="nature">
                    <div class="theme-colors">
                        <span style="background-color: #2E7D32"></span>
                        <span style="background-color: #388E3C"></span>
                        <span style="background-color: #FDD835"></span>
                    </div>
                    <div class="theme-name">Nature</div>
                    <button class="btn btn-sm btn-primary-custom apply-theme" data-theme="nature" data-primary="#2E7D32" data-secondary="#388E3C" data-accent="#FDD835" data-header="#1B5E20">Appliquer</button>
                </div>
            </div>
            
            <div class="col-auto mb-3">
                <div class="theme-preview" data-theme="sunset">
                    <div class="theme-colors">
                        <span style="background-color: #C62828"></span>
                        <span style="background-color: #E53935"></span>
                        <span style="background-color: #FFA000"></span>
                    </div>
                    <div class="theme-name">Coucher de soleil</div>
                    <button class="btn btn-sm btn-primary-custom apply-theme" data-theme="sunset" data-primary="#C62828" data-secondary="#E53935" data-accent="#FFA000" data-header="#7B1FA2">Appliquer</button>
                </div>
            </div>
            
            <div class="col-auto mb-3">
                <div class="theme-preview" data-theme="ocean">
                    <div class="theme-colors">
                        <span style="background-color: #0277BD"></span>
                        <span style="background-color: #039BE5"></span>
                        <span style="background-color: #4DD0E1"></span>
                    </div>
                    <div class="theme-name">Océan</div>
                    <button class="btn btn-sm btn-primary-custom apply-theme" data-theme="ocean" data-primary="#0277BD" data-secondary="#039BE5" data-accent="#4DD0E1" data-header="#01579B">Appliquer</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .theme-preview {
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 10px;
        width: 150px;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .theme-preview:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .theme-colors {
        display: flex;
        justify-content: center;
        margin-bottom: 10px;
    }
    
    .theme-colors span {
        width: 25px;
        height: 25px;
        border-radius: 50%;
        margin: 0 5px;
    }
    
    .theme-name {
        margin-bottom: 10px;
        font-weight: 500;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const themeButtons = document.querySelectorAll('.apply-theme');
        
        themeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const theme = this.dataset.theme;
                const primaryColor = this.dataset.primary;
                const secondaryColor = this.dataset.secondary;
                const accentColor = this.dataset.accent;
                const headerColor = this.dataset.header;
                
                // Appliquer les couleurs
                document.documentElement.style.setProperty('--primary-color', primaryColor);
                document.documentElement.style.setProperty('--secondary-color', secondaryColor);
                document.documentElement.style.setProperty('--accent-color', accentColor);
                document.documentElement.style.setProperty('--header-color', headerColor);
                
                // Sauvegarder les préférences dans localStorage
                localStorage.setItem('theme-primary', primaryColor);
                localStorage.setItem('theme-secondary', secondaryColor);
                localStorage.setItem('theme-accent', accentColor);
                localStorage.setItem('theme-header', headerColor);
                
                // Mettre à jour les champs du formulaire si on est sur la page des paramètres
                const themeColorInput = document.getElementById('theme_color');
                const secondaryColorInput = document.getElementById('secondary_color');
                const headerColorInput = document.getElementById('header_color');
                
                if (themeColorInput) themeColorInput.value = primaryColor;
                if (secondaryColorInput) secondaryColorInput.value = secondaryColor;
                if (headerColorInput) headerColorInput.value = headerColor;
                
                // Mettre à jour les champs texte associés
                const themeColorText = document.getElementById('theme_color_text');
                const secondaryColorText = document.getElementById('secondary_color_text');
                const headerColorText = document.getElementById('header_color_text');
                
                if (themeColorText) themeColorText.value = primaryColor;
                if (secondaryColorText) secondaryColorText.value = secondaryColor;
                if (headerColorText) headerColorText.value = headerColor;
                
                // Feedback visuel
                const originalText = this.textContent;
                this.textContent = 'Appliqué !';
                setTimeout(() => {
                    this.textContent = originalText;
                }, 1500);
            });
        });
    });
</script> 