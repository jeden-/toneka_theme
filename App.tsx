import React, { useEffect } from 'react';
import { StatusBar } from 'react-native';
import { SafeAreaProvider } from 'react-native-safe-area-context';

// Navigation
import AppNavigator from './src/navigation/AppNavigator';

// Services
import AudioService from './src/services/AudioService';

// Theme
import { TonekaTheme } from './src/theme/TonekaTheme';

const App = () => {
  useEffect(() => {
    // Setup audio service
    AudioService.setupPlayer().catch(console.error);
    
    // Cleanup on app close
    return () => {
      AudioService.destroy().catch(console.error);
    };
  }, []);

  return (
    <SafeAreaProvider>
      <StatusBar 
        barStyle="light-content" 
        backgroundColor={TonekaTheme.colors.primary}
      />
      <AppNavigator />
    </SafeAreaProvider>
  );
};

export default App;
