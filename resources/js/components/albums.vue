<template>

    <div class="albumsContainer">

        <div class="album-el"
        v-for="(album) in albums">

            <img class="albumIMG" :src="'/storage/' + album.photo_path">
            <h4 class="albumName">{{album.albumName}}</h4>
        </div>

    </div>

</template>

<script setup>

    import { ref, onMounted } from 'vue'
    import { inject } from 'vue'
    import axios from 'axios'

    const props = defineProps({
        artistId: Object
    })

    const artistID = props.artistId
    const albums = ref([])

    onMounted(async () => {
        try {
            const response = await axios.get(
                `/artist/${artistID}/albums`
            )
            albums.value = response.data

        } catch(error) {
            console.log(error)
        }
        console.log(albums.value)
    })

</script>