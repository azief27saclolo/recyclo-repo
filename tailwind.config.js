/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./public/**/*.{html,js,php}"],
  theme: {
    colors: {
      'white': '#f9f9f9',
      'black': '#0D0D0D',
      'green-200': '#59755E',
      'green-400': '#94C77F',
      'green-600': '#4C6B52',
      'green-800': '#347928'
    },
    extend: {
      fontFamily: {
        'roboto': ['Roboto', 'sans-serif']
      }
    },
  },
  plugins: [],
}

