import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],

// tailwind.config.js
theme: {
    extend: {
        colors: {
            nfl: {
                primary: '#013369',    // NFL Blue
                secondary: '#D50A0A',  // NFL Red
                accent: '#125740',     // NFL Green
                background: '#ffffff', // White background
                text: '#000000',      // Black text
                // Team colors
                vikings: '#4B2682',
                bears: '#FB4F14',
                steelers: '#A5ACAF'
            }
        },
        fontFamily: {
            sans: ['Roboto', ...defaultTheme.fontFamily.sans],
            serif: ['Rod', ...defaultTheme.fontFamily.serif],
        }
    }
},

    plugins: [forms],
};
