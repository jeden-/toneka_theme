import React, { useState, useEffect } from 'react';
import {
  View,
  ScrollView,
  Image,
  StyleSheet,
  Dimensions,
} from 'react-native';
import { useNavigation, useRoute } from '@react-navigation/native';

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

const ProductDetailScreen = () => {
  const navigation = useNavigation();
  const route = useRoute();
  const { product: initialProduct } = route.params;

  const [product, setProduct] = useState(initialProduct);
  const [relatedProducts, setRelatedProducts] = useState([]);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    loadProductDetails();
  }, []);

  const loadProductDetails = async () => {
    try {
      setLoading(true);
      
      // Load full product details
      const fullProduct = await WooCommerceAPI.getProduct(product.id);
      setProduct(fullProduct);
      
      // Load related products
      const related = await WooCommerceAPI.getProducts({
        category: product.category,
        per_page: 3,
        exclude: product.id,
      });
      setRelatedProducts(related);
      
    } catch (error) {
      console.error('Error loading product details:', error);
    } finally {
      setLoading(false);
    }
  };

  const handlePlaySample = async () => {
    try {
      if (product.sampleUrl) {
        await AudioService.playTrack(product);
        navigation.navigate('Player');
      }
    } catch (error) {
      console.error('Error playing sample:', error);
    }
  };

  const handlePurchase = async () => {
    try {
      // TODO: Implement purchase flow
      console.log('Purchase product:', product);
    } catch (error) {
      console.error('Error purchasing product:', error);
    }
  };

  const handleRelatedProductPress = (relatedProduct) => {
    navigation.push('ProductDetail', { product: relatedProduct });
  };

  const formatDuration = (seconds) => {
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = seconds % 60;
    return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
  };

  return (
    <ScrollView style={styles.container}>
      {/* Product Image */}
      <View style={styles.imageContainer}>
        <Image 
          source={{ uri: product.cover }} 
          style={styles.productImage}
          resizeMode="cover"
        />
        
        {/* Category Badge */}
        {product.category && (
          <View style={styles.categoryBadge}>
            <TonekaText variant="small" color="primary">
              {product.category.toUpperCase()}
            </TonekaText>
          </View>
        )}
        
        {/* Duration Badge */}
        {product.duration && (
          <View style={styles.durationBadge}>
            <TonekaText variant="small" color="secondary">
              {formatDuration(product.duration)}
            </TonekaText>
          </View>
        )}
      </View>

      {/* Product Info */}
      <View style={styles.infoContainer}>
        <TonekaText variant="h1" color="secondary" style={styles.title}>
          {product.title}
        </TonekaText>
        
        {product.artist && (
          <TonekaText variant="body" color="accent" style={styles.artist}>
            {product.artist}
          </TonekaText>
        )}
        
        {product.price && !product.isPurchased && (
          <TonekaText variant="h3" color="secondary" style={styles.price}>
            {product.price} zł
          </TonekaText>
        )}
        
        {product.isPurchased && (
          <View style={styles.purchasedBadge}>
            <TonekaText variant="body" color="primary">
              KUPIONO
            </TonekaText>
          </View>
        )}
      </View>

      {/* Actions */}
      <View style={styles.actionsContainer}>
        {product.sampleUrl && (
          <TonekaButton
            title="POSŁUCHAJ FRAGMENTU"
            variant="secondary"
            onPress={handlePlaySample}
            style={styles.actionButton}
          />
        )}
        
        {!product.isPurchased && (
          <TonekaButton
            title="KUP"
            variant="primary"
            onPress={handlePurchase}
            style={styles.actionButton}
          />
        )}
        
        {product.isPurchased && (
          <TonekaButton
            title="ODTWÓRZ"
            variant="primary"
            onPress={handlePlaySample}
            style={styles.actionButton}
          />
        )}
      </View>

      {/* Description */}
      {product.description && (
        <View style={styles.section}>
          <TonekaText variant="h3" color="secondary" style={styles.sectionTitle}>
            OPIS
          </TonekaText>
          <TonekaText variant="body" color="accent" style={styles.description}>
            {product.description}
          </TonekaText>
        </View>
      )}

      {/* Full Description */}
      {product.fullDescription && (
        <View style={styles.section}>
          <TonekaText variant="h3" color="secondary" style={styles.sectionTitle}>
            SZCZEGÓŁY
          </TonekaText>
          <TonekaText variant="body" color="accent" style={styles.description}>
            {product.fullDescription}
          </TonekaText>
        </View>
      )}

      {/* Related Products */}
      {relatedProducts.length > 0 && (
        <View style={styles.section}>
          <TonekaText variant="h3" color="secondary" style={styles.sectionTitle}>
            POWIĄZANE PRODUKTY
          </TonekaText>
          
          <ScrollView 
            horizontal 
            showsHorizontalScrollIndicator={false}
            contentContainerStyle={styles.relatedProductsContainer}
          >
            {relatedProducts.map((relatedProduct) => (
              <View key={relatedProduct.id} style={styles.relatedProductCard}>
                <ProductCard
                  product={relatedProduct}
                  onPress={() => handleRelatedProductPress(relatedProduct)}
                  onPlaySample={() => {
                    AudioService.playTrack(relatedProduct);
                    navigation.navigate('Player');
                  }}
                  showPurchaseButton={false}
                  style={styles.relatedProduct}
                />
              </View>
            ))}
          </ScrollView>
        </View>
      )}
    </ScrollView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: TonekaTheme.colors.primary,
  },
  
  imageContainer: {
    position: 'relative',
    marginBottom: TonekaTheme.spacing.xl,
  },
  
  productImage: {
    width: '100%',
    height: screenWidth,
  },
  
  categoryBadge: {
    position: 'absolute',
    top: TonekaTheme.spacing.lg,
    left: TonekaTheme.spacing.lg,
    backgroundColor: TonekaTheme.colors.secondary,
    paddingHorizontal: TonekaTheme.spacing.lg,
    paddingVertical: TonekaTheme.spacing.sm,
    borderRadius: TonekaTheme.borderRadius.md,
  },
  
  durationBadge: {
    position: 'absolute',
    top: TonekaTheme.spacing.lg,
    right: TonekaTheme.spacing.lg,
    backgroundColor: TonekaTheme.colors.black70,
    paddingHorizontal: TonekaTheme.spacing.lg,
    paddingVertical: TonekaTheme.spacing.sm,
    borderRadius: TonekaTheme.borderRadius.md,
  },
  
  infoContainer: {
    paddingHorizontal: TonekaTheme.spacing.xl,
    marginBottom: TonekaTheme.spacing.xl,
  },
  
  title: {
    marginBottom: TonekaTheme.spacing.sm,
  },
  
  artist: {
    marginBottom: TonekaTheme.spacing.lg,
  },
  
  price: {
    marginBottom: TonekaTheme.spacing.lg,
  },
  
  purchasedBadge: {
    alignSelf: 'flex-start',
    backgroundColor: TonekaTheme.colors.success,
    paddingHorizontal: TonekaTheme.spacing.lg,
    paddingVertical: TonekaTheme.spacing.sm,
    borderRadius: TonekaTheme.borderRadius.md,
  },
  
  actionsContainer: {
    flexDirection: 'row',
    paddingHorizontal: TonekaTheme.spacing.xl,
    marginBottom: TonekaTheme.spacing.xl,
    gap: TonekaTheme.spacing.lg,
  },
  
  actionButton: {
    flex: 1,
  },
  
  section: {
    paddingHorizontal: TonekaTheme.spacing.xl,
    marginBottom: TonekaTheme.spacing.xl,
  },
  
  sectionTitle: {
    marginBottom: TonekaTheme.spacing.lg,
  },
  
  description: {
    lineHeight: TonekaTheme.typography.lineHeight.relaxed * TonekaTheme.typography.fontSize.base,
  },
  
  relatedProductsContainer: {
    paddingLeft: TonekaTheme.spacing.xl,
  },
  
  relatedProductCard: {
    width: screenWidth * 0.7,
    marginRight: TonekaTheme.spacing.lg,
  },
  
  relatedProduct: {
    marginBottom: 0,
  },
});

export default ProductDetailScreen;
