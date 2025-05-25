// import { createPinia } from 'pinia'
// import { createApp } from 'vue'
// import audioplayerComponent from './components/audioplayer.vue' // основной компонент
// import tracks from './components/tracks.vue'
// import favorite_artists from './components/artists.vue'
// import playlistButtons from './components/playlistButtons.vue'
// import albums from './components/albums.vue'

// const pinia = createPinia()

// const audioplayer = createApp(audioplayerComponent)
// audioplayer.use(pinia)
// audioplayer.mount('#audioplayer')

// try {

//     const playlistTracks = createApp(tracks)
//     const elPlaylistTracks = document.getElementById('playlistTracks')
//     playlistTracks.use(pinia)
//     playlistTracks.provide('playlistID', elPlaylistTracks.dataset.playlist)
//     playlistTracks.mount('#playlistTracks')

// } catch (error) {
//     console.log(error)
// }

// try {

//     const playlistBTNs = createApp(playlistButtons)
//     const elPlaylistBTNs = document.getElementById('playlistButtons')
//     playlistBTNs.use(pinia)
//     playlistBTNs.provide('playlistID', elPlaylistBTNs.dataset.playlist)
//     playlistBTNs.mount('#playlistButtons')

// } catch (error) {
//     console.log(error)
// }
// try {

//     const favoriteArtists = createApp(favorite_artists)
//     const elFavoriteArtists = document.getElementById('artistsCollection')
//     favoriteArtists.use(pinia)
//     favoriteArtists.provide('userID', elFavoriteArtists.dataset.user)
//     favoriteArtists.mount('#artistsCollection')

// } catch (error) {
//     console.log(error)
// }
// try {

//     const artistTracks = createApp(tracks)
//     const elArtistTracks = document.getElementById('artistTracks')
//     artistTracks.use(pinia)
//     artistTracks.provide('artistID', elArtistTracks.dataset.artist)
//     artistTracks.mount('#artistTracks')

// } catch (error) {
//     console.log(error)
// }

// try { //Альбомы исполнителя
//     const artistAlbums = createApp(albums)
//     const elArtistAlbums = document.getElementById('artistAlbums')
//     artistAlbums.use(pinia)
//     artistAlbums.provide('artistID', elArtistAlbums.dataset.artist)
//     artistAlbums.mount('#artistAlbums')
// } catch (error) {
//     console.log(error)
// }

// // try {

// // } catch (error) {
// //     console.log(error)
// // }
// // try {

// // } catch (error) {
// //     console.log(error)
// // }

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import { createPinia } from 'pinia'
import Layout from './Layouts/AppLayout.vue'

// Создаем хранилище Pinia
const pinia = createPinia()

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    let page = pages[`./Pages/${name}.vue`]
    
    // Добавляем layout по умолчанию для всех страниц
    page.default.layout = page.default.layout || Layout
    
    return page
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(pinia) // Используем Pinia во всем приложении
      .mount(el)
  },
})