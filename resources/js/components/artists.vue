<template>
    
    <a href="#" class="artistLink"
    v-for="(artist) in artists">
        <div class="artist px-2 py-2">
            <img src="{{asset('images/artistsImages/4k.jpg')}}">
            <h4 class="text-center">{{artist.artistName}}</h4>
        </div>
    </a>

</template>

<script setup>

    import { ref, onMounted } from 'vue'
    import { inject } from 'vue'
    import axios from 'axios'

    const userID = inject('userID')
    const artists = ref([])
    console.log("Привет")

    onMounted(async () => {
        console.log("Начало")
        if (userID == null) {
            try {
                console.log("Зашёл")
                const response = await axios.get(`/api/collectionPage/${userID}/artists`)
                artists.value = response.data 
            } catch(error) {
                console.log("Бля")
                console.error('Ошибка при загрузке исполнителей.', error)
            }
        }
    })

</script>