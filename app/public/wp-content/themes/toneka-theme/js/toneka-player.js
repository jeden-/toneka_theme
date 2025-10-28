/**
 * Toneka Audio/Video Player - zgodny z designem Figma
 * Obsługuje odtwarzanie próbek audio i wideo z eleganckim interfejsem
 */

document.addEventListener('DOMContentLoaded', function() {
    const players = document.querySelectorAll('.toneka-figma-player');
    
    players.forEach(initializePlayer);
});

function initializePlayer(playerContainer) {
    const playerId = playerContainer.id;
    const playerData = JSON.parse(playerContainer.querySelector('.toneka-player-data').textContent);
    
    // Elementy DOM
    const audioElement = playerContainer.querySelector('.toneka-hidden-player');
    const progressContainer = playerContainer.querySelector('.toneka-progress-container');
    const progressBar = playerContainer.querySelector('.toneka-progress-bar');
    const progressHandle = playerContainer.querySelector('.toneka-progress-handle');
    const currentTimeDisplay = playerContainer.querySelector('.toneka-current-time');
    const totalTimeDisplay = playerContainer.querySelector('.toneka-total-time');
    const volumeButton = playerContainer.querySelector('.toneka-volume');
    const volumeOnIcon = playerContainer.querySelector('.toneka-volume-on');
    const volumeOffIcon = playerContainer.querySelector('.toneka-volume-off');
    const playlistButton = playerContainer.querySelector('.toneka-playlist');
    const fullscreenButton = playerContainer.querySelector('.toneka-fullscreen');
    
    // Stan playera
    let currentTrackIndex = 0;
    let isPlaying = false;
    let isDragging = false;
    let currentVolume = 1;
    let isMuted = false;
    let controlsHideTimeout = null;
    let isMouseOverControls = false;
    let trackDurations = {}; // Cache dla czasów trwania utworów
    
    // Inicjalizacja
    if (audioElement) {
        loadTrack(currentTrackIndex);
        setupEventListeners();
        
        // Ustaw proporcje dla pierwszego utworu
        setTimeout(() => {
            const isFirstTrackVideo = playerContainer.classList.contains('video-mode');
            if (isFirstTrackVideo) {
                const videoElement = playerContainer.querySelector('.toneka-background-video');
                if (videoElement && videoElement.videoWidth && videoElement.videoHeight) {
                    adjustPlayerAspectRatio(videoElement.videoWidth, videoElement.videoHeight);
                }
            } else {
                const imageBackground = playerContainer.querySelector('.toneka-background-image');
                if (imageBackground) {
                    getImageDimensions(imageBackground, (width, height) => {
                        adjustPlayerAspectRatio(width, height);
                    });
                }
            }
        }, 500);
    }
    
    function loadTrack(index) {
        if (!playerData.samples[index]) return;
        
        const track = playerData.samples[index];
        audioElement.src = track.file;
        
        // Sprawdź typ aktualnego pliku
        const fileExtension = track.file.split('.').pop().toLowerCase();
        const videoExtensions = ['mp4', 'mov', 'webm', 'avi'];
        const isCurrentTrackVideo = videoExtensions.includes(fileExtension);
        
        // Aktualizuj informacje o utworze
        const trackTitle = playerContainer.querySelector('.toneka-track-title');
        const trackType = playerContainer.querySelector('.toneka-track-type');
        
        if (trackTitle) {
            trackTitle.textContent = track.name || playerData.productName;
        }
        
        if (trackType) {
            trackType.textContent = isCurrentTrackVideo ? 'Video' : 'Audio';
        }
        
        // Przełącz tryb playera
        switchPlayerMode(isCurrentTrackVideo);
        
        // Reset progress
        progressBar.style.width = '0%';
        progressHandle.style.left = '0%';
        
        currentTrackIndex = index;
        playerContainer.setAttribute('data-current-track', index);
    }
    
    function switchPlayerMode(isVideo) {
        const videoElement = playerContainer.querySelector('.toneka-background-video');
        const imageBackground = playerContainer.querySelector('.toneka-background-image');
        
        if (isVideo) {
            // Tryb wideo - pokaż video, ukryj obraz i wizualizację
            if (videoElement) {
                videoElement.style.display = 'block';
                videoElement.src = audioElement.src; // Synchronizuj źródło
                
                // Czekaj na załadowanie metadanych video
                videoElement.addEventListener('loadedmetadata', () => {
                    adjustPlayerAspectRatio(videoElement.videoWidth, videoElement.videoHeight);
                }, { once: true });
            }
            if (imageBackground) {
                imageBackground.style.display = 'none';
            }
            playerContainer.classList.add('video-mode');
            playerContainer.classList.remove('audio-mode');
        } else {
            // Tryb audio - pokaż obraz i wizualizację, ukryj video
            if (videoElement) {
                videoElement.style.display = 'none';
            }
            if (imageBackground) {
                imageBackground.style.display = 'block';
                
                // Pobierz wymiary obrazu produktu
                getImageDimensions(imageBackground, (width, height) => {
                    adjustPlayerAspectRatio(width, height);
                });
            }
            playerContainer.classList.add('audio-mode');
            playerContainer.classList.remove('video-mode');
        }
    }
    
    function adjustPlayerAspectRatio(originalWidth, originalHeight) {
        if (!originalWidth || !originalHeight) return;
        
        const containerWidth = playerContainer.offsetWidth;
        const aspectRatio = originalWidth / originalHeight;
        
        // Oblicz wysokość zachowując proporcje, ale na podstawie szerokości kontenera
        const scaledHeight = containerWidth / aspectRatio;
        
        // Responsywne ograniczenia wysokości
        let minHeight = 200;
        let maxHeight = 9999; // Usunięte ograniczenie 800px
        
        if (window.innerWidth <= 480) {
            minHeight = 150;
            maxHeight = 9999; // Usunięte ograniczenie 400px
        } else if (window.innerWidth <= 768) {
            minHeight = 180;
            maxHeight = 9999; // Usunięte ograniczenie 600px
        }
        
        const finalHeight = Math.max(minHeight, Math.min(maxHeight, scaledHeight));
        
        // Jeśli musimy ograniczyć wysokość, przelicz szerokość
        const finalWidth = finalHeight * aspectRatio;
        const useFullWidth = finalWidth <= containerWidth;
        
        // Smooth transition
        playerContainer.style.transition = 'height 0.5s ease-in-out';
        playerContainer.style.height = finalHeight + 'px';
        
        // Dodaj klasę z informacją o proporcjach
        playerContainer.classList.remove('aspect-16-9', 'aspect-4-3', 'aspect-1-1', 'aspect-wide', 'aspect-tall');
        
        if (aspectRatio > 2.0) {
            playerContainer.classList.add('aspect-wide');
        } else if (aspectRatio > 1.6) {
            playerContainer.classList.add('aspect-16-9');
        } else if (aspectRatio > 1.2) {
            playerContainer.classList.add('aspect-4-3');
        } else if (aspectRatio > 0.8) {
            playerContainer.classList.add('aspect-1-1');
        } else {
            playerContainer.classList.add('aspect-tall');
        }
        
        // Opcjonalne logowanie dla debugowania (usuń w produkcji)
        // console.log(`Player adjusted: ${originalWidth}x${originalHeight} -> ${finalHeight}px`);
    }
    
    function getImageDimensions(element, callback) {
        const bgImage = window.getComputedStyle(element).backgroundImage;
        const imageUrl = bgImage.match(/url\(['"]?(.*?)['"]?\)/);
        
        if (imageUrl && imageUrl[1]) {
            const img = new Image();
            img.onload = function() {
                callback(this.width, this.height);
            };
            img.onerror = function() {
                // Fallback do domyślnych proporcji
                callback(800, 450); // 16:9
            };
            img.src = imageUrl[1];
        } else {
            // Fallback
            callback(800, 450);
        }
    }
    
    function getTrackDuration(trackUrl, callback) {
        // Sprawdź cache
        if (trackDurations[trackUrl]) {
            callback(trackDurations[trackUrl]);
            return;
        }
        
        // Utwórz tymczasowy element audio/video do pobrania metadanych
        const tempMedia = document.createElement(trackUrl.match(/\.(mp4|mov|webm|avi)$/i) ? 'video' : 'audio');
        tempMedia.preload = 'metadata';
        
        tempMedia.addEventListener('loadedmetadata', () => {
            const duration = tempMedia.duration;
            trackDurations[trackUrl] = duration; // Cache wynik
            callback(duration);
            tempMedia.remove(); // Usuń element
        });
        
        tempMedia.addEventListener('error', () => {
            callback(0); // Fallback dla błędów
            tempMedia.remove();
        });
        
        tempMedia.src = trackUrl;
    }
    
    function setupEventListeners() {
        // Synchronizacja video z audio
        const videoElement = playerContainer.querySelector('.toneka-background-video');
        if (videoElement) {
            audioElement.addEventListener('play', () => {
                if (playerContainer.classList.contains('video-mode')) {
                    videoElement.play().catch(e => console.warn('Video play failed:', e));
                }
            });
            
            audioElement.addEventListener('pause', () => {
                if (playerContainer.classList.contains('video-mode')) {
                    videoElement.pause();
                }
            });
            
            audioElement.addEventListener('timeupdate', () => {
                if (playerContainer.classList.contains('video-mode') && Math.abs(videoElement.currentTime - audioElement.currentTime) > 0.5) {
                    videoElement.currentTime = audioElement.currentTime;
                }
            });
        }
        
        // Progress bar
        progressContainer.addEventListener('click', handleProgressClick);
        progressContainer.addEventListener('mousedown', handleProgressMouseDown);
        document.addEventListener('mousemove', handleProgressMouseMove);
        document.addEventListener('mouseup', handleProgressMouseUp);
        
        // Volume
        if (volumeButton) {
            volumeButton.addEventListener('click', toggleMute);
        }
        
        // Playlist
        if (playlistButton) {
            playlistButton.addEventListener('click', togglePlaylist);
        }
        
        // Fullscreen
        if (fullscreenButton) {
            fullscreenButton.addEventListener('click', toggleFullscreen);
        }
        
        // Audio events
        audioElement.addEventListener('loadedmetadata', handleLoadedMetadata);
        audioElement.addEventListener('timeupdate', handleTimeUpdate);
        audioElement.addEventListener('ended', handleTrackEnded);
        audioElement.addEventListener('play', handlePlay);
        audioElement.addEventListener('pause', handlePause);
        audioElement.addEventListener('error', handleError);
        
        // Navigation controls
        const prevButton = playerContainer.querySelector('.toneka-prev');
        const nextButton = playerContainer.querySelector('.toneka-next');
        
        if (prevButton) prevButton.addEventListener('click', () => changeTrack(-1));
        if (nextButton) nextButton.addEventListener('click', () => changeTrack(1));
        
        // Keyboard shortcuts
        document.addEventListener('keydown', handleKeyboard);
        
        // Fullscreen events
        document.addEventListener('fullscreenchange', handleFullscreenChange);
        document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
        document.addEventListener('mozfullscreenchange', handleFullscreenChange);
        document.addEventListener('MSFullscreenChange', handleFullscreenChange);
        
        // Window resize - dostosuj proporcje
        window.addEventListener('resize', debounce(() => {
            if (playerContainer.offsetWidth > 0) {
                const isVideo = playerContainer.classList.contains('video-mode');
                if (isVideo) {
                    const videoElement = playerContainer.querySelector('.toneka-background-video');
                    if (videoElement && videoElement.videoWidth && videoElement.videoHeight) {
                        adjustPlayerAspectRatio(videoElement.videoWidth, videoElement.videoHeight);
                    }
                } else {
                    const imageBackground = playerContainer.querySelector('.toneka-background-image');
                    if (imageBackground) {
                        getImageDimensions(imageBackground, (width, height) => {
                            adjustPlayerAspectRatio(width, height);
                        });
                    }
                }
            }
        }, 250));
        
        // Mouse events dla auto-hide kontrolek w trybie video
        setupControlsAutoHide();
    }
    
    function setupControlsAutoHide() {
        const mainControls = playerContainer.querySelector('.toneka-main-controls');
        const bottomPanel = playerContainer.querySelector('.toneka-bottom-panel');
        
        // Mouse move - pokaż kontrolki
        playerContainer.addEventListener('mousemove', () => {
            showControls();
            scheduleControlsHide();
        });
        
        // Mouse enter na kontrolki - anuluj ukrywanie
        if (mainControls) {
            mainControls.addEventListener('mouseenter', () => {
                isMouseOverControls = true;
                clearTimeout(controlsHideTimeout);
            });
            
            mainControls.addEventListener('mouseleave', () => {
                isMouseOverControls = false;
                scheduleControlsHide();
            });
        }
        
        if (bottomPanel) {
            bottomPanel.addEventListener('mouseenter', () => {
                isMouseOverControls = true;
                clearTimeout(controlsHideTimeout);
            });
            
            bottomPanel.addEventListener('mouseleave', () => {
                isMouseOverControls = false;
                scheduleControlsHide();
            });
        }
        
        // Mouse leave z playera - ukryj kontrolki szybciej
        playerContainer.addEventListener('mouseleave', () => {
            if (isPlaying && playerContainer.classList.contains('video-mode')) {
                scheduleControlsHide(1000); // 1 sekunda zamiast 3
            }
        });
    }
    
    function showControls() {
        playerContainer.classList.remove('controls-hidden');
        playerContainer.classList.add('controls-visible');
    }
    
    function hideControls() {
        // Ukryj kontrolki tylko w trybie video podczas odtwarzania
        if (isPlaying && playerContainer.classList.contains('video-mode') && !isMouseOverControls) {
            playerContainer.classList.add('controls-hidden');
            playerContainer.classList.remove('controls-visible');
        }
    }
    
    function scheduleControlsHide(delay = 3000) {
        clearTimeout(controlsHideTimeout);
        
        const isVideoMode = playerContainer.classList.contains('video-mode');
        
        // Nie ukrywaj jeśli:
        // - Nie odtwarza
        // - Tryb audio
        // - Mysz nad kontrolkami
        if (!isPlaying || !isVideoMode || isMouseOverControls) {
            return;
        }
        
        controlsHideTimeout = setTimeout(() => {
            hideControls();
        }, delay);
    }
    
    function togglePlayPause() {
        if (isPlaying) {
            audioElement.pause();
        } else {
            audioElement.play().catch(error => {
                console.warn('Autoplay prevented:', error);
            });
        }
    }
    
    function skipTime(seconds) {
        if (audioElement.duration) {
            audioElement.currentTime = Math.max(0, Math.min(audioElement.duration, audioElement.currentTime + seconds));
        }
    }
    
    function changeTrack(direction) {
        const newIndex = currentTrackIndex + direction;
        if (newIndex >= 0 && newIndex < playerData.samples.length) {
            const wasPlaying = isPlaying;
            loadTrack(newIndex);
            if (wasPlaying) {
                setTimeout(() => audioElement.play(), 100);
            }
        }
    }
    
    function handleProgressClick(e) {
        if (!audioElement.duration) return;
        
        const rect = progressContainer.getBoundingClientRect();
        const clickX = e.clientX - rect.left;
        const percentage = clickX / rect.width;
        const newTime = percentage * audioElement.duration;
        
        audioElement.currentTime = newTime;
    }
    
    function handleProgressMouseDown(e) {
        isDragging = true;
        handleProgressClick(e);
    }
    
    function handleProgressMouseMove(e) {
        if (!isDragging) return;
        handleProgressClick(e);
    }
    
    function handleProgressMouseUp() {
        isDragging = false;
    }
    
    function toggleMute() {
        if (isMuted) {
            audioElement.volume = currentVolume;
            isMuted = false;
            showVolumeOn();
        } else {
            currentVolume = audioElement.volume;
            audioElement.volume = 0;
            isMuted = true;
            showVolumeOff();
        }
    }
    
    function togglePlaylist() {
        // Sprawdź czy mamy więcej niż jeden utwór
        if (playerData.samples.length <= 1) {
            return;
        }
        
        // Znajdź playlistę w HTML (teraz jest renderowana w PHP)
        const playlist = playerContainer.querySelector('.toneka-playlist');
        
        if (playlist) {
            // Toggle visibility
            const isVisible = playlist.getAttribute('data-visible') === 'true';
            playlist.setAttribute('data-visible', !isVisible ? 'true' : 'false');
            
            // Jeśli otwieramy po raz pierwszy, dodaj event listenery
            if (!isVisible && !playlist.hasAttribute('data-initialized')) {
                setupPlaylistListeners(playlist);
                playlist.setAttribute('data-initialized', 'true');
            }
        }
    }
    
    function setupPlaylistListeners(playlist) {
        const items = playlist.querySelectorAll('.toneka-playlist-item');
        
        items.forEach(item => {
            item.addEventListener('click', () => {
                const trackIndex = parseInt(item.dataset.index);
                if (trackIndex !== currentTrackIndex) {
                    const wasPlaying = isPlaying;
                    loadTrack(trackIndex);
                    if (wasPlaying) {
                        setTimeout(() => audioElement.play(), 100);
                    }
                    
                    // Zaktualizuj aktywny element
                    items.forEach(i => i.classList.remove('active'));
                    item.classList.add('active');
                }
            });
            
            // Obsługa przycisku play/pause w elemencie playlisty
            const playButton = item.querySelector('.toneka-playlist-item-play');
            if (playButton) {
                playButton.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const trackIndex = parseInt(item.dataset.index);
                    
                    if (trackIndex === currentTrackIndex) {
                        // Toggle play/pause dla aktualnego utworu
                        togglePlayPause();
                    } else {
                        // Załaduj i odtwórz nowy utwór
                        loadTrack(trackIndex);
                        setTimeout(() => audioElement.play(), 100);
                        items.forEach(i => i.classList.remove('active'));
                        item.classList.add('active');
                    }
                });
            }
        });
    }
    
    function showPlaylist() {
        const playlistHtml = `
            <div class="toneka-playlist-overlay">
                <div class="toneka-playlist-content">
                    <div class="toneka-playlist-header">
                        <h3>Playlist</h3>
                        <button class="toneka-playlist-close">&times;</button>
                    </div>
                    <div class="toneka-playlist-items">
                        ${playerData.samples.map((sample, index) => `
                            <div class="toneka-playlist-item ${index === currentTrackIndex ? 'active' : ''}" data-track="${index}">
                                <div class="toneka-playlist-item-info">
                                    <div class="toneka-playlist-item-name">${sample.name || `Track ${index + 1}`}</div>
                                    <div class="toneka-playlist-item-duration" data-track-index="${index}">--:--</div>
                                </div>
                                <div class="toneka-playlist-item-controls">
                                    ${index === currentTrackIndex && isPlaying ? 
                                        '<svg width="12" height="12" viewBox="0 0 24 24" fill="white"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>' : 
                                        '<svg width="12" height="12" viewBox="0 0 24 24" fill="white"><path d="M8 5v14l11-7z"/></svg>'
                                    }
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>
        `;
        
        playerContainer.insertAdjacentHTML('beforeend', playlistHtml);
        
        // Dodaj event listenery
        const playlist = playerContainer.querySelector('.toneka-playlist-overlay');
        const closeBtn = playlist.querySelector('.toneka-playlist-close');
        const items = playlist.querySelectorAll('.toneka-playlist-item');
        
        closeBtn.addEventListener('click', () => {
            playlist.classList.add('closing');
            setTimeout(() => {
                if (playlist.parentNode) {
                    playlist.parentNode.removeChild(playlist);
                }
            }, 300);
        });
        
        items.forEach(item => {
            item.addEventListener('click', () => {
                const trackIndex = parseInt(item.dataset.track);
                if (trackIndex !== currentTrackIndex) {
                    const wasPlaying = isPlaying;
                    loadTrack(trackIndex);
                    if (wasPlaying) {
                        setTimeout(() => audioElement.play(), 100);
                    }
                    // Ukryj playlist po wybraniu
                    closeBtn.click();
                }
            });
        });
        
        // Zamknij po kliknięciu poza playlistą
        playlist.addEventListener('click', (e) => {
            if (e.target === playlist) {
                closeBtn.click();
            }
        });
        
        // Pobierz czasy trwania dla wszystkich utworów
        loadPlaylistDurations(playlist);
    }
    
    function loadPlaylistDurations(playlist) {
        // Pobierz wszystkie elementy duration w playliście
        const durationElements = playlist.querySelectorAll('.toneka-playlist-item-duration');
        
        durationElements.forEach(element => {
            const trackIndex = parseInt(element.dataset.trackIndex);
            const sample = playerData.samples[trackIndex];
            
            if (sample && sample.file) {
                // Pokaż loading state
                element.textContent = '...';
                
                getTrackDuration(sample.file, (duration) => {
                    if (duration > 0) {
                        element.textContent = formatTime(duration);
                    } else {
                        element.textContent = '--:--';
                    }
                });
            }
        });
    }
    
    function toggleFullscreen() {
        if (!document.fullscreenElement && !document.webkitFullscreenElement && !document.mozFullScreenElement && !document.msFullscreenElement) {
            // Wejdź w fullscreen
            const element = playerContainer;
            if (element.requestFullscreen) {
                element.requestFullscreen();
            } else if (element.webkitRequestFullscreen) {
                element.webkitRequestFullscreen();
            } else if (element.mozRequestFullScreen) {
                element.mozRequestFullScreen();
            } else if (element.msRequestFullscreen) {
                element.msRequestFullscreen();
            }
            playerContainer.classList.add('toneka-fullscreen');
        } else {
            // Wyjdź z fullscreen
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
            playerContainer.classList.remove('toneka-fullscreen');
        }
    }
    
    function handleLoadedMetadata() {
        const duration = formatTime(audioElement.duration);
        if (totalTimeDisplay) {
            totalTimeDisplay.textContent = duration;
        }
        playerContainer.classList.remove('loading');
    }
    
    function handleTimeUpdate() {
        if (!audioElement.duration || isDragging) return;
        
        const percentage = (audioElement.currentTime / audioElement.duration) * 100;
        progressBar.style.width = percentage + '%';
        progressHandle.style.left = percentage + '%';
        
        if (currentTimeDisplay) {
            currentTimeDisplay.textContent = formatTime(audioElement.currentTime);
        }
    }
    
    function handleTrackEnded() {
        // Auto-next track if available
        if (currentTrackIndex < playerData.samples.length - 1) {
            changeTrack(1);
            setTimeout(() => audioElement.play(), 100);
        } else {
            progressBar.style.width = '0%';
            progressHandle.style.left = '0%';
            audioElement.currentTime = 0;
        }
    }
    
    function handlePlay() {
        isPlaying = true;
        playerContainer.classList.add('playing');
        playerContainer.classList.remove('paused');
        
        
        // Zaplanuj ukrycie kontrolek w trybie video
        scheduleControlsHide();
    }
    
    function handlePause() {
        isPlaying = false;
        playerContainer.classList.add('paused');
        playerContainer.classList.remove('playing');
        
        // Pokaż kontrolki po pauzie
        showControls();
        clearTimeout(controlsHideTimeout);
    }
    
    function handleError(e) {
        console.error('Audio playback error:', e);
        playerContainer.classList.add('error');
    }
    
    function handleKeyboard(e) {
        // Sprawdź czy player jest aktywny (focus w kontenerze)
        if (!playerContainer.contains(document.activeElement) && document.activeElement !== document.body) {
            return;
        }
        
        switch(e.code) {
            case 'Space':
                e.preventDefault();
                togglePlayPause();
                showControls();
                scheduleControlsHide();
                break;
            case 'ArrowLeft':
                e.preventDefault();
                skipTime(-10);
                break;
            case 'ArrowRight':
                e.preventDefault();
                skipTime(10);
                break;
            case 'ArrowUp':
                e.preventDefault();
                changeTrack(-1);
                break;
            case 'ArrowDown':
                e.preventDefault();
                changeTrack(1);
                break;
            case 'KeyM':
                e.preventDefault();
                toggleMute();
                break;
            case 'KeyF':
                e.preventDefault();
                toggleFullscreen();
                break;
            case 'Escape':
                // Zamknij playlist jeśli jest otwarta
                const playlist = playerContainer.querySelector('.toneka-playlist-overlay');
                if (playlist) {
                    const closeBtn = playlist.querySelector('.toneka-playlist-close');
                    if (closeBtn) closeBtn.click();
                }
                break;
        }
    }
    
    function handleFullscreenChange() {
        const isFullscreen = !!(document.fullscreenElement || document.webkitFullscreenElement || 
                               document.mozFullScreenElement || document.msFullscreenElement);
        
        if (isFullscreen) {
            playerContainer.classList.add('toneka-fullscreen');
        } else {
            playerContainer.classList.remove('toneka-fullscreen');
        }
    }
    
    // Funkcje showPlayButton i showPauseButton nie są już potrzebne
    // Główny przycisk play/pause został usunięty z centrum obrazka
    
    function showVolumeOn() {
        if (volumeOnIcon && volumeOffIcon) {
            volumeOnIcon.style.display = 'block';
            volumeOffIcon.style.display = 'none';
        }
    }
    
    function showVolumeOff() {
        if (volumeOnIcon && volumeOffIcon) {
            volumeOnIcon.style.display = 'none';
            volumeOffIcon.style.display = 'block';
        }
    }
    
    function formatTime(seconds) {
        if (!seconds || isNaN(seconds)) return '0:00';
        
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = Math.floor(seconds % 60);
        return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
    }
    
    // Publiczne API dla playera (opcjonalne)
    playerContainer.tonekaPlayer = {
        play: () => audioElement.play(),
        pause: () => audioElement.pause(),
        toggle: togglePlayPause,
        skipForward: () => skipTime(15),
        skipBackward: () => skipTime(-15),
        nextTrack: () => changeTrack(1),
        prevTrack: () => changeTrack(-1),
        setVolume: (volume) => { audioElement.volume = Math.max(0, Math.min(1, volume)); },
        getCurrentTime: () => audioElement.currentTime,
        getDuration: () => audioElement.duration,
        getCurrentTrack: () => currentTrackIndex,
        getTotalTracks: () => playerData.samples.length
    };
}

// Utility functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Export dla użycia w innych skryptach
window.TonekaPlayer = {
    initializePlayer,
    debounce
};
