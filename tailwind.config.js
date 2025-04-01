/** @type {import('tailwindcss').Config} */
module.exports = {
  mode: 'aot',
  purge: [
    "./resources/views/drinks/*****.blade.php",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}