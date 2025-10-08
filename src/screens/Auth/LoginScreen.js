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

const LoginScreen = () => {
  const navigation = useNavigation();
  const [formData, setFormData] = useState({
    email: '',
    password: '',
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

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleLogin = async () => {
    if (!validateForm()) return;

    try {
      setLoading(true);
      
      const result = await AuthService.login(formData.email, formData.password);
      
      if (result.success) {
        // Navigation will be handled by the main app
        navigation.navigate('MainTabs');
      }
      
    } catch (error) {
      console.error('Login error:', error);
      setErrors({ general: error.message || 'Błąd logowania' });
    } finally {
      setLoading(false);
    }
  };

  const handleForgotPassword = () => {
    // TODO: Implement forgot password
    console.log('Forgot password');
  };

  const handleRegister = () => {
    navigation.navigate('Register');
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
            Zaloguj się do swojego konta
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

          <TouchableOpacity 
            style={styles.forgotPassword}
            onPress={handleForgotPassword}
          >
            <TonekaText variant="caption" color="accent" style={styles.forgotPasswordText}>
              Zapomniałeś hasła?
            </TonekaText>
          </TouchableOpacity>

          <TonekaButton
            title="ZALOGUJ SIĘ"
            variant="primary"
            onPress={handleLogin}
            disabled={loading}
            style={styles.loginButton}
          />
        </View>

        {/* Footer */}
        <View style={styles.footer}>
          <TonekaText variant="body" color="accent" style={styles.footerText}>
            Nie masz konta?
          </TonekaText>
          <TouchableOpacity onPress={handleRegister}>
            <TonekaText variant="body" color="secondary" style={styles.registerText}>
              Zarejestruj się
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
  
  forgotPassword: {
    alignSelf: 'flex-end',
    marginBottom: TonekaTheme.spacing.xl,
  },
  
  forgotPasswordText: {
    textDecorationLine: 'underline',
  },
  
  loginButton: {
    width: '100%',
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
  
  registerText: {
    textDecorationLine: 'underline',
  },
});

export default LoginScreen;
