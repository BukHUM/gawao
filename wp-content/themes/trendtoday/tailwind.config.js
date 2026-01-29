/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './**/*.php',
    './inc/**/*.php',
    './template-parts/**/*.php',
    './widgets/**/*.php',
    './assets/js/**/*.js',
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Prompt', 'sans-serif'],
      },
      colors: {
        primary: '#1a1a1a',
        accent: '#FF4500',
        'news-tech': '#3B82F6',
        'news-ent': '#EC4899',
        'news-fin': '#10B981',
        'news-sport': '#F59E0B',
      },
      typography: {
        DEFAULT: {
          css: {
            maxWidth: 'none',
            color: '#374151',
            lineHeight: '1.75',
            fontSize: '1.125rem',
            'h1, h2, h3, h4, h5, h6': {
              color: '#111827',
              fontWeight: '700',
              lineHeight: '1.2',
            },
            p: {
              marginTop: '1.25em',
              marginBottom: '1.25em',
            },
            a: {
              color: '#FF4500',
              textDecoration: 'underline',
              '&:hover': {
                color: '#dc3a00',
              },
            },
            strong: {
              fontWeight: '600',
              color: '#111827',
            },
            'ul, ol': {
              marginTop: '1.25em',
              marginBottom: '1.25em',
              paddingLeft: '1.625em',
            },
            li: {
              marginTop: '0.5em',
              marginBottom: '0.5em',
            },
            blockquote: {
              borderLeftColor: '#FF4500',
              borderLeftWidth: '4px',
              paddingLeft: '1em',
              fontStyle: 'italic',
              color: '#6B7280',
            },
            img: {
              borderRadius: '0.5rem',
              marginTop: '2em',
              marginBottom: '2em',
            },
          },
        },
      },
    },
  },
  plugins: [
    function ({ addComponents, theme }) {
      addComponents({
        '.prose': {
          '& p': {
            marginTop: theme('spacing.5'),
            marginBottom: theme('spacing.5'),
            lineHeight: theme('lineHeight.relaxed'),
          },
          '& h1, & h2, & h3, & h4, & h5, & h6': {
            marginTop: theme('spacing.6'),
            marginBottom: theme('spacing.4'),
            fontWeight: theme('fontWeight.bold'),
            lineHeight: theme('lineHeight.tight'),
            color: theme('colors.gray.900'),
          },
          '& h1': { fontSize: theme('fontSize.3xl[0]') },
          '& h2': { fontSize: theme('fontSize.2xl[0]') },
          '& h3': { fontSize: theme('fontSize.xl[0]') },
          '& a': {
            color: theme('colors.accent'),
            textDecoration: 'underline',
            '&:hover': {
              color: theme('colors.orange.700'),
            },
          },
          '& strong': {
            fontWeight: theme('fontWeight.semibold'),
            color: theme('colors.gray.900'),
          },
          '& ul, & ol': {
            marginTop: theme('spacing.5'),
            marginBottom: theme('spacing.5'),
            paddingLeft: theme('spacing.6'),
          },
          '& li': {
            marginTop: theme('spacing.2'),
            marginBottom: theme('spacing.2'),
          },
          '& blockquote': {
            borderLeftWidth: '4px',
            borderLeftColor: theme('colors.accent'),
            paddingLeft: theme('spacing.4'),
            fontStyle: 'italic',
            color: theme('colors.gray.600'),
            marginTop: theme('spacing.6'),
            marginBottom: theme('spacing.6'),
          },
          '& img': {
            borderRadius: theme('borderRadius.lg'),
            marginTop: theme('spacing.8'),
            marginBottom: theme('spacing.8'),
            maxWidth: '100%',
            height: 'auto',
          },
          '& code': {
            backgroundColor: theme('colors.gray.100'),
            padding: theme('spacing.1'),
            borderRadius: theme('borderRadius.sm'),
            fontSize: theme('fontSize.sm[0]'),
          },
          '& pre': {
            backgroundColor: theme('colors.gray.900'),
            color: theme('colors.gray.100'),
            padding: theme('spacing.4'),
            borderRadius: theme('borderRadius.lg'),
            overflow: 'auto',
            marginTop: theme('spacing.6'),
            marginBottom: theme('spacing.6'),
          },
        },
      });
    },
  ],
};
