import React, { useState, useEffect } from 'react';
import {
  View,
  FlatList,
  StyleSheet,
  RefreshControl,
  TouchableOpacity,
} from 'react-native';
import { useNavigation } from '@react-navigation/native';

// Components
import TonekaText from '../../components/common/TonekaText';
import ProductCard from '../../components/shop/ProductCard';

// Services
import WooCommerceAPI from '../../services/WooCommerceAPI';
import AudioService from '../../services/AudioService';

// Theme
import { TonekaTheme } from '../../theme/TonekaTheme';

const ShopScreen = ({ route }) => {
  const navigation = useNavigation();
  const [products, setProducts] = useState([]);
  const [categories, setCategories] = useState([]);
  const [selectedCategory, setSelectedCategory] = useState(null);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');

  const initialCategory = route?.params?.category;

  useEffect(() => {
    loadShopData();
  }, []);

  useEffect(() => {
    if (initialCategory) {
      setSelectedCategory(initialCategory);
    }
  }, [initialCategory]);

  useEffect(() => {
    loadProducts();
  }, [selectedCategory, searchQuery]);

  const loadShopData = async () => {
    try {
      setLoading(true);
      
      // Load categories
      const categoriesData = await WooCommerceAPI.getProductCategories();
      setCategories(categoriesData);
      
      // Set initial category
      if (initialCategory) {
        setSelectedCategory(initialCategory);
      }
      
    } catch (error) {
      console.error('Error loading shop data:', error);
    } finally {
      setLoading(false);
    }
  };

  const loadProducts = async () => {
    try {
      let productsData;
      
      if (searchQuery) {
        productsData = await WooCommerceAPI.searchProducts(searchQuery, selectedCategory);
      } else if (selectedCategory) {
        productsData = await WooCommerceAPI.getProducts({ category: selectedCategory });
      } else {
        productsData = await WooCommerceAPI.getProducts();
      }
      
      setProducts(productsData);
      
    } catch (error) {
      console.error('Error loading products:', error);
    }
  };

  const onRefresh = async () => {
    setRefreshing(true);
    await loadProducts();
    setRefreshing(false);
  };

  const handleCategoryPress = (category) => {
    setSelectedCategory(category === selectedCategory ? null : category);
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

  const handlePurchase = async (product) => {
    try {
      // TODO: Implement purchase flow
      console.log('Purchase product:', product);
      navigation.navigate('ProductDetail', { product });
    } catch (error) {
      console.error('Error purchasing product:', error);
    }
  };

  const renderCategoryFilter = () => (
    <View style={styles.categoryFilter}>
      <FlatList
        horizontal
        showsHorizontalScrollIndicator={false}
        data={categories}
        keyExtractor={(item) => item.id.toString()}
        renderItem={({ item }) => {
          const isSelected = selectedCategory === item.id;
          return (
            <TouchableOpacity
              style={[
                styles.categoryChip,
                isSelected && styles.categoryChipSelected,
              ]}
              onPress={() => handleCategoryPress(item.id)}
            >
              <TonekaText
                variant="caption"
                color={isSelected ? 'primary' : 'secondary'}
                style={styles.categoryChipText}
              >
                {item.name.toUpperCase()}
              </TonekaText>
            </TouchableOpacity>
          );
        }}
        contentContainerStyle={styles.categoryFilterContent}
      />
    </View>
  );

  const renderHeader = () => (
    <View style={styles.header}>
      <TonekaText variant="h2" color="secondary" style={styles.headerTitle}>
        SKLEP
      </TonekaText>
      {selectedCategory && (
        <TonekaText variant="caption" color="accent" style={styles.headerSubtitle}>
          Kategoria: {categories.find(c => c.id === selectedCategory)?.name}
        </TonekaText>
      )}
    </View>
  );

  const renderProductItem = ({ item }) => (
    <ProductCard
      product={item}
      onPress={() => handleProductPress(item)}
      onPlaySample={() => handlePlaySample(item)}
      onPurchase={() => handlePurchase(item)}
      showPlayButton={true}
      showPurchaseButton={true}
      style={styles.productCard}
    />
  );

  const renderEmptyState = () => (
    <View style={styles.emptyState}>
      <TonekaText variant="h3" color="secondary" style={styles.emptyTitle}>
        BRAK PRODUKTÓW
      </TonekaText>
      <TonekaText variant="body" color="accent" style={styles.emptyDescription}>
        {selectedCategory 
          ? 'Brak produktów w wybranej kategorii'
          : 'Brak dostępnych produktów'
        }
      </TonekaText>
    </View>
  );

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <TonekaText variant="body" color="secondary">
          Ładowanie sklepu...
        </TonekaText>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <FlatList
        data={products}
        renderItem={renderProductItem}
        keyExtractor={(item) => item.id.toString()}
        ListHeaderComponent={() => (
          <View>
            {renderHeader()}
            {renderCategoryFilter()}
          </View>
        )}
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
        numColumns={1}
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
    marginBottom: TonekaTheme.spacing.xl,
  },
  
  headerTitle: {
    marginBottom: TonekaTheme.spacing.xs,
  },
  
  headerSubtitle: {
    textTransform: 'none',
  },
  
  categoryFilter: {
    marginBottom: TonekaTheme.spacing.xl,
  },
  
  categoryFilterContent: {
    paddingLeft: TonekaTheme.spacing.xl,
  },
  
  categoryChip: {
    backgroundColor: 'transparent',
    borderWidth: 1,
    borderColor: TonekaTheme.colors.secondary,
    paddingHorizontal: TonekaTheme.spacing.lg,
    paddingVertical: TonekaTheme.spacing.sm,
    borderRadius: TonekaTheme.borderRadius.md,
    marginRight: TonekaTheme.spacing.md,
  },
  
  categoryChipSelected: {
    backgroundColor: TonekaTheme.colors.secondary,
    borderColor: TonekaTheme.colors.secondary,
  },
  
  categoryChipText: {
    textTransform: 'uppercase',
    letterSpacing: TonekaTheme.typography.letterSpacing.wide,
  },
  
  productCard: {
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
    lineHeight: TonekaTheme.typography.lineHeight.relaxed * TonekaTheme.typography.fontSize.base,
  },
});

export default ShopScreen;
