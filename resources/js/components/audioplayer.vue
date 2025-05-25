<template>
<div id="audio-player-app" class="container mt-5">
  <div class="container" id="player-container">
    <div class="row align-items-center">
      <div class="col track-info">
        <img class="playlist_image" :src="'/storage/templates/playlistImage.svg'">  
        <div class="track-text-info">
            <h5 style="color:var(--placeholder);"> {{ store?.currentArtistName || 'Исполнитель'}}</h5>
            <h4>{{store.currentTrackInfo?.trackName || 'Название'}}</h4>
        </div>
      </div>
      <div class="col multimedia-btn">
        <audio ref="audioPlayer" 
          :src="store.audioSrc" 
          @timeupdate="updateProgress" 
          @ended="handleTrackEnd"></audio>
    
        <div class="multimediaButtonsContainer">
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
        </div>

        <div class="progress-container">
          <span class="time-current">{{ formatTime(currentTime) }}</span>
          <input 
            type="range" 
            class="progress-bar" 
            min="0" 
            :max="duration" 
            :value="currentTime"
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

        <button class="multBTN" @click="dropdown_show">
          <MoreIcon class="icons_additional"></MoreIcon>
        </button>

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
  import { watch } from 'vue'

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

  watch(() => store.audioSrc, async (newSrc) => {
    if (!newSrc || !audioPlayer.value) return;
    playlistID.value = store.currentPlaylistID 
    trackID.value = store.trackID;
    
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

    try {
      const response = await axios.get(`/playlist/${playlistID.value}/tracks`)
      tracks.value = response.data.tracks
      updateNextUpTracks()
    } catch (error) {
      console.error("Ошибка загрузки треков:", error)
    }

    // Начинаем загрузку
    audioPlayer.value.load();
  }, { immediate: true });

  // Обновление треков "Далее в плейлисте"
  const updateNextUpTracks = () => {
    if (!tracks.value || !store.currentTrackInfo) return;
    const currentTrackIndex = tracks.value.findIndex(
      t => t.id === store.currentTrackInfo.id
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
    console.log(queueTracks.value)
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

  const playNextUp = (index) => {
    if (index >= nextUpTracks.value.length) return;
    
    const track = nextUpTracks.value[index];
    // store.setTrack(track);
    updateNextUpTracks();
    play();
  };

  // Обработка окончания трека
  const handleTrackEnd = () => {
    if (queueTracks.value.length > 0) {
        // Воспроизводим следующий трек из очереди
        const nextTrack = queueTracks.value[0];
        store.setTrack(nextTrack);
        queueTracks.value.shift();
        play();
    } else if (nextUpTracks.value.length > 0) {
        // Воспроизводим следующий трек из плейлиста
        const nextTrack = nextUpTracks.value[0];
        store.setTrack(nextTrack);
        updateNextUpTracks();
        play();
    } else {
        // Больше треков нет
        isPlaying.value = false;
        store.pause();
    }
  };


  // Управление воспроизведением
const play = () => {
  if (audioPlayer.value) {
    audioPlayer.value.play();
    isPlaying.value = true;
    store.play();
  }
};

const pause = () => {
  if (audioPlayer.value) {
    audioPlayer.value.pause();
    isPlaying.value = false;
    store.pause();
  }
};

const nextTrack = () => {
  if (queueTracks.value.length > 0) {
    // Берем следующий трек из очереди
    const nextTrack = queueTracks.value[0];
    const trackData = {
      ...nextTrack,
      audioSrc: `/storage/${nextTrack.path.replace('public/audio/', '')}` 
    }
    store.setTrack(trackData);
    queueTracks.value.shift();
  } else if (nextUpTracks.value.length > 0) {
    // Берем следующий трек из плейлиста
    const nextTrack = nextUpTracks.value[0];
    const trackData = {
      ...nextTrack,
      audioSrc: `/storage/${nextTrack.path.replace('public/audio/', '')}` 
    }
    store.setTrack(trackData);
    updateNextUpTracks();
  } else if (tracks.value.length) {
    // Переходим к следующему треку в плейлисте
    currentIndex.value = (currentIndex.value + 1) % tracks.value.length;
    const nextTrack = tracks.value[currentIndex.value]
    const trackData = {
      ...nextTrack,
      audioSrc: `/storage/${nextTrack.path.replace('public/audio/', '')}` 
    }
    store.setTrack(trackData);
    updateNextUpTracks();
  }
  
  play();
};

const prevTrack = () => {
  if (audioPlayer.value.currentTime > 3) {
    // Если трек играет больше 3 секунд, перезапускаем его
    audioPlayer.value.currentTime = 0;
  } else if (tracks.value.length) {
    // Переходим к предыдущему треку
    currentIndex.value = (currentIndex.value - 1 + tracks.value.length) % tracks.value.length;
    store.setTrack(tracks.value[currentIndex.value]);
    updateNextUpTracks();
    play();
  }
};

  const setVolume = () => {
    if (audioPlayer.value) {
      audioPlayer.value.volume = volumeLevel.value
      // Если включен mute, отключаем его при изменении громкости
      if (isMuted.value) {
        isMuted.value = false
      }
    }
  }

  const toggleMute = () => {
    if (audioPlayer.value) {
      isMuted.value = !isMuted.value
      audioPlayer.value.muted = isMuted.value
    }
  }

  // const restartPlayback = () => {
  //   if (audioPlayer.value) {
  //     audioPlayer.value.pause();
  //     audioPlayer.value.load();
  //     if (isPlaying.value) {
  //       audioPlayer.value.play();
  //     }
  //   }
  // };

  // Прогресс бар функции
  const updateProgress = () => {
    if (audioPlayer.value) {
      currentTime.value = audioPlayer.value.currentTime;
      if (!duration.value) {
        duration.value = audioPlayer.value.duration;
      }
    }
  };

  const seek = (e) => {
    const seekTime = parseFloat(e.target.value);
    if (audioPlayer.value && !isNaN(seekTime)) {
      audioPlayer.value.currentTime = seekTime
      currentTime.value = seekTime; // если currentTime — ref
    }
  };

  const formatTime = (time) => {
    if (!time) return '0:00';
    const minutes = Math.floor(time / 60);
    const seconds = Math.floor(time % 60);
    return `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
  };

  // Дополнительные действия
  const dropdown_show = () => {
    isOpen.value = !isOpen.value;
  };

  const likeTrack = () => {
    // Логика для отметки "нравится"
  };


  onMounted(() => {
    audioPlayer.value.volume = volumeLevel.value
      if (audioElement.value) {
        store.initAudio(audioPlayer.value)
      }
  })

  onUnmounted(() => {
    store.audioElement = null // Очищаем ссылку
  })

</script>

