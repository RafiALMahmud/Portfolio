/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './src/pages/**/*.{js,ts,jsx,tsx,mdx}',
    './src/components/**/*.{js,ts,jsx,tsx,mdx}',
    './src/app/**/*.{js,ts,jsx,tsx,mdx}',
  ],
  theme: {
    extend: {
      colors: {
        background: '#111111',
        'text-primary': '#EAEAEA',
        'text-secondary': '#888888',
        accent: '#00FF99',
        surface: '#1A1A1A',
      },
      fontFamily: {
        sans: ['Geist Sans', 'Inter', 'system-ui', 'sans-serif'],
        mono: ['JetBrains Mono', 'Fira Code', 'monospace'],
      },
      fontSize: {
        'hero': ['4.5rem', { lineHeight: '1.1', letterSpacing: '-0.05em' }],
        'section': ['2.25rem', { lineHeight: '1.2', letterSpacing: '-0.025em' }],
        'project': ['1.5rem', { lineHeight: '1.3' }],
        'body': ['1rem', { lineHeight: '1.7' }],
        'secondary': ['0.875rem', { lineHeight: '1.6' }],
        'link': ['1rem', { lineHeight: '1.5', letterSpacing: '0.025em' }],
        'tech': ['0.875rem', { lineHeight: '1.4' }],
      },
      fontWeight: {
        'hero': '700',
        'section': '600',
        'project': '600',
        'body': '400',
        'link': '500',
        'tech': '400',
      },
      animation: {
        'fade-in-up': 'fadeInUp 0.5s ease-in-out',
        'typewriter': 'typewriter 3s steps(40) infinite',
      },
      keyframes: {
        fadeInUp: {
          '0%': {
            opacity: '0',
            transform: 'translateY(30px)',
          },
          '100%': {
            opacity: '1',
            transform: 'translateY(0)',
          },
        },
        typewriter: {
          '0%': { width: '0' },
          '50%': { width: '100%' },
          '100%': { width: '0' },
        },
      },
      transitionDuration: {
        '300': '300ms',
      },
      transitionTimingFunction: {
        'ease-in-out': 'ease-in-out',
      },
    },
  },
  plugins: [],
}
