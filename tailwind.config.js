/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#0A4DF3',
          deep: '#0639B8',
          soft: '#F0F5FF',
          softer: '#DBE6FE',
          ink: '#0A2875',
        },
        accent: { DEFAULT: '#0284C7', ink: '#075985', soft: '#F0F9FF' },
        canvas: { DEFAULT: '#FFFFFF', soft: '#F8FAFC' },
        surface: '#FFFFFF',
        hairline: { DEFAULT: '#E2E8F0', strong: '#CBD5E1' },
        ink: '#0F172A',
        body: '#334155',
        mute: '#64748B',
        'on-primary': '#FFFFFF',
        success: { DEFAULT: '#16A34A', soft: '#F0FDF4', ink: '#166534' },
        warning: { DEFAULT: '#D97706', soft: '#FFFBEB', ink: '#92400E' },
        error:   { DEFAULT: '#DC2626', soft: '#FEF2F2', ink: '#991B1B', deep: '#B91C1C' },
        info:    { DEFAULT: '#0284C7', soft: '#F0F9FF', ink: '#075985' },
        neutral: { DEFAULT: '#64748B', soft: '#F1F5F9', ink: '#334155' },
      },
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', '"Segoe UI"', 'sans-serif'],
        display: ['"Plus Jakarta Sans"', 'Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
      fontSize: {
        'display-xl': ['56px', { lineHeight: '60px', letterSpacing: '-0.03em', fontWeight: '800' }],
        'display-lg': ['40px', { lineHeight: '44px', letterSpacing: '-0.025em', fontWeight: '700' }],
        'heading-lg': ['30px', { lineHeight: '36px', letterSpacing: '-0.02em', fontWeight: '700' }],
        'heading-md': ['20px', { lineHeight: '28px', letterSpacing: '-0.01em', fontWeight: '600' }],
        'body-md':    ['16px', { lineHeight: '24px' }],
        'body-sm':    ['14px', { lineHeight: '20px' }],
        'button-md':  ['14px', { lineHeight: '20px', fontWeight: '500' }],
        caption:      ['12px', { lineHeight: '16px', letterSpacing: '0.01em', fontWeight: '500' }],
      },
      spacing: {
        xxs: '2px', xs: '4px', sm: '8px', md: '12px', lg: '16px',
        xl: '24px', '2xl': '32px', '3xl': '48px', '4xl': '64px', section: '96px',
      },
      borderRadius: {
        none: '0', sm: '4px', md: '8px', lg: '12px', pill: '9999px', full: '50%',
      },
      boxShadow: {
        'xs': '0 1px 2px 0 rgba(15, 23, 42, 0.04)',
        'elev-0': 'none',
        'elev-1': '0 0 0 1px rgba(15,23,42,0.06), 0 1px 2px rgba(15,23,42,0.04)',
        'elev-2': '0 0 0 1px rgba(15,23,42,0.06), 0 1px 2px rgba(15,23,42,0.04), 0 4px 8px -2px rgba(15,23,42,0.06)',
        'elev-3': '0 0 0 1px rgba(15,23,42,0.06), 0 2px 4px rgba(15,23,42,0.04), 0 8px 16px -4px rgba(15,23,42,0.08)',
        'elev-4': '0 0 0 1px rgba(15,23,42,0.06), 0 2px 4px rgba(15,23,42,0.04), 0 12px 24px -6px rgba(15,23,42,0.10), 0 24px 48px -12px rgba(15,23,42,0.08)',
        'elev-5': '0 0 0 1px rgba(15,23,42,0.06), 0 4px 8px rgba(15,23,42,0.04), 0 16px 32px -8px rgba(15,23,42,0.12), 0 32px 64px -16px rgba(15,23,42,0.16)',
        'focus': '0 0 0 3px rgba(37,99,235,0.20)',
        'focus-error': '0 0 0 3px rgba(220,38,38,0.20)',
      },
      backgroundImage: {
        'brand': 'linear-gradient(135deg, #0A4DF3 0%, #0284C7 100%)',
        'brand-deep': 'linear-gradient(135deg, #0A2875 0%, #0A4DF3 55%, #0284C7 100%)',
        'glow': 'radial-gradient(60% 60% at 50% 0%, rgba(10, 77, 243, 0.16) 0%, rgba(255, 255, 255, 0) 100%)',
        'progress': 'linear-gradient(90deg, #0A4DF3 0%, #0284C7 100%)',
      },
      maxWidth: { container: '1280px' },
    },
  },
  plugins: [require('@tailwindcss/forms')],
};
