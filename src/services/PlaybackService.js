// PlaybackService.js - Required for react-native-track-player
import TrackPlayer, { Event } from 'react-native-track-player';

module.exports = async function() {
  // This service is required for react-native-track-player
  // It handles background playback events
  
  TrackPlayer.addEventListener(Event.RemotePlay, () => {
    TrackPlayer.play();
  });

  TrackPlayer.addEventListener(Event.RemotePause, () => {
    TrackPlayer.pause();
  });

  TrackPlayer.addEventListener(Event.RemoteStop, () => {
    TrackPlayer.stop();
  });

  TrackPlayer.addEventListener(Event.RemoteNext, () => {
    TrackPlayer.skipToNext();
  });

  TrackPlayer.addEventListener(Event.RemotePrevious, () => {
    TrackPlayer.skipToPrevious();
  });

  TrackPlayer.addEventListener(Event.RemoteSeek, (data) => {
    TrackPlayer.seekTo(data.position);
  });
};
