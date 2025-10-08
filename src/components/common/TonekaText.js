import React from 'react';
import { Text, StyleSheet } from 'react-native';
import { TonekaTheme } from '../../theme/TonekaTheme';

const TonekaText = ({ 
  children, 
  variant = 'body', 
  color = 'secondary',
  style,
  ...props 
}) => {
  const textStyle = [
    styles.text,
    styles[variant],
    styles[`${color}Color`],
    style,
  ];

  return (
    <Text style={textStyle} {...props}>
      {children}
    </Text>
  );
};

const styles = StyleSheet.create({
  text: {
    fontFamily: TonekaTheme.typography.fontFamily.regular,
  },
  
  // Typography variants
  h1: {
    fontSize: TonekaTheme.typography.fontSize['5xl'],
    fontWeight: TonekaTheme.typography.fontWeight.regular,
    lineHeight: TonekaTheme.typography.lineHeight.tight * TonekaTheme.typography.fontSize['5xl'],
    textTransform: TonekaTheme.typography.textTransform.uppercase,
  },
  
  h2: {
    fontSize: TonekaTheme.typography.fontSize['2xl'],
    fontWeight: TonekaTheme.typography.fontWeight.regular,
    lineHeight: TonekaTheme.typography.lineHeight.normal * TonekaTheme.typography.fontSize['2xl'],
    textTransform: TonekaTheme.typography.textTransform.uppercase,
  },
  
  h3: {
    fontSize: TonekaTheme.typography.fontSize.xl,
    fontWeight: TonekaTheme.typography.fontWeight.medium,
    lineHeight: TonekaTheme.typography.lineHeight.normal * TonekaTheme.typography.fontSize.xl,
  },
  
  body: {
    fontSize: TonekaTheme.typography.fontSize.base,
    fontWeight: TonekaTheme.typography.fontWeight.regular,
    lineHeight: TonekaTheme.typography.lineHeight.normal * TonekaTheme.typography.fontSize.base,
  },
  
  caption: {
    fontSize: TonekaTheme.typography.fontSize.sm,
    fontWeight: TonekaTheme.typography.fontWeight.regular,
    lineHeight: TonekaTheme.typography.lineHeight.normal * TonekaTheme.typography.fontSize.sm,
  },
  
  small: {
    fontSize: TonekaTheme.typography.fontSize.xs,
    fontWeight: TonekaTheme.typography.fontWeight.regular,
    lineHeight: TonekaTheme.typography.lineHeight.normal * TonekaTheme.typography.fontSize.xs,
  },
  
  // Color variants
  primaryColor: {
    color: TonekaTheme.colors.primary,
  },
  
  secondaryColor: {
    color: TonekaTheme.colors.secondary,
  },
  
  accentColor: {
    color: TonekaTheme.colors.accent,
  },
  
  successColor: {
    color: TonekaTheme.colors.success,
  },
  
  errorColor: {
    color: TonekaTheme.colors.error,
  },
  
  warningColor: {
    color: TonekaTheme.colors.warning,
  },
  
  infoColor: {
    color: TonekaTheme.colors.info,
  },
});

export default TonekaText;
