import React, { useState, useEffect } from 'react';
import {
  View,
  ScrollView,
  StyleSheet,
  TouchableOpacity,
  Alert,
} from 'react-native';
import { useNavigation } from '@react-navigation/native';

// Components
import TonekaText from '../../components/common/TonekaText';
import TonekaButton from '../../components/common/TonekaButton';
import TonekaInput from '../../components/common/TonekaInput';

// Services
import AuthService from '../../services/AuthService';

// Theme
import { TonekaTheme } from '../../theme/TonekaTheme';

const ProfileScreen = () => {
  const navigation = useNavigation();
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);
  const [editing, setEditing] = useState(false);
  const [formData, setFormData] = useState({
    firstName: '',
    lastName: '',
    email: '',
  });

  useEffect(() => {
    loadUserData();
  }, []);

  const loadUserData = async () => {
    try {
      setLoading(true);
      
      const isAuthenticated = await AuthService.isAuthenticated();
      if (!isAuthenticated) {
        navigation.navigate('Auth');
        return;
      }
      
      const currentUser = await AuthService.getCurrentUser();
      if (currentUser) {
        setUser(currentUser);
        setFormData({
          firstName: currentUser.first_name || '',
          lastName: currentUser.last_name || '',
          email: currentUser.email || '',
        });
      }
      
    } catch (error) {
      console.error('Error loading user data:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleLogout = () => {
    Alert.alert(
      'Wylogowanie',
      'Czy na pewno chcesz się wylogować?',
      [
        {
          text: 'Anuluj',
          style: 'cancel',
        },
        {
          text: 'Wyloguj',
          style: 'destructive',
          onPress: async () => {
            try {
              await AuthService.logout();
              navigation.navigate('Auth');
            } catch (error) {
              console.error('Error logging out:', error);
            }
          },
        },
      ]
    );
  };

  const handleSaveProfile = async () => {
    try {
      await AuthService.updateProfile(formData);
      setEditing(false);
      await loadUserData();
      Alert.alert('Sukces', 'Profil został zaktualizowany');
    } catch (error) {
      console.error('Error updating profile:', error);
      Alert.alert('Błąd', 'Nie udało się zaktualizować profilu');
    }
  };

  const handleChangePassword = () => {
    // TODO: Implement change password screen
    Alert.alert('Info', 'Funkcja zmiany hasła będzie dostępna wkrótce');
  };

  const handlePurchaseHistory = () => {
    // TODO: Implement purchase history screen
    Alert.alert('Info', 'Historia zakupów będzie dostępna wkrótce');
  };

  const handleSettings = () => {
    // TODO: Implement settings screen
    Alert.alert('Info', 'Ustawienia będą dostępne wkrótce');
  };

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <TonekaText variant="body" color="secondary">
          Ładowanie profilu...
        </TonekaText>
      </View>
    );
  }

  if (!user) {
    return (
      <View style={styles.emptyContainer}>
        <TonekaText variant="h3" color="secondary" style={styles.emptyTitle}>
          BRAK DANYCH UŻYTKOWNIKA
        </TonekaText>
        <TonekaButton
          title="ZALOGUJ SIĘ"
          variant="primary"
          onPress={() => navigation.navigate('Auth')}
          style={styles.emptyButton}
        />
      </View>
    );
  }

  return (
    <ScrollView style={styles.container}>
      {/* Header */}
      <View style={styles.header}>
        <TonekaText variant="h2" color="secondary" style={styles.headerTitle}>
          PROFIL
        </TonekaText>
        <TonekaText variant="caption" color="accent" style={styles.headerSubtitle}>
          Zarządzaj swoim kontem
        </TonekaText>
      </View>

      {/* User Info */}
      <View style={styles.section}>
        <View style={styles.sectionHeader}>
          <TonekaText variant="h3" color="secondary" style={styles.sectionTitle}>
            DANE OSOBOWE
          </TonekaText>
          {!editing && (
            <TouchableOpacity onPress={() => setEditing(true)}>
              <TonekaText variant="caption" color="accent" style={styles.editText}>
                EDYTUJ
              </TonekaText>
            </TouchableOpacity>
          )}
        </View>

        {editing ? (
          <View style={styles.form}>
            <TonekaInput
              label="Imię"
              value={formData.firstName}
              onChangeText={(text) => setFormData({ ...formData, firstName: text })}
            />
            
            <TonekaInput
              label="Nazwisko"
              value={formData.lastName}
              onChangeText={(text) => setFormData({ ...formData, lastName: text })}
            />
            
            <TonekaInput
              label="Email"
              value={formData.email}
              onChangeText={(text) => setFormData({ ...formData, email: text })}
              keyboardType="email-address"
              autoCapitalize="none"
            />
            
            <View style={styles.formActions}>
              <TonekaButton
                title="ANULUJ"
                variant="secondary"
                onPress={() => {
                  setEditing(false);
                  setFormData({
                    firstName: user.first_name || '',
                    lastName: user.last_name || '',
                    email: user.email || '',
                  });
                }}
                style={styles.formButton}
              />
              
              <TonekaButton
                title="ZAPISZ"
                variant="primary"
                onPress={handleSaveProfile}
                style={styles.formButton}
              />
            </View>
          </View>
        ) : (
          <View style={styles.userInfo}>
            <View style={styles.infoRow}>
              <TonekaText variant="caption" color="accent" style={styles.infoLabel}>
                IMIĘ
              </TonekaText>
              <TonekaText variant="body" color="secondary" style={styles.infoValue}>
                {user.first_name || 'Nie podano'}
              </TonekaText>
            </View>
            
            <View style={styles.infoRow}>
              <TonekaText variant="caption" color="accent" style={styles.infoLabel}>
                NAZWISKO
              </TonekaText>
              <TonekaText variant="body" color="secondary" style={styles.infoValue}>
                {user.last_name || 'Nie podano'}
              </TonekaText>
            </View>
            
            <View style={styles.infoRow}>
              <TonekaText variant="caption" color="accent" style={styles.infoLabel}>
                EMAIL
              </TonekaText>
              <TonekaText variant="body" color="secondary" style={styles.infoValue}>
                {user.email}
              </TonekaText>
            </View>
          </View>
        )}
      </View>

      {/* Account Actions */}
      <View style={styles.section}>
        <TonekaText variant="h3" color="secondary" style={styles.sectionTitle}>
          KONTO
        </TonekaText>
        
        <TouchableOpacity style={styles.actionItem} onPress={handleChangePassword}>
          <TonekaText variant="body" color="secondary" style={styles.actionText}>
            ZMIEŃ HASŁO
          </TonekaText>
          <TonekaText variant="caption" color="accent">›</TonekaText>
        </TouchableOpacity>
        
        <TouchableOpacity style={styles.actionItem} onPress={handlePurchaseHistory}>
          <TonekaText variant="body" color="secondary" style={styles.actionText}>
            HISTORIA ZAKUPÓW
          </TonekaText>
          <TonekaText variant="caption" color="accent">›</TonekaText>
        </TouchableOpacity>
        
        <TouchableOpacity style={styles.actionItem} onPress={handleSettings}>
          <TonekaText variant="body" color="secondary" style={styles.actionText}>
            USTAWIENIA
          </TonekaText>
          <TonekaText variant="caption" color="accent">›</TonekaText>
        </TouchableOpacity>
      </View>

      {/* Logout */}
      <View style={styles.section}>
        <TonekaButton
          title="WYLOGUJ SIĘ"
          variant="secondary"
          onPress={handleLogout}
          style={styles.logoutButton}
        />
      </View>
    </ScrollView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: TonekaTheme.colors.primary,
  },
  
  loadingContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: TonekaTheme.colors.primary,
  },
  
  emptyContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    paddingHorizontal: TonekaTheme.spacing.xl,
  },
  
  emptyTitle: {
    textAlign: 'center',
    marginBottom: TonekaTheme.spacing.xl,
  },
  
  emptyButton: {
    minWidth: 200,
  },
  
  header: {
    paddingHorizontal: TonekaTheme.spacing.xl,
    paddingVertical: TonekaTheme.spacing.xl,
  },
  
  headerTitle: {
    marginBottom: TonekaTheme.spacing.xs,
  },
  
  headerSubtitle: {
    textTransform: 'none',
  },
  
  section: {
    paddingHorizontal: TonekaTheme.spacing.xl,
    marginBottom: TonekaTheme.spacing.xl,
  },
  
  sectionHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: TonekaTheme.spacing.lg,
  },
  
  sectionTitle: {
    flex: 1,
  },
  
  editText: {
    textDecorationLine: 'underline',
  },
  
  form: {
    gap: TonekaTheme.spacing.lg,
  },
  
  formActions: {
    flexDirection: 'row',
    gap: TonekaTheme.spacing.lg,
    marginTop: TonekaTheme.spacing.lg,
  },
  
  formButton: {
    flex: 1,
  },
  
  userInfo: {
    gap: TonekaTheme.spacing.lg,
  },
  
  infoRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingVertical: TonekaTheme.spacing.sm,
    borderBottomWidth: 0.5,
    borderBottomColor: TonekaTheme.colors.accent,
  },
  
  infoLabel: {
    flex: 1,
  },
  
  infoValue: {
    flex: 2,
    textAlign: 'right',
  },
  
  actionItem: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingVertical: TonekaTheme.spacing.lg,
    borderBottomWidth: 0.5,
    borderBottomColor: TonekaTheme.colors.accent,
  },
  
  actionText: {
    flex: 1,
  },
  
  logoutButton: {
    marginTop: TonekaTheme.spacing.lg,
  },
});

export default ProfileScreen;
