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
                
                // Elegant Accent
                accent: {
                    DEFAULT: '#4f46e5', // Indigo 600
                    hover: '#4338ca',   // Indigo 700
                    light: '#e0e7ff',   // Indigo 100
                },
            },
        },
    },

    plugins: [forms],
};
