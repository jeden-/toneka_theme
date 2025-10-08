import React from 'react';
import { View, Image, TouchableOpacity, StyleSheet } from 'react-native';
import { TonekaTheme } from '../../theme/TonekaTheme';
import TonekaText from '../common/TonekaText';
import TonekaButton from '../common/TonekaButton';

const ProductCard = ({ 
  product, 
  onPress, 
  onPlaySample, 
  onPurchase,
  showPlayButton = true,
  showPurchaseButton = true,
  style 
}) => {
  const {
    id,
    title,
    artist,
    cover,
    price,
    category,
    duration,
    sampleUrl,
    isPurchased = false,
  } = product;

  const cardStyle = [
    styles.card,
    style,
  ];

  return (
    <TouchableOpacity 
      style={cardStyle} 
      onPress={onPress}
      activeOpacity={0.8}
    >
      {/* Cover Image */}
      <View style={styles.imageContainer}>
        <Image 
          source={{ uri: cover }} 
          style={styles.image}
          resizeMode="cover"
        />
        
        {/* Category Badge */}
        {category && (
          <View style={styles.categoryBadge}>
            <TonekaText variant="small" color="primary">
              {category.toUpperCase()}
            </TonekaText>
          </View>
        )}
        
        {/* Duration Badge */}
        {duration && (
          <View style={styles.durationBadge}>
            <TonekaText variant="small" color="secondary">
              {formatDuration(duration)}
            </TonekaText>
          </View>
        )}
      </View>

      {/* Product Info */}
      <View style={styles.infoContainer}>
        <TonekaText 
          variant="body" 
          color="secondary" 
          style={styles.title}
          numberOfLines={2}
        >
          {title}
        </TonekaText>
        
        {artist && (
          <TonekaText 
            variant="caption" 
            color="accent" 
            style={styles.artist}
            numberOfLines={1}
          >
            {artist}
          </TonekaText>
        )}
        
        {/* Price */}
        {price && !isPurchased && (
          <TonekaText 
            variant="body" 
            color="secondary" 
            style={styles.price}
          >
            {price} zł
          </TonekaText>
        )}
        
        {/* Purchased Badge */}
        {isPurchased && (
          <View style={styles.purchasedBadge}>
            <TonekaText variant="small" color="primary">
              KUPIONO
            </TonekaText>
          </View>
        )}
      </View>

      {/* Action Buttons */}
      <View style={styles.actionsContainer}>
        {showPlayButton && sampleUrl && (
          <TonekaButton
            title="POSŁUCHAJ"
            variant="secondary"
            onPress={() => onPlaySample && onPlaySample(product)}
            style={styles.actionButton}
          />
        )}
        
        {showPurchaseButton && !isPurchased && (
          <TonekaButton
            title="KUP"
            variant="primary"
            onPress={() => onPurchase && onPurchase(product)}
            style={styles.actionButton}
          />
        )}
        
        {isPurchased && (
          <TonekaButton
            title="ODTWÓRZ"
            variant="primary"
            onPress={() => onPress && onPress(product)}
            style={styles.actionButton}
          />
        )}
      </View>
    </TouchableOpacity>
  );
};

// Helper function to format duration
const formatDuration = (seconds) => {
  const minutes = Math.floor(seconds / 60);
  const remainingSeconds = seconds % 60;
  return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
};

const styles = StyleSheet.create({
  card: {
    backgroundColor: TonekaTheme.colors.surface,
    borderRadius: TonekaTheme.borderRadius.md,
    padding: TonekaTheme.spacing.lg,
    marginBottom: TonekaTheme.spacing.lg,
    ...TonekaTheme.shadows.sm,
  },
  
  imageContainer: {
    position: 'relative',
    marginBottom: TonekaTheme.spacing.md,
  },
  
  image: {
    width: '100%',
    height: 200,
    borderRadius: TonekaTheme.borderRadius.md,
  },
  
  categoryBadge: {
    position: 'absolute',
    top: TonekaTheme.spacing.sm,
    left: TonekaTheme.spacing.sm,
    backgroundColor: TonekaTheme.colors.secondary,
    paddingHorizontal: TonekaTheme.spacing.sm,
    paddingVertical: TonekaTheme.spacing.xs,
    borderRadius: TonekaTheme.borderRadius.sm,
  },
  
  durationBadge: {
    position: 'absolute',
    top: TonekaTheme.spacing.sm,
    right: TonekaTheme.spacing.sm,
    backgroundColor: TonekaTheme.colors.black70,
    paddingHorizontal: TonekaTheme.spacing.sm,
    paddingVertical: TonekaTheme.spacing.xs,
    borderRadius: TonekaTheme.borderRadius.sm,
  },
  
  infoContainer: {
    marginBottom: TonekaTheme.spacing.md,
  },
  
  title: {
    marginBottom: TonekaTheme.spacing.xs,
    fontWeight: TonekaTheme.typography.fontWeight.medium,
  },
  
  artist: {
    marginBottom: TonekaTheme.spacing.sm,
  },
  
  price: {
    fontWeight: TonekaTheme.typography.fontWeight.medium,
  },
  
  purchasedBadge: {
    alignSelf: 'flex-start',
    backgroundColor: TonekaTheme.colors.success,
    paddingHorizontal: TonekaTheme.spacing.sm,
    paddingVertical: TonekaTheme.spacing.xs,
    borderRadius: TonekaTheme.borderRadius.sm,
    marginTop: TonekaTheme.spacing.xs,
  },
  
  actionsContainer: {
    flexDirection: 'row',
    gap: TonekaTheme.spacing.sm,
  },
  
  actionButton: {
    flex: 1,
  },
});

export default ProductCard;
