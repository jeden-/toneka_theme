import React, { useState, useEffect } from 'react';
import {
  View,
  FlatList,
  StyleSheet,
  RefreshControl,
  TouchableOpacity,
} from 'react-native';
import { useNavigation, useFocusEffect } from '@react-navigation/native';

// Components
import TonekaText from '../../components/common/TonekaText';
import ProductCard from '../../components/shop/ProductCard';

// Services
import WooCommerceAPI from '../../services/WooCommerceAPI';
import AudioService from '../../services/AudioService';
import AuthService from '../../services/AuthService';

// Theme
import { TonekaTheme } from '../../theme/TonekaTheme';

const LibraryScreen = () => {
  const navigation = useNavigation();
  const [purchasedTracks, setPurchasedTracks] = useState([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [user, setUser] = useState(null);

  useFocusEffect(
    React.useCallback(() => {
      loadLibraryData();
    }, [])
  );

  useEffect(() => {
    checkAuth();
  }, []);

  const checkAuth = async () => {
    const isAuthenticated = await AuthService.isAuthenticated();
    if (!isAuthenticated) {
      navigation.navigate('Auth');
      return;
    }
    
    const currentUser = await AuthService.getCurrentUser();
    setUser(currentUser);
  };

  const loadLibraryData = async () => {
    try {
      setLoading(true);
      
      const isAuthenticated = await AuthService.isAuthenticated();
      if (!isAuthenticated) {
        navigation.navigate('Auth');
        return;
      }
      
      const currentUser = await AuthService.getCurrentUser();
      if (!currentUser) {
        navigation.navigate('Auth');
        return;
      }
      
      const tracks = await WooCommerceAPI.getPurchasedTracks(currentUser.id);
      setPurchasedTracks(tracks);
      
    } catch (error) {
      console.error('Error loading library data:', error);
    } finally {
      setLoading(false);
    }
  };

  const onRefresh = async () => {
    setRefreshing(true);
    await loadLibraryData();
    setRefreshing(false);
  };

  const handlePlayTrack = async (track) => {
    try {
      if (track.audioUrl) {
        await AudioService.playTrack(track);
        navigation.navigate('Player');
      }
    } catch (error) {
      console.error('Error playing track:', error);
    }
  };

  const handlePlayPlaylist = async () => {
    try {
      if (purchasedTracks.length > 0) {
        await AudioService.playPlaylist(purchasedTracks);
        navigation.navigate('Player');
      }
    } catch (error) {
      console.error('Error playing playlist:', error);
    }
  };

  const handleProductPress = (product) => {
    navigation.navigate('ProductDetail', { product });
  };

  const renderEmptyState = () => (
    <View style={styles.emptyState}>
      <TonekaText variant="h2" color="secondary" style={styles.emptyTitle}>
        BRAK KUPIONYCH UTWORÓW
      </TonekaText>
      <TonekaText variant="body" color="accent" style={styles.emptyDescription}>
        Odwiedź sklep, aby kupić swoje pierwsze słuchowiska
      </TonekaText>
      <TouchableOpacity 
        style={styles.shopButton}
        onPress={() => navigation.navigate('Shop')}
      >
        <TonekaText variant="body" color="primary">
          PRZEJDŹ DO SKLEPU
        </TonekaText>
      </TouchableOpacity>
    </View>
  );

  const renderHeader = () => (
    <View style={styles.header}>
      <View style={styles.headerInfo}>
        <TonekaText variant="h2" color="secondary" style={styles.headerTitle}>
          MOJA BIBLIOTEKA
        </TonekaText>
        <TonekaText variant="caption" color="accent" style={styles.headerSubtitle}>
          {purchasedTracks.length} {purchasedTracks.length === 1 ? 'utwór' : 'utworów'}
        </TonekaText>
      </View>
      
      {purchasedTracks.length > 0 && (
        <TouchableOpacity 
          style={styles.playAllButton}
          onPress={handlePlayPlaylist}
        >
          <TonekaText variant="caption" color="primary">
            ODTWÓRZ WSZYSTKO
          </TonekaText>
        </TouchableOpacity>
      )}
    </View>
  );

  const renderTrackItem = ({ item }) => (
    <ProductCard
      product={item}
      onPress={() => handleProductPress(item)}
      onPlaySample={() => handlePlayTrack(item)}
      showPlayButton={true}
      showPurchaseButton={false}
      style={styles.trackCard}
    />
  );

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <TonekaText variant="body" color="secondary">
          Ładowanie biblioteki...
        </TonekaText>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <FlatList
        data={purchasedTracks}
        renderItem={renderTrackItem}
        keyExtractor={(item) => item.id.toString()}
        ListHeaderComponent={renderHeader}
        ListEmptyComponent={renderEmptyState}
        contentContainerStyle={styles.listContainer}
        refreshControl={
          <RefreshControl
            refreshing={refreshing}
            onRefresh={onRefresh}
            tintColor={TonekaTheme.colors.secondary}
            colors={[TonekaTheme.colors.secondary]}
          />
        }
        showsVerticalScrollIndicator={false}
      />
    </View>
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
  
  listContainer: {
    paddingHorizontal: TonekaTheme.spacing.xl,
    paddingTop: TonekaTheme.spacing.lg,
  },
  
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: TonekaTheme.spacing.xl,
  },
  
  headerInfo: {
    flex: 1,
  },
  
  headerTitle: {
    marginBottom: TonekaTheme.spacing.xs,
  },
  
  headerSubtitle: {
    textTransform: 'none',
  },
  
  playAllButton: {
    backgroundColor: TonekaTheme.colors.secondary,
    paddingHorizontal: TonekaTheme.spacing.lg,
    paddingVertical: TonekaTheme.spacing.sm,
    borderRadius: TonekaTheme.borderRadius.md,
  },
  
  trackCard: {
    marginBottom: TonekaTheme.spacing.lg,
  },
  
  emptyState: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    paddingHorizontal: TonekaTheme.spacing.xl,
    paddingVertical: TonekaTheme.spacing['4xl'],
  },
  
  emptyTitle: {
    textAlign: 'center',
    marginBottom: TonekaTheme.spacing.lg,
  },
  
  emptyDescription: {
    textAlign: 'center',
    marginBottom: TonekaTheme.spacing.xl,
    lineHeight: TonekaTheme.typography.lineHeight.relaxed * TonekaTheme.typography.fontSize.base,
  },
  
  shopButton: {
    backgroundColor: TonekaTheme.colors.secondary,
    paddingHorizontal: TonekaTheme.spacing.xl,
    paddingVertical: TonekaTheme.spacing.lg,
    borderRadius: TonekaTheme.borderRadius.md,
  },
});

export default LibraryScreen;
