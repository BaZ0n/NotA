<template>
  <div class="artistBodyContainer">
    <div class="artistInfoCont">
      <img class="artistAvatar" src="/storage/templates/userImage.svg" alt="Artist Avatar" />
      <h4 class="artistName">{{ artist.artistName }}</h4>
    </div>

    <div class="tracksList">
      <TrackList :artistId="artist.id" />
    </div>

    <div class="albumList">
      <AlbumList :artistId="artist.id" />
    </div>
  </div>
</template>

<script setup>
    import { computed } from 'vue'

    // Подключаем дочерние компоненты
    import TrackList from '@/components/tracks.vue'
    import AlbumList from '@/components/albums.vue'

    const props = defineProps({
        artist: Object
    })

    // Можно формировать ссылку на аватар динамически, если нужно
    // const artistImage = computed(() =>
    //     new URL('@/images/templates/userImage.svg', import.meta.url).href
    // )
</script>
