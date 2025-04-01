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
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#22c55e',
                    600: '#16a34a', /* couleur principale */
                    700: '#15803d',
                    800: '#166534',
                    900: '#14532d',
                    950: '#052e16',
                },
                'secondary': {
                    50: '#ecfdf5',
                    100: '#d1fae5',
                    200: '#a7f3d0',
                    300: '#6ee7b7',
                    400: '#34d399',
                    500: '#10b981',
                    600: '#059669',
                    700: '#047857',
                    800: '#065f46',
                    900: '#064e3b',
                    950: '#022c22',
                },
                'accent': '#22c55e',
                'success': '#16a34a',
                'warning': '#4ade80',
                'danger': '#15803d',
                'info': '#86efac',

                // Remplacer les couleurs sémantiques standards par des nuances de vert
                'green': {
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#22c55e',
                    600: '#16a34a',
                    700: '#15803d',
                    800: '#166534',
                    900: '#14532d',
                    950: '#052e16',
                },
                'red': {
                    50: '#f0fdf4',  // vert très clair 
                    100: '#dcfce7', // vert très clair
                    200: '#bbf7d0', // vert clair
                    300: '#86efac', // vert clair
                    400: '#4ade80', // vert moyen
                    500: '#22c55e', // vert
                    600: '#16a34a', // vert foncé
                    700: '#15803d', // vert foncé
                    800: '#166534', // vert très foncé
                    900: '#14532d', // vert très foncé
                    950: '#052e16',
                },
                'yellow': {
                    50: '#f0fdf5',  // vert très clair
                    100: '#dcfce8', // vert très clair  
                    200: '#bbf7d1', // vert clair
                    300: '#86efad', // vert clair
                    400: '#4ade81', // vert moyen
                    500: '#22c55f', // vert
                    600: '#16a34b', // vert foncé
                    700: '#15803e', // vert foncé
                    800: '#166535', // vert très foncé
                    900: '#14532e', // vert très foncé
                    950: '#052e17',
                },
                'amber': {
                    50: '#ecfdf5',  // vert très clair
                    100: '#d1fae5', // vert très clair
                    200: '#a7f3d0', // vert clair
                    300: '#6ee7b7', // vert clair
                    400: '#34d399', // vert moyen
                    500: '#10b981', // vert
                    600: '#059669', // vert foncé
                    700: '#047857', // vert foncé
                    800: '#065f46', // vert très foncé
                    900: '#064e3b', // vert très foncé
                    950: '#022c22',
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