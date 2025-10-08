import React from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import { createStackNavigator } from '@react-navigation/stack';
import Icon from 'react-native-vector-icons/MaterialIcons';

// Screens
import HomeScreen from '../screens/Home/HomeScreen';
import LibraryScreen from '../screens/Library/LibraryScreen';
import ShopScreen from '../screens/Shop/ShopScreen';
import PlayerScreen from '../screens/Player/PlayerScreen';
import ProfileScreen from '../screens/Profile/ProfileScreen';
import LoginScreen from '../screens/Auth/LoginScreen';
import RegisterScreen from '../screens/Auth/RegisterScreen';
import ProductDetailScreen from '../screens/Shop/ProductDetailScreen';

// Theme
import { TonekaTheme } from '../theme/TonekaTheme';

const Tab = createBottomTabNavigator();
const Stack = createStackNavigator();

// Main Tab Navigator
const MainTabNavigator = () => {
  return (
    <Tab.Navigator
      screenOptions={({ route }) => ({
        tabBarIcon: ({ focused, color, size }) => {
          let iconName;

          switch (route.name) {
            case 'Home':
              iconName = 'home';
              break;
            case 'Library':
              iconName = 'library-music';
              break;
            case 'Shop':
              iconName = 'store';
              break;
            case 'Player':
              iconName = 'play-circle-filled';
              break;
            case 'Profile':
              iconName = 'person';
              break;
            default:
              iconName = 'circle';
          }

          return <Icon name={iconName} size={size} color={color} />;
        },
        tabBarActiveTintColor: TonekaTheme.colors.secondary,
        tabBarInactiveTintColor: TonekaTheme.colors.accent,
        tabBarStyle: {
          backgroundColor: TonekaTheme.colors.primary,
          borderTopColor: TonekaTheme.colors.accent,
          borderTopWidth: 0.5,
          paddingBottom: 5,
          paddingTop: 5,
          height: 60,
        },
        tabBarLabelStyle: {
          fontSize: TonekaTheme.typography.fontSize.xs,
          fontFamily: TonekaTheme.typography.fontFamily.regular,
          textTransform: TonekaTheme.typography.textTransform.uppercase,
          letterSpacing: TonekaTheme.typography.letterSpacing.wide,
        },
        headerStyle: {
          backgroundColor: TonekaTheme.colors.primary,
          borderBottomColor: TonekaTheme.colors.accent,
          borderBottomWidth: 0.5,
        },
        headerTintColor: TonekaTheme.colors.secondary,
        headerTitleStyle: {
          fontSize: TonekaTheme.typography.fontSize.lg,
          fontFamily: TonekaTheme.typography.fontFamily.regular,
          fontWeight: TonekaTheme.typography.fontWeight.medium,
          textTransform: TonekaTheme.typography.textTransform.uppercase,
        },
      })}
    >
      <Tab.Screen 
        name="Home" 
        component={HomeScreen}
        options={{
          title: 'GŁÓWNA',
          headerTitle: 'TONEKA',
        }}
      />
      <Tab.Screen 
        name="Library" 
        component={LibraryScreen}
        options={{
          title: 'BIBLIOTEKA',
          headerTitle: 'MOJA BIBLIOTEKA',
        }}
      />
      <Tab.Screen 
        name="Shop" 
        component={ShopScreen}
        options={{
          title: 'SKLEP',
          headerTitle: 'SKLEP',
        }}
      />
      <Tab.Screen 
        name="Player" 
        component={PlayerScreen}
        options={{
          title: 'ODTWARZACZ',
          headerTitle: 'ODTWARZACZ',
        }}
      />
      <Tab.Screen 
        name="Profile" 
        component={ProfileScreen}
        options={{
          title: 'PROFIL',
          headerTitle: 'PROFIL',
        }}
      />
    </Tab.Navigator>
  );
};

// Auth Stack Navigator
const AuthStackNavigator = () => {
  return (
    <Stack.Navigator
      screenOptions={{
        headerStyle: {
          backgroundColor: TonekaTheme.colors.primary,
          borderBottomColor: TonekaTheme.colors.accent,
          borderBottomWidth: 0.5,
        },
        headerTintColor: TonekaTheme.colors.secondary,
        headerTitleStyle: {
          fontSize: TonekaTheme.typography.fontSize.lg,
          fontFamily: TonekaTheme.typography.fontFamily.regular,
          fontWeight: TonekaTheme.typography.fontWeight.medium,
          textTransform: TonekaTheme.typography.textTransform.uppercase,
        },
      }}
    >
      <Stack.Screen 
        name="Login" 
        component={LoginScreen}
        options={{
          title: 'LOGOWANIE',
          headerShown: false,
        }}
      />
      <Stack.Screen 
        name="Register" 
        component={RegisterScreen}
        options={{
          title: 'REJESTRACJA',
          headerShown: false,
        }}
      />
    </Stack.Navigator>
  );
};

// Main App Navigator
const AppNavigator = () => {
  return (
    <NavigationContainer>
      <Stack.Navigator
        screenOptions={{
          headerShown: false,
        }}
      >
        <Stack.Screen 
          name="MainTabs" 
          component={MainTabNavigator}
        />
        <Stack.Screen 
          name="Auth" 
          component={AuthStackNavigator}
        />
        <Stack.Screen 
          name="ProductDetail" 
          component={ProductDetailScreen}
          options={{
            headerShown: true,
            title: 'SZCZEGÓŁY PRODUKTU',
            headerStyle: {
              backgroundColor: TonekaTheme.colors.primary,
              borderBottomColor: TonekaTheme.colors.accent,
              borderBottomWidth: 0.5,
            },
            headerTintColor: TonekaTheme.colors.secondary,
            headerTitleStyle: {
              fontSize: TonekaTheme.typography.fontSize.lg,
              fontFamily: TonekaTheme.typography.fontFamily.regular,
              fontWeight: TonekaTheme.typography.fontWeight.medium,
              textTransform: TonekaTheme.typography.textTransform.uppercase,
            },
          }}
        />
      </Stack.Navigator>
    </NavigationContainer>
  );
};

export default AppNavigator;
