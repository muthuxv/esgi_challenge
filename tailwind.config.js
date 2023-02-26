/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./assets/**/*.js",
  "./templates/**/*.html.twig",
  "./node_modules/flowbite/**/*.js",],
  theme: {
    extend: {},
    fontFamily: {
      sans: ['Montserrat', 'sans-serif'],
      serif: ['Montserrat', 'serif'],
    },
  },
  plugins: [
    require('daisyui'), 
    require('flowbite/plugin')
  ],
  daisyui: {
    styled: true,
    themes: false,
    base: true,
    utils: true,
    logs: true,
    rtl: false,
    prefix: "",
    darkTheme: "dark",
  }
}
