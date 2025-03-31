import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'primary': {
                    50: '#f0f7ff',
                    100: '#e0effe',
                    200: '#bae6fd',
                    300: '#7dd3fc',
                    400: '#38bdf8',
                    500: '#0ea5e9',
                    600: '#0284c7', /* couleur principale */
                    700: '#0369a1',
                    800: '#075985',
                    900: '#0c4a6e',
                    950: '#082f49',
                },
                'secondary': {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    200: '#bae6fd',
                    300: '#7dd3fc',
                    400: '#38bdf8',
                    500: '#0ea5e9',
                    600: '#0284c7',
                    700: '#0369a1',
                    800: '#075985',
                    900: '#0c4a6e',
                    950: '#082f49',
                },
                'accent': '#0ea5e9',
                'success': '#0284c7',
                'warning': '#38bdf8',
                'danger': '#0369a1',
                'info': '#7dd3fc',

                // Remplacer les couleurs sémantiques standards par des nuances de bleu
                'green': {
                    50: '#f0f9ff',  // bleu très clair
                    100: '#e0f2fe', // bleu très clair
                    200: '#bae6fd', // bleu clair
                    300: '#7dd3fc', // bleu clair
                    400: '#38bdf8', // bleu moyen
                    500: '#0ea5e9', // bleu
                    600: '#0284c7', // bleu foncé
                    700: '#0369a1', // bleu foncé
                    800: '#075985', // bleu très foncé
                    900: '#0c4a6e', // bleu très foncé
                },
                'red': {
                    50: '#eff6ff',  // bleu très clair 
                    100: '#dbeafe', // bleu très clair
                    200: '#bfdbfe', // bleu clair
                    300: '#93c5fd', // bleu clair
                    400: '#60a5fa', // bleu moyen
                    500: '#3b82f6', // bleu
                    600: '#2563eb', // bleu foncé
                    700: '#1d4ed8', // bleu foncé
                    800: '#1e40af', // bleu très foncé
                    900: '#1e3a8a', // bleu très foncé
                },
                'yellow': {
                    50: '#f0f7ff',  // bleu très clair
                    100: '#e0effe', // bleu très clair  
                    200: '#c0ddfd', // bleu clair
                    300: '#92c5fb', // bleu clair
                    400: '#5ea6f8', // bleu moyen
                    500: '#3a86f5', // bleu
                    600: '#1a56db', // bleu foncé
                    700: '#1a4bbd', // bleu foncé
                    800: '#1c419a', // bleu très foncé
                    900: '#1c3879', // bleu très foncé
                },
                'amber': {
                    50: '#f0f7ff',  // bleu très clair
                    100: '#e0effe', // bleu très clair
                    200: '#c0ddfd', // bleu clair
                    300: '#92c5fb', // bleu clair
                    400: '#5ea6f8', // bleu moyen
                    500: '#3a86f5', // bleu
                    600: '#1a56db', // bleu foncé
                    700: '#1a4bbd', // bleu foncé
                    800: '#1c419a', // bleu très foncé
                    900: '#1c3879', // bleu très foncé
                },
            },
            borderRadius: {
                'sm': '0.25rem',
                DEFAULT: '0.375rem',
                'md': '0.5rem',
                'lg': '0.75rem',
                'xl': '1rem',
                '2xl': '1.5rem',
            },
            boxShadow: {
                'sm': '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
                DEFAULT: '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
                'md': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                'lg': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
                'xl': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
};
