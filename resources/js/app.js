import { createPinia } from 'pinia'
import { createApp } from 'vue'
import audioplayerComponent from './components/audioplayer.vue' // основной компонент
import playlist_tracks from './components/tracks.vue'

const pinia = createPinia()

const audioplayer = createApp(audioplayerComponent)
audioplayer.use(pinia)
audioplayer.mount('#audioplayer')

const playlistTracks = createApp(playlist_tracks)
const el = document.getElementById('playlistTracks')
playlistTracks.use(pinia)
playlistTracks.provide('playlistID', el.dataset.playlist)
playlistTracks.mount('#playlistTracks')