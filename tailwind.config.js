/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./*.{html,js,php}"],
  theme: {
    extend: {
      spacing: {
        '95/100': '95%',
      }
    },
  },
  variants: {
    opacity: ({ after }) => after(['disabled'])
  },
  plugins: [],
}

