// stores/useAudioPlayerStore.js
import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useAudioPlayerStore = defineStore('audioPlayer', () => {
  const currentTrackInfo = ref(null)
  const audioSrc = ref('')
  const currentIndex = ref(0)
  const isPlaying = ref(false)
  const currentArtistName = ref(null)
  const currentPlaylistID = ref(0)
  const justSelected = ref(false)
  const trackUpload = ref(false)
  const active_user = ref(null)
  const isSynchronizedMode = ref(true)
  const currentAlbumPhoto = ref(null)

  function toggleSyncMode(mode) {
    isSynchronizedMode.value = mode
  }

  const setTrack = (track, index) => {
    currentTrackInfo.value = track
    audioSrc.value = track.audioSrc
    currentIndex.value = index
    currentArtistName.value = track.artistName
    currentAlbumPhoto.value = track.albumPhoto
    currentPlaylistID.value = track.playlistId
  }

  const setUser = (user) => {
    active_user.value = user
  }

  const play = () => { isPlaying.value = true }
  const pause = () => { isPlaying.value = false }
  const selectTrack = () => { justSelected.value = true }
  const selectedPlaying = () => { justSelected.value = false }
  const trackUploadShow = () => { trackUpload.value = true }
  const trackUploadHide = () => { trackUpload.value = false }

  return {
    currentTrackInfo,
    audioSrc,
    currentIndex,
    currentArtistName,
    currentPlaylistID,
    isPlaying,
    justSelected,
    trackUpload,
    active_user,
    isSynchronizedMode,
    currentAlbumPhoto,
    setTrack,
    play,
    pause,
    selectTrack,
    selectedPlaying,
    trackUploadShow,
    trackUploadHide,
    setUser,
    toggleSyncMode
  }
})