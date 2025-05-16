// stores/useAudioPlayerStore.js
import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useAudioPlayerStore = defineStore('audioPlayer', () => {
  const currentTrackInfo = ref(null)
  const audioSrc = ref('')
  const currentIndex = ref(0)
  const isPlaying = ref(false)
  const currentArtistName = ref(null)

  const setTrack = (track, index) => {
    currentTrackInfo.value = track
    console.log(currentTrackInfo.value)
    audioSrc.value = track.audioSrc
    console.log(track.audioSrc)
    currentIndex.value = index
    console.log(currentIndex.value)
    currentArtistName.value = track.artistName
    console.log(currentArtistName.value)
  }

  const play = () => { isPlaying.value = true }
  const pause = () => { isPlaying.value = false }

  return {
    currentTrackInfo,
    audioSrc,
    currentIndex,
    currentArtistName,
    isPlaying,
    setTrack,
    play,
    pause
  }
})

// import { defineStore } from 'pinia'
// import { ref } from 'vue'

// export const useAudioPlayerStore = defineStore('audioPlayer', () => {
//   const audioElement = ref(null)
//   const currentTrack = ref(null)
//   const isPlaying = ref(false)
//   const isLoading = ref(false)
//   const error = ref(null)

//   const initAudio = (element) => {
//     audioElement.value = element
//     setupAudioListeners()
//   }

//   const setupAudioListeners = () => {
//     if (!audioElement.value) return
    
//     audioElement.value.addEventListener('play', () => isPlaying.value = true)
//     audioElement.value.addEventListener('pause', () => isPlaying.value = false)
//     audioElement.value.addEventListener('error', (e) => {
//       error.value = `Audio error: ${e.target.error.message}`
//       isPlaying.value = false
//     })
//   }

//   const loadTrack = async (trackData) => {
//     if (!audioElement.value) {
//       error.value = 'Аудиоэлемент не инициализирован'
//       console.log(error.message)
//       return false
//     }
//     try {
//       isLoading.value = true
//       error.value = null
      
//       // Пауза и сброс текущего трека
//       audioElement.value.pause()
//       audioElement.value.currentTime = 0
      
//       // Установка нового источника
//       audioElement.value.src = trackData.audioSrc
//       console.log("privet1")

//       // Ожидание загрузки метаданных
//       await new Promise((resolve, reject) => {
//         if (!audioElement.value) {
//           reject(new Error('Аудиоэлемент пропал во время загрузки'))
//           return
//         }

//         audioElement.value.onloadedmetadata = resolve
//         audioElement.value.onerror = () => {
//           reject(new Error('Ошибка загрузки аудио'))
//         }
//       })
      
//       currentTrack.value = trackData
//       console.log("privet3")

//       return true
//     } catch (err) {
//       console.log(err.message)
//       error.value = `Failed to load track: ${err.message}`
//       return false
//     } finally {
//       isLoading.value = false
//     }
//   }

//   const play = async () => {
//     if (!audioElement.value || !currentTrack.value) return
    
//     try {
//       await audioElement.value.play()
//     } catch (err) {
//       error.value = `Playback failed: ${err.message}`
//       isPlaying.value = false
//     }
//   }

//   const pause = () => {
//     audioElement.value?.pause()
//   }

//   return {
//     audioElement,
//     currentTrack,
//     isPlaying,
//     isLoading,
//     error,
//     initAudio,
//     loadTrack,
//     play,
//     pause
//   }
// })