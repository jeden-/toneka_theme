import React, { useState, useEffect } from 'react';
import {
  View,
  ScrollView,
  Image,
  TouchableOpacity,
  StyleSheet,
  Dimensions,
  RefreshControl,
} from 'react-native';
import { useNavigation } from '@react-navigation/native';

// Components
import TonekaText from '../../components/common/TonekaText';
import TonekaButton from '../../components/common/TonekaButton';
import ProductCard from '../../components/shop/ProductCard';

// Services
import WooCommerceAPI from '../../services/WooCommerceAPI';
import AudioService from '../../services/AudioService';

// Theme
import { TonekaTheme } from '../../theme/TonekaTheme';

const { width: screenWidth } = Dimensions.get('window');

const HomeScreen = () => {
  const navigation = useNavigation();
  const [featuredProduct, setFeaturedProduct] = useState(null);
  const [latestAudio, setLatestAudio] = useState([]);
  const [latestMerch, setLatestMerch] = useState([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => {
    loadHomeData();
  }, []);

  const loadHomeData = async () => {
    try {
      setLoading(true);
      
      // Load featured product (hero section)
      const featuredProducts = await WooCommerceAPI.getFeaturedProducts(1);
      if (featuredProducts.length > 0) {
        setFeaturedProduct(featuredProducts[0]);
      }
      
      // Load latest audio products
      const audioProducts = await WooCommerceAPI.getProducts({
        category: 'sluchowiska',
        per_page: 3,
        orderby: 'date',
        order: 'desc',
      });
      setLatestAudio(audioProducts);
      
      // Load latest merch products
      const merchProducts = await WooCommerceAPI.getProducts({
        category: 'merch',
        per_page: 3,
        orderby: 'date',
        order: 'desc',
      });
      setLatestMerch(merchProducts);
      
    } catch (error) {
      console.error('Error loading home data:', error);
    } finally {
      setLoading(false);
    }
  };

  const onRefresh = async () => {
    setRefreshing(true);
    await loadHomeData();
    setRefreshing(false);
  };

  const handlePlaySample = async (product) => {
    try {
      if (product.sampleUrl) {
        await AudioService.playTrack(product);
        navigation.navigate('Player');
      }
    } catch (error) {
      console.error('Error playing sample:', error);
    }
  };

  const handleProductPress = (product) => {
    navigation.navigate('ProductDetail', { product });
  };

  const handleCategoryPress = (category) => {
    navigation.navigate('Shop', { category });
  };

  const renderHeroSection = () => {
    if (!featuredProduct) return null;

    return (
      <View style={styles.heroContainer}>
        <View style={styles.heroContent}>
          {/* Play Button */}
          <TouchableOpacity 
            style={styles.playButton}
            onPress={() => handlePlaySample(featuredProduct)}
          >
            <View style={styles.playIcon}>
              <TonekaText variant="h3" color="primary">▶</TonekaText>
            </View>
          </TouchableOpacity>
          
          {/* Title */}
          <TonekaText variant="h1" color="secondary" style={styles.heroTitle}>
            {featuredProduct.title}
          </TonekaText>
          
          {/* Listen Button */}
          <TonekaButton
            title="POSŁUCHAJ"
            variant="secondary"
            onPress={() => handlePlaySample(featuredProduct)}
            style={styles.listenButton}
          />
        </View>
        
        {/* Cover Image */}
        <Image 
          source={{ uri: featuredProduct.cover }} 
          style={styles.coverImage}
          resizeMode="cover"
        />
      </View>
    );
  };

  const renderProductSection = (title, products, category) => {
    if (!products || products.length === 0) return null;

    return (
      <View style={styles.section}>
        <View style={styles.sectionHeader}>
          <TonekaText variant="h2" color="secondary" style={styles.sectionTitle}>
            {title}
          </TonekaText>
          <TouchableOpacity onPress={() => handleCategoryPress(category)}>
            <TonekaText variant="caption" color="accent" style={styles.seeAllText}>
              ZOBACZ WSZYSTKO
            </TonekaText>
          </TouchableOpacity>
        </View>
        
        <ScrollView 
          horizontal 
          showsHorizontalScrollIndicator={false}
          contentContainerStyle={styles.horizontalScroll}
        >
          {products.map((product) => (
            <View key={product.id} style={styles.productCardContainer}>
              <ProductCard
                product={product}
                onPress={() => handleProductPress(product)}
                onPlaySample={() => handlePlaySample(product)}
                showPurchaseButton={false}
                style={styles.productCard}
              />
            </View>
          ))}
        </ScrollView>
      </View>
    );
  };

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <TonekaText variant="body" color="secondary">
          Ładowanie...
        </TonekaText>
      </View>
    );
  }

  return (
    <ScrollView 
      style={styles.container}
      refreshControl={
        <RefreshControl
          refreshing={refreshing}
          onRefresh={onRefresh}
          tintColor={TonekaTheme.colors.secondary}
          colors={[TonekaTheme.colors.secondary]}
        />
      }
    >
      {/* Top Bar - Logo + Menu */}
      <View style={styles.topBar}>
        <TonekaText variant="h2" color="secondary" style={styles.logo}>
          TONEKA
        </TonekaText>
        <TouchableOpacity style={styles.menuButton}>
          <View style={styles.hamburgerLine} />
          <View style={styles.hamburgerLine} />
          <View style={styles.hamburgerLine} />
        </TouchableOpacity>
      </View>

      {/* Hero Section */}
      {renderHeroSection()}

      {/* Latest Audio Section */}
      {renderProductSection('NAJNOWSZE SŁUCHOWISKA', latestAudio, 'sluchowiska')}

      {/* Latest Merch Section */}
      {renderProductSection('MERCH', latestMerch, 'merch')}
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
  
  topBar: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: TonekaTheme.spacing.xl,
    paddingVertical: TonekaTheme.spacing.md,
    height: 82,
  },
  
  logo: {
    fontWeight: TonekaTheme.typography.fontWeight.bold,
  },
  
  menuButton: {
    width: 40,
    height: 40,
    justifyContent: 'center',
    alignItems: 'center',
  },
  
  hamburgerLine: {
    width: 30,
    height: 2,
    backgroundColor: TonekaTheme.colors.secondary,
    marginVertical: 2,
  },
  
  heroContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    paddingHorizontal: TonekaTheme.spacing.lg,
    paddingVertical: TonekaTheme.spacing.xl,
    minHeight: 400,
  },
  
  heroContent: {
    flex: 1,
    alignItems: 'flex-start',
    marginRight: TonekaTheme.spacing.xl,
  },
  
  playButton: {
    width: 64,
    height: 64,
    borderRadius: 32,
    backgroundColor: TonekaTheme.colors.secondary,
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: TonekaTheme.spacing.lg,
  },
  
  playIcon: {
    marginLeft: 4, // Offset for play triangle
  },
  
  heroTitle: {
    marginBottom: TonekaTheme.spacing.lg,
    lineHeight: TonekaTheme.typography.lineHeight.tight * TonekaTheme.typography.fontSize['5xl'],
  },
  
  listenButton: {
    minWidth: 110,
  },
  
  coverImage: {
    width: 240,
    height: 240,
    borderRadius: TonekaTheme.borderRadius.md,
  },
  
  section: {
    marginBottom: TonekaTheme.spacing['4xl'],
  },
  
  sectionHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: TonekaTheme.spacing.xl,
    marginBottom: TonekaTheme.spacing.lg,
  },
  
  sectionTitle: {
    flex: 1,
  },
  
  seeAllText: {
    textDecorationLine: 'underline',
  },
  
  horizontalScroll: {
    paddingLeft: TonekaTheme.spacing.xl,
  },
  
  productCardContainer: {
    width: screenWidth * 0.7,
    marginRight: TonekaTheme.spacing.lg,
  },
  
  productCard: {
    marginBottom: 0,
  },
});

export default HomeScreen;
