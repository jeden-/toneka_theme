import React from 'react';
import { TouchableOpacity, Text, View, StyleSheet } from 'react-native';
import { TonekaTheme } from '../../theme/TonekaTheme';

const TonekaButton = ({ 
  title, 
  onPress, 
  variant = 'primary', 
  icon, 
  disabled = false,
  style,
  textStyle,
  ...props 
}) => {
  const buttonStyle = [
    styles.button,
    styles[variant],
    disabled && styles.disabled,
    style,
  ];

  const buttonTextStyle = [
    styles.buttonText,
    styles[`${variant}Text`],
    disabled && styles.disabledText,
    textStyle,
  ];

  return (
    <TouchableOpacity
      style={buttonStyle}
      onPress={onPress}
      disabled={disabled}
      activeOpacity={0.8}
      {...props}
    >
      <Text style={buttonTextStyle}>{title}</Text>
      {icon && <View style={styles.icon}>{icon}</View>}
    </TouchableOpacity>
  );
};

const styles = StyleSheet.create({
  button: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    paddingHorizontal: TonekaTheme.spacing.lg,
    paddingVertical: TonekaTheme.spacing.md,
    borderRadius: TonekaTheme.borderRadius.md,
    gap: TonekaTheme.spacing.md,
    minHeight: 44,
  },
  
  // Primary button - biały z czarnym tekstem (jak w Figma)
  primary: {
    backgroundColor: TonekaTheme.colors.secondary,
  },
  
  // Secondary button - transparentny z białym obramowaniem
  secondary: {
    backgroundColor: 'transparent',
    borderWidth: 1,
    borderColor: TonekaTheme.colors.secondary,
  },
  
  // Ghost button - tylko tekst
  ghost: {
    backgroundColor: 'transparent',
  },
  
  // Disabled state
  disabled: {
    opacity: 0.5,
  },
  
  // Text styles
  buttonText: {
    fontSize: TonekaTheme.typography.fontSize.sm,
    fontFamily: TonekaTheme.typography.fontFamily.regular,
    fontWeight: TonekaTheme.typography.fontWeight.regular,
    textTransform: TonekaTheme.typography.textTransform.uppercase,
    letterSpacing: TonekaTheme.typography.letterSpacing.wider,
    textAlign: 'center',
  },
  
  primaryText: {
    color: TonekaTheme.colors.primary,
  },
  
  secondaryText: {
    color: TonekaTheme.colors.secondary,
  },
  
  ghostText: {
    color: TonekaTheme.colors.secondary,
  },
  
  disabledText: {
    opacity: 0.7,
  },
  
  // Icon container
  icon: {
    marginLeft: TonekaTheme.spacing.xs,
  },
});

export default TonekaButton;
