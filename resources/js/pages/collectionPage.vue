<template>
    <div>
  <div class="container" style="padding: 0px">
    <div class="container d-flex p-0" id="playlistTrackCont" style="height: 60vh; width: 100%;">
      <!-- Блок с треками -->
      <div class="favoriteTracksCont mx-2 my-3 px-1" style="overflow-y: auto;">
        <div class="headCont">
          <h3>Треки</h3>
          <button class="showAllTrackBTN mx-2" @click="showAllTracks">
            <h6 class="text">Показать все...</h6>
          </button>
        </div>

        <div class="playlistTracks mx-3 py-3 px-3">
          <Tracks :playlistId="1"/>
        </div>
      </div>

      <!-- Блок с плейлистами -->
      <div class="playlistContainer mx-2 my-3 px-1" style="overflow-y: auto; width: 15vw;">
        <div class="headCont d-flex">
          <h3>Плейлисты</h3>
          <button class="showAllPlaylistBTN mx-2" @click="showAllPlaylists">
            <h6>Все...</h6>
          </button>
        </div>

        <ol class="playlistUser d-flex flex-column align-items-center" style="list-style-type: none; padding: 0;">
          <li class="playlistCollection py-2 px-1">
            <button class="createPlaylistBTN" @click="createPlaylist">
              <h1 style="color: white">+</h1>
            </button>
            <h6 class="text-center mt-2">Создать плейлист</h6>
          </li>

          <li v-for="(playlist, index) in playlists.slice(0, 5)" :key="playlist.id" class="playlist-item">
            <Link :href="`/playlist/${playlist.id}`" class="playlistLink" style="margin:0">
              <div class="playlistCollection py-2 px-1">
                <img class="playlistImage" src="/storage/templates/playlistImage.svg" />
                <h5 class="text-center mt-2">{{ playlist.playlistName }}</h5>
              </div>
            </Link>
          </li>
        </ol>
      </div>
    </div>

    <!-- Заглушка под будущие данные об артистах
    <div class="container mx-0">
      <h3>Исполнители</h3>
      <div class="artistsCollection mx-3 d-flex" id="artistsCollection">
        <ArtistsCard
            v-for="artist in artists"
            :key="artist.id"
            :artist="artist"
        />
        <p v-if="!artists.length">Здесь будет список исполнителей</p>
      </div>
    </div> -->
  </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'

import Tracks from '@/components/tracks.vue'
import ArtistsCard from '@/components/artistHCard.vue' // Исполнители

const props = defineProps({
  playlists: Array,
  tracks: Array
})

const playlists = props.playlists
const tracks = props.tracks

const showAllTracks = () => {
  alert('Показать все треки — реализация позже');
}

const showAllPlaylists = () => {
  alert('Показать все плейлисты — реализация позже');
}

const createPlaylist = async () => {
  try {
    const response = await fetch('/playlist', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json',
      },
      body: JSON.stringify({ name: 'Новый плейлист' }),
      credentials: 'include',
    })

    if (!response.ok) throw new Error('Ошибка сервера')

    const data = await response.json()
    console.log(data)
    if (data.success) {
      Inertia.visit(data.redirect)
    }
  } catch (e) {
    alert(`Ошибка: ${e.message}`)
    console.log(e.message)
  }
}
</script>
