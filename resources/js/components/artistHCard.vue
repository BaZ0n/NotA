<template>
  <Link :href="`/artist/${artist.id}`" class="artistLink">
    <div class="artist px-2 py-2">
      <img src="/storage/templates/userImage.svg" />
      <h4 class="text-center">{{ artist.artistName }}</h4>
    </div>
  </Link>
</template>

<script setup>
  import { Link } from '@inertiajs/inertia-vue3'
  const props = defineProps({
      artist: Object
  })
</script>
