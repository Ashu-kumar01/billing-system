import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
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
                primary: {
                    DEFAULT: '#4F46E5',
                    50: '#EEF2FF',
                    100: '#E0E7FF',
                    500: '#4F46E5',
                    600: '#4338CA',
                    700: '#3730A3',
                },
                secondary: {
                    DEFAULT: '#3B82F6',
                    50: '#EFF6FF',
                    100: '#DBEAFE',
                    500: '#3B82F6',
                    600: '#2563EB',
                },
                accent: {
                    DEFAULT: '#06B6D4',
                    50: '#ECFEFF',
                    100: '#CFFAFE',
                    500: '#06B6D4',
                    600: '#0891B2',
                },
                purple: {
                    DEFAULT: '#8B5CF6',
                    50: '#F5F3FF',
                    100: '#EDE9FE',
                    500: '#8B5CF6',
                    600: '#7C3AED',
                },
                pink: {
                    DEFAULT: '#EC4899',
                    50: '#FDF2F8',
                    100: '#FCE7F3',
                    500: '#EC4899',
                    600: '#DB2777',
                },
                orange: {
                    DEFAULT: '#FB923C',
                    50: '#FFF7ED',
                    100: '#FFEDD5',
                    500: '#FB923C',
                    600: '#EA580C',
                },
                yellow: {
                    DEFAULT: '#FACC15',
                    50: '#FEFCE8',
                    100: '#FEF9C3',
                    500: '#FACC15',
                    600: '#CA8A04',
                },
                mint: {
                    DEFAULT: '#10B981',
                    50: '#ECFDF5',
                    100: '#D1FAE5',
                    500: '#10B981',
                    600: '#059669',
                },
                sky: {
                    DEFAULT: '#0EA5E9',
                    50: '#F0F9FF',
                    100: '#E0F2FE',
                    500: '#0EA5E9',
                    600: '#0284C7',
                },
                success: '#22C55E',
                danger: '#EF4444',
                warning: '#F59E0B',
                surface: {
                    DEFAULT: 'rgb(var(--color-surface) / <alpha-value>)',
                    subtle: 'rgb(var(--color-surface-subtle) / <alpha-value>)',
                    muted: 'rgb(var(--color-surface-muted) / <alpha-value>)',
                    soft: 'rgb(var(--color-surface-soft) / <alpha-value>)',
                },
                border: 'rgb(var(--color-border) / <alpha-value>)',
                ink: 'rgb(var(--color-ink) / <alpha-value>)',
                muted: 'rgb(var(--color-muted) / <alpha-value>)',
            },
            boxShadow: {
                card: '0 1px 2px 0 rgba(30, 41, 59, 0.04), 0 12px 32px -8px rgba(79, 70, 229, 0.08)',
                soft: '0 2px 8px -2px rgba(30, 41, 59, 0.08)',
                glow: '0 8px 28px -6px rgba(79, 70, 229, 0.4)',
                'glow-pink': '0 8px 28px -6px rgba(236, 72, 153, 0.4)',
            },
            borderRadius: {
                xl: '0.875rem',
                '2xl': '1.25rem',
                '3xl': '1.75rem',
            },
            backgroundImage: {
                mesh: 'linear-gradient(135deg, #F8FAFF 0%, #EEF4FF 35%, #F6F8FF 65%, #FFF9F5 100%)',
            },
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0) translateX(0)' },
                    '50%': { transform: 'translateY(-18px) translateX(10px)' },
                },
                'float-slow': {
                    '0%, 100%': { transform: 'translateY(0) scale(1)' },
                    '50%': { transform: 'translateY(14px) scale(1.05)' },
                },
            },
            animation: {
                float: 'float 8s ease-in-out infinite',
                'float-slow': 'float-slow 12s ease-in-out infinite',
            },
        },
    },

    plugins: [forms],
};
