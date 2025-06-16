<template>
  <div class="artistBodyContainer">
    <div class="artistInfoCont">
      <img class="artistAvatar" src="/storage/templates/userImage.svg" alt="Artist Avatar" />
      <h4 class="artistName">{{ artist.artistName }}</h4>
      <!-- <button class="favoriteButton" id="likeButton" @click="addToFavorite">
          <LikeIcon class="icon"></LikeIcon>
      </button> -->
        <button class="toolButton" id="likeButton" @click="deleteFromFavorite">
            <FavoriteIcon class="icon"></FavoriteIcon>
        </button>
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
    import LikeIcon from '@/assets/icons/likeIcon.svg'
    import FavoriteIcon from '@/assets/icons/favoriteTrackIcon.svg'

    const props = defineProps({
        artist: Object
    })

    // Можно формировать ссылку на аватар динамически, если нужно
    // const artistImage = computed(() =>
    //     new URL('@/images/templates/userImage.svg', import.meta.url).href
    // )
</script>
