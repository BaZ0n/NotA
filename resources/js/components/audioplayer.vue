<template>
<div id="audio-player-app" class="container mt-5">
  <div class="container" id="player-container">
    <div class="row align-items-center">
      <div class="col track-info">
        <img class="playlist_image" :src="albumPhotoSrc">  
        <div class="track-text-info">
            <h5 style="color:var(--placeholder);"> {{ store?.currentArtistName || 'Исполнитель'}}</h5>
            <h4>{{store.currentTrackInfo?.trackName || 'Название'}}</h4>
        </div>
      </div>
      <div class="col multimedia-btn">
        <audio ref="audioPlayer" 
          :src="store.audioSrc" 
          @timeupdate="updateProgress" 
          @ended="nextTrack"></audio>
    
        <div class="multimediaButtonsContainer">

          <Button class="multBTN" @click="repeat">
            <RepeatIcon class="icon mx-3"></RepeatIcon>
          </Button>

          <button class="multBTN" @click="prevTrack">
            <PreviousIcon class="icon"></PreviousIcon>
          </button>
      
          <button class="multBTN" v-if="!isPlaying" @click="play">
            <PlayIcon class="icon"></PlayIcon>
          </button>
      
          <button class="multBTN" v-else @click="pause">
            <PauseIcon class="icon"></PauseIcon>
          </button>
      
          <button class="multBTN" @click="nextTrack">
            <NextIcon class="icon"></NextIcon>
          </button>

          <Button class="multBTN" @click="shuffle">
            <ShuffleIcon class="icon mx-3"></ShuffleIcon>
          </Button>
        </div>

        <div class="progress-container">
          <span class="time-current">{{ formatTime(currentTime) }}</span>
          <input 
            type="range" 
            class="progress-bar" 
            min="0" 
            :max="duration" 
            :value="currentTime"
            step="0.1"
            @input="seek"
          >
          <span class="time-total">{{ formatTime(duration) }}</span>
        </div>
      </div>

      <div class="col additionalBtn">
        <button class="multBTN" @click="likeTrack">
          <LikeIcon class="icons_additional"></LikeIcon>
        </button>
        
        <button class="multBTN" @click="toggleQueueVisibility">
          <QueueIcon class="icons_additional"></QueueIcon>
        </button>

        <div class="dropdown-container">
          <button class="multBTN" @mouseenter="showDropDownMenu = true" @showDropDownMenu="showVolumeSlider = false">
            <MoreIcon class="icons_additional"></MoreIcon>
          </button>

          <transition name="fade">
            <div v-if="showDropDownMenu" class="menu-popup">
              
            </div>
          </transition>
        </div>
        

        <div class="volume-container" @mouseenter="showVolumeSlider = true" @mouseleave="showVolumeSlider = false">
          <button class="multBTN">
            <VolumeUpIcon v-if="!isMuted" class="icons_additional"></VolumeUpIcon>
            <VolumeOffIcon v-else class="icons_additional"></VolumeOffIcon>
          </button>
          
          <transition name="fade">
            <div v-if="showVolumeSlider" class="volume-popup">
              <input
                type="range"
                class="volume-slider-vertical"
                min="0"
                max="1"
                step="0.01"
                v-model="volumeLevel"
                @input="setVolume"
                orient="vertical"
              >
            </div>
          </transition>
        </div>
      </div>
    </div>
  </div>

  <!-- Окно очереди проигрывания -->
  <div v-if="showQueue" class="queue-modal" @click.self="toggleQueueVisibility">
    <div class="queue-container">
      <div class="queue-header">
        <h3>Очередь проигрывания</h3>
        <button @click="toggleQueueVisibility" class="close-queue">
          &times;
        </button>
      </div>
      <div class="queue-list">
        <div v-if="queueTracks.length === 0" class="empty-queue">
          Очередь пуста
        </div>
        <div 
          v-for="(track, index) in queueTracks" 
          :key="index" 
          class="queue-item"
          :class="{ 'current-track': index === 0 && !isNextUp }"
          @click="playFromQueue(index)"
        >
          <div class="queue-item-info">
            <span class="queue-track-name">{{ track.trackName }}</span>
            <span class="queue-artist">{{ track.artistName }}</span>
          </div>
          <button class="remove-from-queue" @click.stop="removeFromQueue(index)">
            &times;
          </button>
        </div>
      </div>
      <div v-if="nextUpTracks.length > 0" class="next-up-section">
        <h4>Далее в плейлисте</h4>
        <div 
          v-for="(track, index) in nextUpTracks" 
          :key="'next-'+index" 
          class="queue-item next-up"
          @click="playNextUp(index)"
        >
          <div class="queue-item-info">
            <span class="queue-track-name">{{ track.trackName }}</span>
            <span class="queue-artist">{{ track.artistName }}</span>
          </div>
          <button class="add-to-queue" @click.stop="addToQueue(track)">
            +
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

</template>

<script setup>
  import axios from 'axios'
  import { ref, onMounted, onUnmounted } from 'vue'
  import { useAudioPlayerStore } from '@/stores/useAudioPlayerStore'
  import { watch, computed } from 'vue'

  //Иконки
  import PlayIcon from '@/assets/icons/playTrackIcon.svg'
  import PauseIcon from '@/assets/icons/pauseIcon.svg'
  import PreviousIcon from '@/assets/icons/previousIcon.svg'
  import NextIcon from '@/assets/icons/nextIcon.svg'
  import MoreIcon from '@/assets/icons/moreIcon.svg'
  import LikeIcon from '@/assets/icons/likeIcon.svg'
  import QueueIcon from '@/assets/icons/queueIcon.svg'
  import VolumeUpIcon from '@/assets/icons/volumeUpIcon.svg'
  import VolumeOffIcon from '@/assets/icons/volumeOffIcon.svg'
  import RepeatIcon from '@/assets/icons/repeatIcon.svg'
  import ShuffleIcon from '@/assets/icons/shuffleIcon.svg'

  // Плейлист
  const tracks = []
  const currentIndex = ref(0)
  const playlistID = ref(null)
  const isPlaying = ref(false)
  const audioElement = ref(null)
  const store = useAudioPlayerStore()
  const audioPlayer = ref(null)
  const moreClicked = ref(false)
  const trackID = ref(null)
  const isFavorite = ref(false)
  const albumPhoto = "/storage/templates/userImage.svg"
  let socket = null

    // Для прогресс бара
  const currentTime = ref(0)
  const duration = ref(0)

  // начальный уровень громкости (50%)
  const volumeLevel = ref(0.5) 
  const isMuted = ref(false)
  const showVolumeSlider = ref(false)

  // Очередь проигрывания
  const showQueue = ref(false)
  const queueTracks = ref([])
  const nextUpTracks = ref([])

  // Кнопки управления
  const isLoop = ref(false)

  watch(() => store.audioSrc, async (newSrc) => {
    if (!newSrc || !audioPlayer.value) return;
    playlistID.value = store.currentPlaylistID 
    trackID.value = store.trackID;
    console.log(store.currentAlbumPhoto)
    
    // Сбрасываем состояние
    currentTime.value = 0;
    duration.value = 0;
    isPlaying.value = false;

    // Обработчики для аудио
    audioPlayer.value.onloadedmetadata = () => {
      duration.value = audioPlayer.value.duration;
    };

    audioPlayer.value.oncanplaythrough = async () => {
      if (store.isPlaying) {
        try {
          await audioPlayer.value.play();
          isPlaying.value = true;
        } catch (error) {
          console.error("Ошибка:", error);

          // Обработка ошибки автовоспроизведения (например, показ кнопки play)
          isPlaying.value = false;
          store.pause();
        }
      }
    };

    if (store.justSelected) {
      try {
        const response = await axios.get(`/playlist/${playlistID.value}/tracks`)
        tracks.value = response.data.tracks
        updateNextUpTracks()
        store.selectedPlaying()
      } catch (error) {
        console.error("Ошибка загрузки треков:", error)
      }
    }

    // Начинаем загрузку
    audioPlayer.value.load();
  }, { immediate: true });

  // Обновление треков "Далее в плейлисте"
  const updateNextUpTracks = () => {
    if (!tracks.value || !store.currentTrackInfo) return;
    const currentTrackIndex = tracks.value.findIndex(
      track => track.id === store.currentTrackInfo.id
    );
    
    if (currentTrackIndex === -1) {
      nextUpTracks.value = [];
      return;
    }
    
    // Берем следующие 5 треков после текущего
    nextUpTracks.value = tracks.value.slice(
      currentTrackIndex + 1,
      currentTrackIndex + 6
    );
    
  };

  // Управление очередью
  const toggleQueueVisibility = () => {
    showQueue.value = !showQueue.value;
  };

  const addToQueue = (track) => {
    queueTracks.value.push(track);
  };

  const removeFromQueue = (index) => {
    queueTracks.value.splice(index, 1);
  };


  const playFromQueue = (index) => {
    if (index >= queueTracks.value.length) return;
    
    const track = queueTracks.value[index];
    store.setTrack(track);
    
    // Удаляем трек из очереди, если он сейчас играет
    if (index === 0) {
      queueTracks.value.shift();
    }
    
    play();
  };

  // Добавление трека в очередь
  const playNextUp = (index) => {
    if (index >= nextUpTracks.value.length) return;
    
    const track = nextUpTracks.value[index];
    // store.setTrack(track);
    updateNextUpTracks();
    play();
  };


  // Управление воспроизведением
  const play = () => {
    if (store.isSynchronizedMode) {
      sendToWebSocket({ action: 'play', trackID: store.currentTrackInfo.id })
    } else if (audioPlayer.value){
      audioPlayer.value.play()
      isPlaying.value = true
      store.play()
    }
  };

  const albumPhotoSrc = computed(() => {
    return store.value?.currentAlbumPhoto 
      ? '/storage/' + store.currentAlbumPhoto 
      : '/storage/templates/playlistImage.svg';
  });

    // '/storage/templates/userImage.svg'

  // Пауза
  const pause = () => {
    if (store.isSynchronizedMode) {
      sendToWebSocket({ action: 'play', trackID: store.currentTrackInfo.id})
    }
    if (audioPlayer.value) {
      audioPlayer.value.pause();
      isPlaying.value = false;
      store.pause();
    }
  };

  // Следующий трек
  const nextTrack = () => {
    if (store.isSynchronizedMode) {
      sendToWebSocket('nextTrack');
      return;
    }

    handleNextTrack();
  };

  const handleNextTrack = () => {
    if (queueTracks.value.length > 0) {
      const nextTrack = queueTracks.value[0];
      const trackData = {
        ...nextTrack,
        audioSrc: `/storage/${nextTrack.path.replace('public/audio/', '')}`
      };
      store.setTrack(trackData);
      queueTracks.value.shift();
    } else if (nextUpTracks.value.length > 0) {
      const nextTrack = nextUpTracks.value[0];
      const trackData = {
        ...nextTrack,
        audioSrc: `/storage/${nextTrack.path.replace('public/audio/', '')}`
      };
      store.setTrack(trackData);
      updateNextUpTracks();
    } else if (nextUpTracks.value.length === 0 && isLoop) {
      isPlaying.value = false;
      store.pause();
    } else if (tracks.value.length) {
      currentIndex.value = (currentIndex.value + 1) % tracks.value.length;
      const nextTrack = tracks.value[currentIndex.value];
      const trackData = {
        ...nextTrack,
        audioSrc: `/storage/${nextTrack.path.replace('public/audio/', '')}`
      };
      store.setTrack(trackData);
      updateNextUpTracks();
    }

    play();
  };

  // Предыдущий трек
  const prevTrack = () => {
    if (store.isSynchronizedMode) {
      sendToWebSocket('prevTrack');
      return;
    }

    handlePrevTrack();
  };

  const handlePrevTrack = () => {
    if (audioPlayer.value.currentTime > 3 || (currentIndex.value === 0 && isLoop)) {
      audioPlayer.value.currentTime = 0;
    } else if (tracks.value.length) {
      if (!isLoop) {
        currentIndex.value = (currentIndex.value - 1 + tracks.value.length) % tracks.value.length;
      } else {
        currentIndex.value = currentIndex.value - 1;
      }

      const prevTrack = tracks.value[currentIndex.value];
      const trackData = {
        ...prevTrack,
        audioSrc: `/storage/${prevTrack.path.replace('public/audio/', '')}`
      };

      store.setTrack(trackData);
      updateNextUpTracks();
      play();
    }
  };

  // Ползунок громкости
  const setVolume = () => {
    if (audioPlayer.value) {
      audioPlayer.value.volume = volumeLevel.value
      // Если включен mute, отключаем его при изменении громкости
      if (isMuted.value) {
        isMuted.value = false
      }
    }
  }

  // Мут
  const toggleMute = () => {
    if (audioPlayer.value) {
      isMuted.value = !isMuted.value
      audioPlayer.value.muted = isMuted.value
    }
  }

  // Прогресс бар функции
  const updateProgress = () => {
    if (audioPlayer.value) {
      currentTime.value = audioPlayer.value.currentTime;
      if (!duration.value) {
        duration.value = audioPlayer.value.duration;
      }
    }
  };

  // Ползунок
  const seek = (e) => {
    const seekTime = parseFloat(e.target.value);
    if (isNaN(seekTime)) return;

    if (store.isSynchronizedMode) {
      sendToWebSocket('seek', { time: seekTime });
    } else {
      handleSeek(seekTime);
    }
  };

  const handleSeek = (time) => {
    if (audioPlayer.value) {
      audioPlayer.value.currentTime = time;
      currentTime.value = time;
    }
  };

  // Формат длительности
  const formatTime = (time) => {
    if (!time) return '0:00';
    const minutes = Math.floor(time / 60);
    const seconds = Math.floor(time % 60);
    return `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
  };

  // Дополнительные действия
  const dropdown_show = () => {
    showDropDownMenu = true;
  };

  const likeTrack = () => {
    // Логика для отметки "нравится"
  };

  // Подключение к WebSocket
const connectWebSocket = () => {
  socket = new WebSocket('wss://127.0.0.1:8000/app/YOUR_APP_KEY?protocol=7&client=js&version=7.0.3&flash=false');

  socket.onopen = () => {
    console.log('WebSocket подключен');
  };

  socket.onmessage = (event) => {
    const message = JSON.parse(event.data);
    handleWebSocketMessage(message);
  };

  socket.onclose = () => {
    console.log('WebSocket отключен, попытка переподключения через 5 секунд...');
    setTimeout(connectWebSocket, 5000);
  };

  socket.onerror = (error) => {
    console.error('Ошибка WebSocket:', error);
  };
};

// Отправка сообщения
const sendToWebSocket = (action, data = {}) => {
  if (socket && socket.readyState === WebSocket.OPEN) {
    const payload = { action, ...data };
    socket.send(JSON.stringify(payload));
  }
};

// Обработка входящих команд
const handleWebSocketMessage = (message) => {
  switch (message.action) {
    case 'play':
      handleSeek(message.time ?? 0);
      audioPlayer.value.play();
      isPlaying.value = true;
      store.play();
      break;

    case 'pause':
      audioPlayer.value.pause();
      isPlaying.value = false;
      store.pause();
      break;

    case 'seek':
      handleSeek(message.time);
      break;

    case 'nextTrack':
      handleNextTrack();
      break;

    case 'prevTrack':
      handlePrevTrack();
      break;

    default:
      console.warn('Неизвестное сообщение от WebSocket:', message);
  }
};


  onMounted(() => {
    audioPlayer.value.volume = volumeLevel.value
    if (audioElement.value) {
      store.initAudio(audioPlayer.value)
    }
    connectWebSocket(); // ← подключение
  })

  onUnmounted(() => {
    store.audioElement = null // Очищаем ссылку
  })

</script>

