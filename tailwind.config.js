import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
// tailwind.config.js
export default {
  theme: {
    extend: {
      colors: {
        cafe: {
          light: '#fef9e8',
          medium: '#fff5ea',
          glass: '#fef1e0',
          accent: '#d97706', // A nice coffee-brown/amber for buttons
        },
      },
      backgroundImage: {
        'cafe-gradient': "linear-gradient(120deg, #fef9e8 0%, #fff5ea 100%)",
        'cafe-glass': "linear-gradient(120deg, #fef9e8 0%, #fef1e0 100%)",
      }
    },
  },


    plugins: [forms],
};
