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
                sans: ['Inter', 'ui-sans-serif', 'system-ui'],
            },
            colors: {
                // Neutral Canvas
                primary: '#0f172a', // Slate 900
                secondary: '#64748b', // Slate 500
                background: '#f8fafc', // Slate 50
                surface: '#ffffff',
                
                // Bolo Emerald Accent
                accent: {
                    DEFAULT: '#059669', // Emerald 600
                    hover: '#047857',   // Emerald 700
                    light: '#ecfdf5',   // Emerald 50
                },
            },
        },
    },

    plugins: [forms],
};
