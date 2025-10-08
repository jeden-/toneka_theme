import TrackPlayer, { 
  Capability, 
  Event, 
  RepeatMode,
  State 
} from 'react-native-track-player';

class AudioService {
  constructor() {
    this.isSetup = false;
    this.currentTrack = null;
    this.playlist = [];
    this.listeners = [];
  }

  // Setup player
  async setupPlayer() {
    if (this.isSetup) return;
    
    try {
      await TrackPlayer.setupPlayer({
        capabilities: [
          Capability.Play,
          Capability.Pause,
          Capability.SkipToNext,
          Capability.SkipToPrevious,
          Capability.SeekTo,
          Capability.Stop,
        ],
        compactCapabilities: [
          Capability.Play,
          Capability.Pause,
          Capability.SkipToNext,
          Capability.SkipToPrevious,
        ],
        notificationCapabilities: [
          Capability.Play,
          Capability.Pause,
          Capability.SkipToNext,
          Capability.SkipToPrevious,
        ],
      });

      // Set repeat mode
      await TrackPlayer.setRepeatMode(RepeatMode.Off);
      
      this.isSetup = true;
      console.log('AudioService: Player setup completed');
    } catch (error) {
      console.error('AudioService: Setup failed', error);
      throw error;
    }
  }

  // Add event listeners
  addEventListener(event, callback) {
    this.listeners.push({ event, callback });
    TrackPlayer.addEventListener(event, callback);
  }

  removeEventListener(event, callback) {
    this.listeners = this.listeners.filter(listener => 
      listener.event !== event || listener.callback !== callback
    );
    TrackPlayer.removeEventListener(event, callback);
  }

  // Play single track
  async playTrack(track) {
    await this.setupPlayer();
    
    try {
      // Clear current playlist
      await TrackPlayer.reset();
      
      // Add track to player
      await TrackPlayer.add({
        id: track.id.toString(),
        url: track.audioUrl || track.sampleUrl,
        title: track.title,
        artist: track.artist,
        artwork: track.cover,
        duration: track.duration,
      });
      
      // Start playback
      await TrackPlayer.play();
      
      this.currentTrack = track;
      console.log('AudioService: Playing track', track.title);
    } catch (error) {
      console.error('AudioService: Play track failed', error);
      throw error;
    }
  }

  // Play playlist
  async playPlaylist(tracks, startIndex = 0) {
    await this.setupPlayer();
    
    try {
      // Clear current playlist
      await TrackPlayer.reset();
      
      // Add all tracks
      const trackPlayerTracks = tracks.map(track => ({
        id: track.id.toString(),
        url: track.audioUrl || track.sampleUrl,
        title: track.title,
        artist: track.artist,
        artwork: track.cover,
        duration: track.duration,
      }));
      
      await TrackPlayer.add(trackPlayerTracks);
      
      // Start from specified index
      if (startIndex > 0) {
        await TrackPlayer.skip(startIndex);
      }
      
      // Start playback
      await TrackPlayer.play();
      
      this.playlist = tracks;
      this.currentTrack = tracks[startIndex];
      console.log('AudioService: Playing playlist', tracks.length, 'tracks');
    } catch (error) {
      console.error('AudioService: Play playlist failed', error);
      throw error;
    }
  }

  // Control methods
  async togglePlayback() {
    try {
      const state = await TrackPlayer.getPlaybackState();
      if (state.state === State.Playing) {
        await TrackPlayer.pause();
      } else {
        await TrackPlayer.play();
      }
    } catch (error) {
      console.error('AudioService: Toggle playback failed', error);
      throw error;
    }
  }

  async play() {
    try {
      await TrackPlayer.play();
    } catch (error) {
      console.error('AudioService: Play failed', error);
      throw error;
    }
  }

  async pause() {
    try {
      await TrackPlayer.pause();
    } catch (error) {
      console.error('AudioService: Pause failed', error);
      throw error;
    }
  }

  async stop() {
    try {
      await TrackPlayer.stop();
    } catch (error) {
      console.error('AudioService: Stop failed', error);
      throw error;
    }
  }

  async seekTo(position) {
    try {
      await TrackPlayer.seekTo(position);
    } catch (error) {
      console.error('AudioService: Seek failed', error);
      throw error;
    }
  }

  async skipToNext() {
    try {
      await TrackPlayer.skipToNext();
    } catch (error) {
      console.error('AudioService: Skip to next failed', error);
      throw error;
    }
  }

  async skipToPrevious() {
    try {
      await TrackPlayer.skipToPrevious();
    } catch (error) {
      console.error('AudioService: Skip to previous failed', error);
      throw error;
    }
  }

  // Get current state
  async getPlaybackState() {
    try {
      return await TrackPlayer.getPlaybackState();
    } catch (error) {
      console.error('AudioService: Get playback state failed', error);
      return null;
    }
  }

  async getCurrentTrack() {
    try {
      const track = await TrackPlayer.getCurrentTrack();
      return track;
    } catch (error) {
      console.error('AudioService: Get current track failed', error);
      return null;
    }
  }

  async getPosition() {
    try {
      return await TrackPlayer.getPosition();
    } catch (error) {
      console.error('AudioService: Get position failed', error);
      return 0;
    }
  }

  async getDuration() {
    try {
      return await TrackPlayer.getDuration();
    } catch (error) {
      console.error('AudioService: Get duration failed', error);
      return 0;
    }
  }

  async getQueue() {
    try {
      return await TrackPlayer.getQueue();
    } catch (error) {
      console.error('AudioService: Get queue failed', error);
      return [];
    }
  }

  // Playlist management
  async addToQueue(track) {
    try {
      await TrackPlayer.add({
        id: track.id.toString(),
        url: track.audioUrl || track.sampleUrl,
        title: track.title,
        artist: track.artist,
        artwork: track.cover,
        duration: track.duration,
      });
    } catch (error) {
      console.error('AudioService: Add to queue failed', error);
      throw error;
    }
  }

  async removeFromQueue(trackId) {
    try {
      await TrackPlayer.remove(trackId.toString());
    } catch (error) {
      console.error('AudioService: Remove from queue failed', error);
      throw error;
    }
  }

  async clearQueue() {
    try {
      await TrackPlayer.reset();
      this.playlist = [];
      this.currentTrack = null;
    } catch (error) {
      console.error('AudioService: Clear queue failed', error);
      throw error;
    }
  }

  // Utility methods
  formatDuration(seconds) {
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = Math.floor(seconds % 60);
    return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
  }

  // Cleanup
  async destroy() {
    try {
      await TrackPlayer.destroy();
      this.isSetup = false;
      this.currentTrack = null;
      this.playlist = [];
      
      // Remove all listeners
      this.listeners.forEach(({ event, callback }) => {
        TrackPlayer.removeEventListener(event, callback);
      });
      this.listeners = [];
      
      console.log('AudioService: Destroyed');
    } catch (error) {
      console.error('AudioService: Destroy failed', error);
      throw error;
    }
  }
}

export default new AudioService();
