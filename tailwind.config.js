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
                    100: '#e0eefe',
                    200: '#c0ddfd',
                    300: '#92c5fb',
                    400: '#5ea6f8',
                    500: '#3a86f5',
                    600: '#1a56db', /* couleur principale */
                    700: '#1a4bbd',
                    800: '#1c419a',
                    900: '#1c3879',
                    950: '#0f1f42',
                },
                'secondary': {
                    50: '#f2f7fd',
                    100: '#e5eefa',
                    200: '#c5dcf5',
                    300: '#95c0ec',
                    400: '#5e9cdf',
                    500: '#3a7dd3',
                    600: '#2964bd',
                    700: '#254f99',
                    800: '#25437f',
                    900: '#233a69',
                    950: '#172340',
                },
                'accent': '#2563eb',
                'success': '#10b981',
                'warning': '#f59e0b',
                'danger': '#ef4444',
                'info': '#3b82f6',
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
