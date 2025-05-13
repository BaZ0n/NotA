// stores/useAudioPlayerStore.js
import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useAudioPlayerStore = defineStore('audioPlayer', () => {
  const currentTrackInfo = ref(null)
  const audioSrc = ref('')
  const currentIndex = ref(0)
  const isPlaying = ref(false)

  const setTrack = (track, index) => {
    currentTrackInfo.value = track
    audioSrc.value = track.audioSrc
    currentIndex.value = index
  }

  const play = () => { isPlaying.value = true }
  const pause = () => { isPlaying.value = false }

  return {
    currentTrackInfo,
    audioSrc,
    currentIndex,
    isPlaying,
    setTrack,
    play,
    pause
  }
})
