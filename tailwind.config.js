/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./node_modules/flowbite/**/*.js"
  ],
  theme: {
    extend: {
      height: {
        '80vh': '80vh',
      }
    },
  },
  plugins: [
    require('flowbite/plugin')
  ],
}

