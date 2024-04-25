/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./*.{html,js,php}"],
  theme: {
    extend: {
      spacing: {
        '95/100': '95%',
      },
      colors: {
        'main': '#feee7d',
        'sub': '#a5dff9',
        'moji': '#34314c',
      }
    },
    fontFamily: {
      body: [
        'メイリオ',
        'Meiryo',
        'Meiryo UI',
        'ヒラギノ角ゴシック',
        'Hiragino Sans',
        'Hiragino Kaku Gothic ProN',
        'ヒラギノ角ゴ ProN W3',
        'ヒラギノ明朝 ProN',
        'Hiragino Mincho ProN',
        'sans-serif'
      ]
    }
  },
  variants: {
    opacity: ({ after }) => after(['disabled'])
  },
  plugins: [],
}

