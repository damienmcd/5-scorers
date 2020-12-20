const colors = require('tailwindcss/colors')

module.exports = {
  purge: [],
  darkMode: false, // or 'media' or 'class'
  theme: {
    screens: {
      sm: '480px',
      md: '768px',
      lg: '976px',
      xl: '1440px'
    },
    minHeight: {
      0: '0',
      '1/4': '25%',
      '1/2': '50%',
      '3/4': '75%',
      full: '100%',
      screen: '100vh',
      'fill-d': 'calc(100vh - 80px)',
      'fill-m': 'calc(100vh - 80px)'
    },
    container: {
      center: true,
    },
    colors: {
      white: colors.white,
      grey: colors.trueGray,
      blue: colors.lightBlue,
      green: colors.teal,
      red: colors.red,
      orange: colors.orange,
      yellow: colors.amber
    },
    fontFamily: {
      sans: ['Graphik', 'sans-serif'],
      serif: ['Merriweather', 'serif']
    },
    extend: {
      spacing: {
        128: '32rem',
        144: '36rem'
      },
      borderRadius: {
        '4xl': '2rem'
      }
    }
  },
  variants: {
    extend: {}
  },
  plugins: []
}
