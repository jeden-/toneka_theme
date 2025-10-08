import React, { useState, useEffect } from 'react';
import {
  View,
  Image,
  TouchableOpacity,
  StyleSheet,
  Dimensions,
  Slider,
} from 'react-native';
import { useNavigation, useFocusEffect } from '@react-navigation/native';

// Components
import TonekaText from '../../components/common/TonekaText';
import TonekaButton from '../../components/common/TonekaButton';

// Services
import AudioService from '../../services/AudioService';

// Theme
import { TonekaTheme } from '../../theme/TonekaTheme';

const { width: screenWidth } = Dimensions.get('window');

const PlayerScreen = () => {
  const navigation = useNavigation();
  const [currentTrack, setCurrentTrack] = useState(null);
  const [isPlaying, setIsPlaying] = useState(false);
  const [position, setPosition] = useState(0);
  const [duration, setDuration] = useState(0);
  const [playbackState, setPlaybackState] = useState(null);

  useFocusEffect(
    React.useCallback(() => {
      setupPlayer();
      return () => {
        // Cleanup if needed
      };
    }, [])
  );

  useEffect(() => {
    const interval = setInterval(updatePosition, 1000);
    return () => clearInterval(interval);
  }, []);

  const setupPlayer = async () => {
    try {
      await AudioService.setupPlayer();
      
      // Add event listeners
      AudioService.addEventListener('playback-state', handlePlaybackStateChange);
      AudioService.addEventListener('playback-track-changed', handleTrackChanged);
      
      // Get current state
      const state = await AudioService.getPlaybackState();
      setPlaybackState(state);
      setIsPlaying(state?.state === 'playing');
      
      const currentTrackId = await AudioService.getCurrentTrack();
      if (currentTrackId) {
        // Get track info from queue or current track
        const queue = await AudioService.getQueue();
        const track = queue.find(t => t.id === currentTrackId);
        if (track) {
          setCurrentTrack({
            id: track.id,
            title: track.title,
            artist: track.artist,
            cover: track.artwork,
            duration: track.duration,
          });
        }
      }
      
      updatePosition();
    } catch (error) {
      console.error('Error setting up player:', error);
    }
  };

  const updatePosition = async () => {
    try {
      const currentPosition = await AudioService.getPosition();
      const currentDuration = await AudioService.getDuration();
      
      setPosition(currentPosition);
      setDuration(currentDuration);
    } catch (error) {
      console.error('Error updating position:', error);
    }
  };

  const handlePlaybackStateChange = (data) => {
    setPlaybackState(data);
    setIsPlaying(data.state === 'playing');
  };

  const handleTrackChanged = (data) => {
    if (data.track) {
      setCurrentTrack({
        id: data.track.id,
        title: data.track.title,
        artist: data.track.artist,
        cover: data.track.artwork,
        duration: data.track.duration,
      });
    }
  };

  const handlePlayPause = async () => {
    try {
      await AudioService.togglePlayback();
    } catch (error) {
      console.error('Error toggling playback:', error);
    }
  };

  const handlePrevious = async () => {
    try {
      await AudioService.skipToPrevious();
    } catch (error) {
      console.error('Error skipping to previous:', error);
    }
  };

  const handleNext = async () => {
    try {
      await AudioService.skipToNext();
    } catch (error) {
      console.error('Error skipping to next:', error);
    }
  };

  const handleSeek = async (value) => {
    try {
      await AudioService.seekTo(value);
      setPosition(value);
    } catch (error) {
      console.error('Error seeking:', error);
    }
  };

  const formatTime = (seconds) => {
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = Math.floor(seconds % 60);
    return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
  };

  if (!currentTrack) {
    return (
      <View style={styles.emptyContainer}>
        <TonekaText variant="h3" color="secondary" style={styles.emptyTitle}>
          BRAK ODTWARZANEGO UTWORU
        </TonekaText>
        <TonekaText variant="body" color="accent" style={styles.emptyDescription}>
          Wybierz utwór z biblioteki lub sklepu, aby rozpocząć odtwarzanie
        </TonekaText>
        <TonekaButton
          title="PRZEJDŹ DO BIBLIOTEKI"
          variant="primary"
          onPress={() => navigation.navigate('Library')}
          style={styles.emptyButton}
        />
      </View>
    );
  }

  return (
    <View style={styles.container}>
      {/* Album Art */}
      <View style={styles.albumArtContainer}>
        <Image 
          source={{ uri: currentTrack.cover }} 
          style={styles.albumArt}
          resizeMode="cover"
        />
      </View>

      {/* Track Info */}
      <View style={styles.trackInfo}>
        <TonekaText 
          variant="h2" 
          color="secondary" 
          style={styles.trackTitle}
          numberOfLines={2}
        >
          {currentTrack.title}
        </TonekaText>
        
        <TonekaText 
          variant="body" 
          color="accent" 
          style={styles.trackArtist}
          numberOfLines={1}
        >
          {currentTrack.artist}
        </TonekaText>
      </View>

      {/* Progress Bar */}
      <View style={styles.progressContainer}>
        <Slider
          style={styles.progressBar}
          minimumValue={0}
          maximumValue={duration}
          value={position}
          onValueChange={handleSeek}
          minimumTrackTintColor={TonekaTheme.colors.secondary}
          maximumTrackTintColor={TonekaTheme.colors.accent}
          thumbStyle={styles.progressThumb}
        />
        
        <View style={styles.timeContainer}>
          <TonekaText variant="caption" color="accent">
            {formatTime(position)}
          </TonekaText>
          <TonekaText variant="caption" color="accent">
            {formatTime(duration)}
          </TonekaText>
        </View>
      </View>

      {/* Controls */}
      <View style={styles.controls}>
        <TouchableOpacity 
          style={styles.controlButton}
          onPress={handlePrevious}
        >
          <TonekaText variant="h3" color="secondary">⏮</TonekaText>
        </TouchableOpacity>
        
        <TouchableOpacity 
          style={styles.playButton}
          onPress={handlePlayPause}
        >
          <TonekaText variant="h1" color="primary">
            {isPlaying ? '⏸' : '▶'}
          </TonekaText>
        </TouchableOpacity>
        
        <TouchableOpacity 
          style={styles.controlButton}
          onPress={handleNext}
        >
          <TonekaText variant="h3" color="secondary">⏭</TonekaText>
        </TouchableOpacity>
      </View>

      {/* Additional Actions */}
      <View style={styles.actions}>
        <TonekaButton
          title="DODAJ DO PLAYLISTY"
          variant="secondary"
          onPress={() => {
            // TODO: Implement add to playlist
            console.log('Add to playlist');
          }}
          style={styles.actionButton}
        />
        
        <TonekaButton
          title="KUP PEŁNĄ WERSJĘ"
          variant="primary"
          onPress={() => {
            // TODO: Navigate to purchase
            console.log('Purchase full version');
          }}
          style={styles.actionButton}
        />
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: TonekaTheme.colors.primary,
    paddingHorizontal: TonekaTheme.spacing.xl,
    paddingVertical: TonekaTheme.spacing.xl,
  },
  
  emptyContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    paddingHorizontal: TonekaTheme.spacing.xl,
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
  
  emptyButton: {
    minWidth: 200,
  },
  
  albumArtContainer: {
    alignItems: 'center',
    marginBottom: TonekaTheme.spacing.xl,
  },
  
  albumArt: {
    width: screenWidth * 0.7,
    height: screenWidth * 0.7,
    borderRadius: TonekaTheme.borderRadius.lg,
  },
  
  trackInfo: {
    alignItems: 'center',
    marginBottom: TonekaTheme.spacing.xl,
  },
  
  trackTitle: {
    textAlign: 'center',
    marginBottom: TonekaTheme.spacing.sm,
  },
  
  trackArtist: {
    textAlign: 'center',
  },
  
  progressContainer: {
    marginBottom: TonekaTheme.spacing.xl,
  },
  
  progressBar: {
    width: '100%',
    height: 40,
  },
  
  progressThumb: {
    backgroundColor: TonekaTheme.colors.secondary,
    width: 20,
    height: 20,
  },
  
  timeContainer: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginTop: TonekaTheme.spacing.sm,
  },
  
  controls: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: TonekaTheme.spacing.xl,
  },
  
  controlButton: {
    width: 60,
    height: 60,
    justifyContent: 'center',
    alignItems: 'center',
  },
  
  playButton: {
    width: 80,
    height: 80,
    borderRadius: 40,
    backgroundColor: TonekaTheme.colors.secondary,
    justifyContent: 'center',
    alignItems: 'center',
    marginHorizontal: TonekaTheme.spacing.xl,
  },
  
  actions: {
    gap: TonekaTheme.spacing.lg,
  },
  
  actionButton: {
    width: '100%',
  },
});

export default PlayerScreen;
