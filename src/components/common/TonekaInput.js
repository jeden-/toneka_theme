import React, { useState } from 'react';
import { View, TextInput, Text, StyleSheet } from 'react-native';
import { TonekaTheme } from '../../theme/TonekaTheme';
import TonekaText from './TonekaText';

const TonekaInput = ({ 
  label,
  placeholder,
  value,
  onChangeText,
  secureTextEntry = false,
  error,
  style,
  inputStyle,
  labelStyle,
  ...props 
}) => {
  const [isFocused, setIsFocused] = useState(false);

  const containerStyle = [
    styles.container,
    style,
  ];

  const inputContainerStyle = [
    styles.inputContainer,
    isFocused && styles.inputContainerFocused,
    error && styles.inputContainerError,
  ];

  const inputTextStyle = [
    styles.input,
    inputStyle,
  ];

  const labelTextStyle = [
    styles.label,
    labelStyle,
  ];

  return (
    <View style={containerStyle}>
      {label && (
        <TonekaText 
          variant="caption" 
          color="secondary" 
          style={labelTextStyle}
        >
          {label}
        </TonekaText>
      )}
      
      <View style={inputContainerStyle}>
        <TextInput
          style={inputTextStyle}
          placeholder={placeholder}
          placeholderTextColor={TonekaTheme.colors.accent}
          value={value}
          onChangeText={onChangeText}
          secureTextEntry={secureTextEntry}
          onFocus={() => setIsFocused(true)}
          onBlur={() => setIsFocused(false)}
          {...props}
        />
      </View>
      
      {error && (
        <TonekaText 
          variant="small" 
          color="error" 
          style={styles.errorText}
        >
          {error}
        </TonekaText>
      )}
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    marginBottom: TonekaTheme.spacing.lg,
  },
  
  label: {
    marginBottom: TonekaTheme.spacing.sm,
    paddingHorizontal: TonekaTheme.spacing.lg,
  },
  
  inputContainer: {
    borderBottomWidth: 1,
    borderBottomColor: TonekaTheme.colors.accent,
    backgroundColor: TonekaTheme.colors.primary,
  },
  
  inputContainerFocused: {
    borderBottomColor: TonekaTheme.colors.secondary,
  },
  
  inputContainerError: {
    borderBottomColor: TonekaTheme.colors.error,
  },
  
  input: {
    paddingHorizontal: TonekaTheme.spacing.lg,
    paddingVertical: TonekaTheme.spacing.md,
    fontSize: TonekaTheme.typography.fontSize.base,
    fontFamily: TonekaTheme.typography.fontFamily.regular,
    color: TonekaTheme.colors.secondary,
    backgroundColor: TonekaTheme.colors.primary,
  },
  
  errorText: {
    marginTop: TonekaTheme.spacing.xs,
    paddingHorizontal: TonekaTheme.spacing.lg,
  },
});

export default TonekaInput;
