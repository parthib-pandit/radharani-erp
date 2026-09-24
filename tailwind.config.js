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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Aurum ERP design system — see docs/DESIGN_SYSTEM.md
                ink: {
                    DEFAULT: '#151515', // sidebar / darkest surface
                    charcoal: '#1F1F1F',
                },
                gold: {
                    DEFAULT: '#B8862D',
                    dark: '#946B20',
                    light: '#D4AF5A',
                    soft: '#E8D3A2',
                },
                surface: {
                    bg: '#FAF8F3',
                    DEFAULT: '#FFFFFF',
                    muted: '#F3F1EC',
                },
                ink_text: {
                    primary: '#1A1A1A',
                    secondary: '#6F6B63',
                    muted: '#9A958B',
                },
                line: {
                    DEFAULT: '#E7E3DA',
                    light: '#EFEBE3',
                },
                success: { DEFAULT: '#26845B', bg: '#EAF7F0' },
                warning: { DEFAULT: '#B7791F', bg: '#FFF5DC' },
                danger: { DEFAULT: '#C94A4A', bg: '#FCECEC' },
                info: { DEFAULT: '#3867A6', bg: '#EDF4FC' },
            },
            borderRadius: {
                card: '14px',
                control: '10px',
            },
            boxShadow: {
                card: '0 2px 10px rgba(30, 25, 15, 0.04)',
            },
        },
    },

    plugins: [forms],
};
