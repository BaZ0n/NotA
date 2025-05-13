<template>
<div id="audio-player-app" class="container mt-5">
  <div class="container" id="player-container">
    <div class="track-info">
        <img src="{{asset('images/playlistImages/playlistImgTest.png')}}">
        <div class="track-text-info">
            <h5 style="color:var(--placeholder);">Исполнитель</h5>
            <h4>Название</h4>
        </div>
    </div>
    <div class="multimedia-btn">
      <audio ref="audioPlayer" :src="store.audioSrc"></audio>
  
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

    <div class="additionalBtn">
      <button class="multBTN" @click="likeTrack">
        <LikeIcon class="icons_additional"></LikeIcon>
      </button>
      
      <button class="multBTN" @click="queue">
        <QueueIcon class="icons_additional"></QueueIcon>
      </button>

      <button class="multBTN" @click="dropdown_show">
        <MoreIcon class="icons_additional"></MoreIcon>
      </button>
      <div class="dropdown-menu" v-if="isOpen">
        <ul class="menu" id="list">
          <li class="menu-item">
            <a href="#" class="menu-link">Добавить в плейлист</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
  </template>

<script setup>
  import axios from 'axios'
  import { ref, onMounted } from 'vue'
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

  // Плейлист
  const tracks = []

  const currentIndex = ref(0)
  const isPlaying = ref(false)
  const store = useAudioPlayerStore()
  const audioPlayer = ref(null)
  const moreClicked = ref(false)

  watch(() => store.audioSrc, (newSrc) => {
    if (audioPlayer.value) {
      audioPlayer.value.load()
      if (store.isPlaying) {
        audioPlayer.value.play()
      }
    }
  })

  const play = () => {
    if (audioPlayer.value) {
      audioPlayer.value.play();
      isPlaying.value = true;
    }
  }

  const pause = () => {
    if (audioPlayer.value) {
      audioPlayer.value.pause();
      isPlaying.value = false;
    }
  }

  const nextTrack = () => {
    if (tracks.value.length) {
      currentIndex.value = (currentIndex.value + 1) % tracks.value.length;
      restartPlayback();
    }
  }

  const prevTrack = () => {
    if (tracks.value.length) {
      currentIndex.value = (currentIndex.value - 1 + tracks.value.length) % tracks.value.length;
      restartPlayback();
    }
  }

  const restartPlayback = () => {
    if (audioPlayer.value) {
      audioPlayer.value.pause();
      audioPlayer.value.load();
      if (isPlaying.value) {
        audioPlayer.value.play();
      }
    }
  };

  // Дополнительные действия
  const dropdown_show = () => {
    isOpen.value = !isOpen.value;
  };

  const likeTrack = () => {
    // Логика для отметки "нравится"
  };

  const queue = () => {
    // Логика для добавления в очередь
  };

  // Загрузка треков при монтировании компонента
  onMounted(() => {
    axios.get('/api/tracks')
      .then(response => {
        tracks.value = response.data.map(track => ({
          ...track,
          trackPath: `/storage/${track.trackPath}`,
          albumImage: `/storage/${track.albumImage}`
        }));
      })
      .catch(error => {
        console.error('Ошибка загрузки треков:', error);
      });

    if (audioPlayer.value) {
      audioPlayer.value.addEventListener('ended', nextTrack);
    }
  });
</script>