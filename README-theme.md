# Personnalisation du thème Bootstrap - Student Fees Manager

Ce guide vous explique comment personnaliser les couleurs et le thème de l'application Student Fees Manager.

## Fichiers de personnalisation

Nous avons créé plusieurs fichiers pour vous permettre de personnaliser facilement les couleurs de l'application :

1. **`resources/css/theme.css`** - Fichier CSS qui définit les variables de couleur et personnalise les composants Bootstrap.
2. **`resources/css/custom-bootstrap.scss`** - Fichier SCSS qui personnalise Bootstrap depuis sa source.
3. **`resources/js/theme-changer.js`** - Fichier JavaScript qui permet de changer les couleurs du thème dynamiquement via une interface utilisateur.

## Méthodes de personnalisation

### Méthode 1 : Édition directe des fichiers CSS/SCSS

Pour personnaliser les couleurs du thème de manière permanente, vous pouvez modifier directement les fichiers CSS/SCSS :

1. Ouvrez le fichier `resources/css/theme.css` ou `resources/css/custom-bootstrap.scss`
2. Modifiez les valeurs des variables de couleur selon vos besoins
3. Recompilez les assets (si vous utilisez SCSS) avec la commande :
   ```
   npm run dev
   ```
   ou
   ```
   npm run prod
   ```

### Méthode 2 : Utilisation de l'interface utilisateur de changement de thème

L'application inclut une interface utilisateur de changement de thème qui permet de personnaliser les couleurs sans avoir à modifier le code :

1. Assurez-vous que le fichier `theme-changer.js` est bien chargé dans votre layout principal
2. Un bouton de personnalisation apparaîtra en bas à droite de l'écran
3. Cliquez sur ce bouton pour ouvrir le panel de personnalisation
4. Modifiez les couleurs à l'aide des sélecteurs de couleur
5. Prévisualisez vos changements en cliquant sur "Prévisualiser"
6. Sauvegardez votre thème en cliquant sur "Enregistrer"

Les préférences de couleur sont sauvegardées dans le localStorage du navigateur et seront restaurées à chaque visite.

## Intégration dans l'application

### Intégration du CSS

Pour intégrer le fichier CSS dans votre application, ajoutez la ligne suivante à votre fichier de layout principal (généralement `resources/views/layouts/app.blade.php`) dans la section `<head>` :

```html
<link href="{{ asset('css/theme.css') }}" rel="stylesheet">
```

### Intégration du JavaScript

Pour intégrer le changeur de thème JavaScript, ajoutez les lignes suivantes avant la fermeture de la balise `</body>` dans votre layout principal :

```html
<script src="{{ asset('js/theme-changer.js') }}"></script>
```

## Structure des variables de couleur

Les variables de couleur définies sont les suivantes :

```css
:root {
  /* Couleurs principales */
  --primary: #0A3D62;       /* Bleu principal */
  --secondary: #1E5B94;     /* Bleu secondaire */
  --success: #28a745;       /* Vert */
  --info: #17a2b8;          /* Bleu clair */
  --warning: #ffc107;       /* Jaune */
  --danger: #dc3545;        /* Rouge */
  --light: #F5F7FA;         /* Gris clair */
  --dark: #071E3D;          /* Bleu foncé */
  
  /* Couleur personnalisée */
  --accent: #D4AF37;        /* Or / Accent */
}
```

## Compilation des assets

Si vous avez modifié le fichier SCSS, vous devez recompiler les assets de l'application. Voici comment procéder :

1. Assurez-vous que Node.js et npm sont installés sur votre machine
2. Naviguez vers le répertoire racine de l'application
3. Exécutez la commande suivante pour installer les dépendances :
   ```
   npm install
   ```
4. Exécutez l'une des commandes suivantes pour compiler les assets :
   - Pour le développement (non minifié) :
     ```
     npm run dev
     ```
   - Pour la production (minifié) :
     ```
     npm run prod
     ```

## Ajout à votre projet Laravel

Pour ajouter ces fichiers à votre projet Laravel existant, suivez ces étapes :

1. Copiez le fichier `theme.css` dans le répertoire `resources/css/`
2. Copiez le fichier `custom-bootstrap.scss` dans le répertoire `resources/css/`
3. Copiez le fichier `theme-changer.js` dans le répertoire `resources/js/`
4. Modifiez votre fichier `webpack.mix.js` pour inclure ces fichiers :
   ```js
   mix.js('resources/js/app.js', 'public/js')
      .js('resources/js/theme-changer.js', 'public/js')
      .sass('resources/css/custom-bootstrap.scss', 'public/css')
      .css('resources/css/theme.css', 'public/css');
   ```
5. Recompilez les assets avec `npm run dev` ou `npm run prod`
6. Intégrez les fichiers CSS et JavaScript dans votre layout comme indiqué ci-dessus

## Adaptation à Vite (Laravel 9+)

Si vous utilisez Vite avec Laravel 9 ou supérieur au lieu de Laravel Mix, voici comment adapter les fichiers à votre configuration :

1. Ajoutez les imports nécessaires dans `resources/js/app.js` :
   ```js
   import '../css/theme.css';
   import './theme-changer.js';
   ```

2. Mettez à jour votre fichier `vite.config.js` pour inclure les fichiers SCSS :
   ```js
   import { defineConfig } from 'vite';
   import laravel from 'laravel-vite-plugin';

   export default defineConfig({
     plugins: [
       laravel({
         input: [
           'resources/css/app.css',
           'resources/css/custom-bootstrap.scss',
           'resources/js/app.js',
         ],
         refresh: true,
       }),
     ],
   });
   ```

## Accès à l'exemple complet

Pour un exemple complet d'utilisation des fichiers de personnalisation de thème, consultez les fichiers fournis dans le répertoire du projet. 