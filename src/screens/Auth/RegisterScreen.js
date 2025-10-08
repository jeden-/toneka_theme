import React, { useState } from 'react';
import {
  View,
  StyleSheet,
  TouchableOpacity,
  KeyboardAvoidingView,
  Platform,
  ScrollView,
} from 'react-native';
import { useNavigation } from '@react-navigation/native';

// Components
import TonekaText from '../../components/common/TonekaText';
import TonekaInput from '../../components/common/TonekaInput';
import TonekaButton from '../../components/common/TonekaButton';

// Services
import AuthService from '../../services/AuthService';

// Theme
import { TonekaTheme } from '../../theme/TonekaTheme';

const RegisterScreen = () => {
  const navigation = useNavigation();
  const [formData, setFormData] = useState({
    firstName: '',
    lastName: '',
    email: '',
    password: '',
    confirmPassword: '',
  });
  const [errors, setErrors] = useState({});
  const [loading, setLoading] = useState(false);

  const handleInputChange = (field, value) => {
    setFormData({ ...formData, [field]: value });
    // Clear error when user starts typing
    if (errors[field]) {
      setErrors({ ...errors, [field]: '' });
    }
  };

  const validateForm = () => {
    const newErrors = {};

    if (!formData.firstName) {
      newErrors.firstName = 'Imię jest wymagane';
    }

    if (!formData.lastName) {
      newErrors.lastName = 'Nazwisko jest wymagane';
    }

    if (!formData.email) {
      newErrors.email = 'Email jest wymagany';
    } else if (!/\S+@\S+\.\S+/.test(formData.email)) {
      newErrors.email = 'Email jest nieprawidłowy';
    }

    if (!formData.password) {
      newErrors.password = 'Hasło jest wymagane';
    } else if (formData.password.length < 6) {
      newErrors.password = 'Hasło musi mieć co najmniej 6 znaków';
    }

    if (!formData.confirmPassword) {
      newErrors.confirmPassword = 'Potwierdzenie hasła jest wymagane';
    } else if (formData.password !== formData.confirmPassword) {
      newErrors.confirmPassword = 'Hasła nie są identyczne';
    }

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleRegister = async () => {
    if (!validateForm()) return;

    try {
      setLoading(true);
      
      const result = await AuthService.register({
        firstName: formData.firstName,
        lastName: formData.lastName,
        email: formData.email,
        password: formData.password,
      });
      
      if (result.success) {
        // Navigation will be handled by the main app
        navigation.navigate('MainTabs');
      }
      
    } catch (error) {
      console.error('Registration error:', error);
      setErrors({ general: error.message || 'Błąd rejestracji' });
    } finally {
      setLoading(false);
    }
  };

  const handleLogin = () => {
    navigation.navigate('Login');
  };

  return (
    <KeyboardAvoidingView 
      style={styles.container}
      behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
    >
      <ScrollView contentContainerStyle={styles.scrollContainer}>
        {/* Header */}
        <View style={styles.header}>
          <TonekaText variant="h1" color="secondary" style={styles.title}>
            TONEKA
          </TonekaText>
          <TonekaText variant="body" color="accent" style={styles.subtitle}>
            Utwórz nowe konto
          </TonekaText>
        </View>

        {/* Form */}
        <View style={styles.form}>
          {errors.general && (
            <View style={styles.errorContainer}>
              <TonekaText variant="caption" color="error">
                {errors.general}
              </TonekaText>
            </View>
          )}

          <TonekaInput
            label="Imię"
            value={formData.firstName}
            onChangeText={(text) => handleInputChange('firstName', text)}
            autoCapitalize="words"
            error={errors.firstName}
          />

          <TonekaInput
            label="Nazwisko"
            value={formData.lastName}
            onChangeText={(text) => handleInputChange('lastName', text)}
            autoCapitalize="words"
            error={errors.lastName}
          />

          <TonekaInput
            label="Email"
            value={formData.email}
            onChangeText={(text) => handleInputChange('email', text)}
            keyboardType="email-address"
            autoCapitalize="none"
            autoCorrect={false}
            error={errors.email}
          />

          <TonekaInput
            label="Hasło"
            value={formData.password}
            onChangeText={(text) => handleInputChange('password', text)}
            secureTextEntry
            error={errors.password}
          />

          <TonekaInput
            label="Potwierdź hasło"
            value={formData.confirmPassword}
            onChangeText={(text) => handleInputChange('confirmPassword', text)}
            secureTextEntry
            error={errors.confirmPassword}
          />

          <TonekaButton
            title="ZAREJESTRUJ SIĘ"
            variant="primary"
            onPress={handleRegister}
            disabled={loading}
            style={styles.registerButton}
          />
        </View>

        {/* Footer */}
        <View style={styles.footer}>
          <TonekaText variant="body" color="accent" style={styles.footerText}>
            Masz już konto?
          </TonekaText>
          <TouchableOpacity onPress={handleLogin}>
            <TonekaText variant="body" color="secondary" style={styles.loginText}>
              Zaloguj się
            </TonekaText>
          </TouchableOpacity>
        </View>
      </ScrollView>
    </KeyboardAvoidingView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: TonekaTheme.colors.primary,
  },
  
  scrollContainer: {
    flexGrow: 1,
    justifyContent: 'center',
    paddingHorizontal: TonekaTheme.spacing.xl,
  },
  
  header: {
    alignItems: 'center',
    marginBottom: TonekaTheme.spacing['4xl'],
  },
  
  title: {
    marginBottom: TonekaTheme.spacing.lg,
    textAlign: 'center',
  },
  
  subtitle: {
    textAlign: 'center',
    lineHeight: TonekaTheme.typography.lineHeight.relaxed * TonekaTheme.typography.fontSize.base,
  },
  
  form: {
    marginBottom: TonekaTheme.spacing['4xl'],
  },
  
  errorContainer: {
    backgroundColor: TonekaTheme.colors.error + '20',
    padding: TonekaTheme.spacing.lg,
    borderRadius: TonekaTheme.borderRadius.md,
    marginBottom: TonekaTheme.spacing.lg,
  },
  
  registerButton: {
    width: '100%',
    marginTop: TonekaTheme.spacing.lg,
  },
  
  footer: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    gap: TonekaTheme.spacing.sm,
  },
  
  footerText: {
    textTransform: 'none',
  },
  
  loginText: {
    textDecorationLine: 'underline',
  },
});

export default RegisterScreen;
