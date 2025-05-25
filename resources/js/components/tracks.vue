<template>
    <h2 class="emptyPlaylist" v-if="tracks.length == 0">Упс. Плейлист пока что пустой</h2>
    <div class="trackLink"  
    v-for="(track, index) in tracks"
    :key="track.id"
    @click=playTrack(track.id)>
        <div class="track d-flex py-2 px-3" style="align-items: center;">
            <div class="leftContainer" style="display: flex; align-items: center;">
                <h4 class="track-number me-3">{{ index + 1 }}.</h4>
                <div class="trackInfo">
                    <a href="#"><h6 class="trackArtist">{{ track.artistName }} {{ track.albumName }}</h6></a>
                    <a href="#"><h5 class="trackName">{{track.trackName}}</h5></a>
                </div>
            </div>
            <h5 class="trackDuration">{{ formatDuration(track.duration) }}</h5>
        </div>
    </div>
</template>

<script setup>

    import { ref, onMounted } from 'vue'
    import { inject } from 'vue'
    import axios from 'axios'
    import { useAudioPlayerStore } from '@/stores/useAudioPlayerStore'
    
    const store = useAudioPlayerStore()
    const playlistID = inject('playlistID')
    const artistID = inject('artistID')
    const tracks = ref([])
    const artists = ref([])
    const test = ref([])

    onMounted(async () => {
        if (!isNaN(playlistID)) {
            try {
                const response = await axios.get(`/api/playlistPage/${playlistID}/tracks`)
                tracks.value = response.data
            } catch (error) {
                console.error('Ошибка при загрузке треков плейлиста:', error)
            }
        }
        else if (!isNaN(artistID)) {
            try {
                const response = await axios.get(`/artist/${artistID}/tracks`)
                tracks.value = response.data
            } catch (error) {
                console.error('Ошибка при загрузке треков плейлиста:', error)
            }
        }
        
    })

    function formatDuration(seconds) {
        const mins = Math.floor(seconds / 60)
        const secs = Math.floor(seconds % 60).toString().padStart(2, '0')
        return `${mins}:${secs}`
    }

    const playTrack = async (trackID, index) => {
    try {
        const res = await axios.get('/api/play-audio', {
            params: { trackID },
        })

        const track = res.data.track
        const author = res.data.author
        
        const trackData = {
            ...track,
            artistName: author?.artistName,
            playlistId: playlistID,
            audioSrc: `/storage/${track.path.replace('public/audio/', '')}`
        }
        
        // Загрузка трека в плеер
        const loaded = await store.setTrack(trackData)
        await store.play()

        // console.log(loaded)
        // Воспроизведение только если загрузка успешна
        // if (loaded) {
        //     await store.play()
        // }
    } catch (err) {
        console.error('Ошибка при воспроизведении:', err)
        store.error = `Ошибка: ${err.message}`
    }
    }

</script>

<style>

    .trackLink {
        cursor: pointer;
    }

    .emptyPlaylist {
        height: 100%;
        width: 100%;
        align-items: center;
        display: flex;
        text-align: center;
    }

</style>