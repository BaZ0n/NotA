<template>
  <Link :href="`/playlist/${playlist.id}`" class="playlistLink">
    <div class="playlist px-2 py-2">
      <img class="playlistImage" :src="'storage/' + playlist.photo_path" />
      <h4>{{ playlist.playlistName }}</h4>
      <h6 v-if="showAuthor">{{ playlist.userName }}</h6>
    </div>
  </Link>
</template>

<script setup>
  import { Link } from '@inertiajs/inertia-vue3'

  const props = defineProps({
    playlist: Object,
    showAuthor: {
      type: Boolean,
      default: true
    }
  })
</script>
