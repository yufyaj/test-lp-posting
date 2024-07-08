/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./*.{html,js,php}"],
  theme: {
    extend: {
      spacing: {
        '95/100': '95%',
        '125': '500px',
      },
      colors: {
        'main': 'rgb(241, 245, 249)',
        'text-main': 'rgb(46, 86, 165)',
        'text-sub': 'rgb(203, 169, 63)',
        'element-bg-main': 'rgb(29, 53, 101)',
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

