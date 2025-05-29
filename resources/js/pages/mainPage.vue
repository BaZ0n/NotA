<template>
    <h2 class="text mt-5 mb-2 mx-4">Плейлисты пользователей</h2>
    <div class="playlistsCollection mx-3 d-flex">
      <PlaylistCard
        v-for="(playlist, i) in playlists.slice(0, 10)"
        :key="'suggested-' + playlist.id"
        :playlist="playlist"
      />
    </div>

    <h2 class="text mt-5 mb-2 mx-4">Ваши плейлисты</h2>
    <div class="playlistsCollection mx-3 d-flex">
      <PlaylistCard
        v-for="(playlist, i) in user_playlists.slice(0, 10)"
        :key="'user-' + playlist.id"
        :playlist="playlist"
        :showAuthor="false"
      />
    </div>

    <h3 class="text mt-5 mb-2 mx-4">Исполнители</h3>
    <div class="artistsCollection mx-3 d-flex">
      <ArtistCard
        v-for="(artist, i) in artists.slice(0, 10)"
        :key="artist.id"
        :artist="artist"
      />
    </div>
</template>

<script setup>
    import { defineProps } from 'vue'
    import PlaylistCard from '@/components/playlistHCard.vue'
    import ArtistCard from '@/components/artistHCard.vue'

    const props = defineProps({
      playlists: Array,
      user_playlists: Array,
      artists: Array,
    })


</script>
