// Toneka Design System - bazowany na Figma design
export const TonekaTheme = {
  colors: {
    // Główne kolory z Figma
    primary: '#000000',        // Czarne tło
    secondary: '#FFFFFF',      // Biały tekst
    accent: '#404040',         // Szare elementy
    background: '#000000',     // Główne tło
    surface: '#1A1A1A',       // Karty/sekcje
    border: '#FFFFFF',         // Białe obramowania
    overlay: 'rgba(0, 0, 0, 0.7)', // Overlay na obrazach
    
    // Status kolory
    success: '#4CAF50',
    error: '#F44336',
    warning: '#FF9800',
    info: '#2196F3',
    
    // Transparentne warianty
    white10: 'rgba(255, 255, 255, 0.1)',
    white20: 'rgba(255, 255, 255, 0.2)',
    white50: 'rgba(255, 255, 255, 0.5)',
    white70: 'rgba(255, 255, 255, 0.7)',
    black10: 'rgba(0, 0, 0, 0.1)',
    black20: 'rgba(0, 0, 0, 0.2)',
    black50: 'rgba(0, 0, 0, 0.5)',
    black70: 'rgba(0, 0, 0, 0.7)',
  },

  typography: {
    // Font family - Figtree (jak w web)
    fontFamily: {
      regular: 'System', // Figtree będzie dodany później
      medium: 'System',
      bold: 'System',
    },
    
    // Rozmiary fontów z Figma
    fontSize: {
      xs: 10,
      sm: 12,
      base: 14,
      md: 16,
      lg: 18,
      xl: 20,
      '2xl': 24,
      '3xl': 30,
      '4xl': 36,
      '5xl': 42, // Hero title z Figma
    },
    
    // Wysokości linii
    lineHeight: {
      tight: 1.1,    // Hero title
      normal: 1.4,   // Body text
      relaxed: 1.6,  // Long text
    },
    
    // Wagi fontów
    fontWeight: {
      light: '300',
      regular: '400',
      medium: '500',
      semibold: '600',
      bold: '700',
    },
    
    // Style tekstu
    textTransform: {
      uppercase: 'uppercase',
      lowercase: 'lowercase',
      capitalize: 'capitalize',
      none: 'none',
    },
    
    // Letter spacing
    letterSpacing: {
      tight: -0.5,
      normal: 0,
      wide: 0.5,
      wider: 1.0,
      widest: 1.5,
    },
  },

  spacing: {
    // Spacing system - 4px grid
    xs: 4,
    sm: 8,
    md: 12,
    lg: 16,
    xl: 20,
    '2xl': 24,
    '3xl': 32,
    '4xl': 40,
    '5xl': 48,
    '6xl': 64,
  },

  borderRadius: {
    // Border radius z Figma
    none: 0,
    sm: 4,
    md: 8,
    lg: 12,
    xl: 16,
    '2xl': 20,
    '3xl': 28, // Player border radius z Figma
    full: 9999,
  },

  shadows: {
    // Shadow system
    none: {
      shadowColor: 'transparent',
      shadowOffset: { width: 0, height: 0 },
      shadowOpacity: 0,
      shadowRadius: 0,
      elevation: 0,
    },
    sm: {
      shadowColor: '#000000',
      shadowOffset: { width: 0, height: 1 },
      shadowOpacity: 0.1,
      shadowRadius: 2,
      elevation: 2,
    },
    md: {
      shadowColor: '#000000',
      shadowOffset: { width: 0, height: 2 },
      shadowOpacity: 0.15,
      shadowRadius: 4,
      elevation: 4,
    },
    lg: {
      shadowColor: '#000000',
      shadowOffset: { width: 0, height: 4 },
      shadowOpacity: 0.2,
      shadowRadius: 8,
      elevation: 8,
    },
  },

  // Komponenty style
  components: {
    // Button styles z Figma
    button: {
      primary: {
        backgroundColor: '#FFFFFF',
        color: '#000000',
        borderRadius: 8,
        paddingHorizontal: 16,
        paddingVertical: 12,
        fontSize: 11.2,
        fontWeight: '400',
        textTransform: 'uppercase',
        letterSpacing: 1.008,
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'center',
        gap: 12,
      },
      secondary: {
        backgroundColor: 'transparent',
        color: '#FFFFFF',
        borderWidth: 1,
        borderColor: '#FFFFFF',
        borderRadius: 8,
        paddingHorizontal: 16,
        paddingVertical: 12,
        fontSize: 11.2,
        fontWeight: '400',
        textTransform: 'uppercase',
        letterSpacing: 1.008,
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'center',
        gap: 12,
      },
    },
    
    // Input styles z Figma
    input: {
      backgroundColor: '#000000',
      color: '#FFFFFF',
      borderBottomWidth: 1,
      borderBottomColor: '#404040',
      paddingHorizontal: 15,
      paddingVertical: 12,
      fontSize: 16,
      fontFamily: 'System',
    },
    
    // Card styles
    card: {
      backgroundColor: '#1A1A1A',
      borderRadius: 8,
      padding: 16,
      marginBottom: 16,
    },
    
    // Player styles z Figma
    player: {
      backgroundColor: '#000000',
      borderRadius: 28,
      padding: 20,
      minHeight: 400,
    },
  },

  // Breakpoints dla responsive design
  breakpoints: {
    sm: 576,
    md: 768,
    lg: 992,
    xl: 1200,
  },

  // Animacje
  animations: {
    duration: {
      fast: 150,
      normal: 300,
      slow: 500,
    },
    easing: {
      ease: 'ease',
      easeIn: 'ease-in',
      easeOut: 'ease-out',
      easeInOut: 'ease-in-out',
    },
  },
};

// Helper functions
export const getThemeColor = (colorPath) => {
  const keys = colorPath.split('.');
  let value = TonekaTheme.colors;
  
  for (const key of keys) {
    value = value[key];
    if (value === undefined) {
      console.warn(`Color not found: ${colorPath}`);
      return '#000000';
    }
  }
  
  return value;
};

export const getThemeSpacing = (size) => {
  return TonekaTheme.spacing[size] || 0;
};

export const getThemeFontSize = (size) => {
  return TonekaTheme.typography.fontSize[size] || 14;
};

export const getThemeBorderRadius = (size) => {
  return TonekaTheme.borderRadius[size] || 0;
};

export default TonekaTheme;
