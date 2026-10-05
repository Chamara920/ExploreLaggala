import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                amber: {
                    950: '#451a03',
                },
                emerald: {
                    950: '#022c22',
                },
                teal: {
                    950: '#042f2e',
                },
                slate: {
                    950: '#020617',
                },
                stone: {
                    950: '#0c0a09',
                },
            },
        },
    },

    plugins: [forms],
};
