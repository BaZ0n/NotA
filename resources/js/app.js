import { createPinia } from 'pinia'
import { createApp } from 'vue'
import audioplayerComponent from './components/audioplayer.vue' // основной компонент
import playlist_tracks from './components/tracks.vue'
import favorite_artists from './components/artists.vue'
import playlistButtons from './components/playlistButtons.vue'

const pinia = createPinia()

const audioplayer = createApp(audioplayerComponent)
audioplayer.use(pinia)
audioplayer.mount('#audioplayer')

const playlistTracks = createApp(playlist_tracks)
const elPlaylistTracks = document.getElementById('playlistTracks')
playlistTracks.use(pinia)
playlistTracks.provide('playlistID', elPlaylistTracks.dataset.playlist)
playlistTracks.mount('#playlistTracks')

const playlistBTNs = createApp(playlistButtons)
const elPlaylistBTNs = document.getElementById('playlistButtons')
playlistBTNs.use(pinia)
playlistBTNs.provide('playlistID', elPlaylistBTNs.dataset.playlist)
playlistBTNs.mount('#playlistButtons')

const favoriteArtists = createApp(favorite_artists)
const elFavoriteArtists = document.getElementById('artistsCollection')
favoriteArtists.use(pinia)
favoriteArtists.provide('userID', elFavoriteArtists.dataset.user)
favoriteArtists.mount('#artistsCollection')