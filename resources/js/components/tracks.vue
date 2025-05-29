<template>
    <h2 class="emptyPlaylist" v-if="tracks.length == 0">Упс. Тут пока что пусто</h2>
    <div class="trackLink"  
    v-for="(track, index) in tracks"
    :key="track.id"
    @click=playTrack(track.id)>
        <div class="track d-flex py-2 px-3" style="align-items: center;">
            <div class="leftContainer" style="display: flex; align-items: center;">
                <h4 class="track-number me-3">{{ index + 1 }}.</h4>
                <img class="albumCover" :src="'/storage/' + track.albumCover">
                <div class="trackInfo">
                    <Link :href="`/artist/${track.artistID}`"><span class="trackArtist">{{ track.artistName }}</span></Link>
                    <Link :href="`/album/${track.albumID}`"><span class="trackName">{{track.trackName}}</span></Link>
                </div>
            </div>
            <div class="rightContainer" style="display: flex; align-items: center; justify-content: center;">
                <h5 class="trackDuration">{{ formatDuration(track.duration) }}</h5>
                <MoreIcon class="icon"></MoreIcon>
                <div class="track_userInfo">
                    <img class="userPhoto" src="/storage/templates/userImage.svg">
                    <span class="userName">{{ track.userName }}</span>
                </div>
                
                
            </div>
            
        </div>
        <hr>
    </div>
</template>

<script setup>

    import { ref, onMounted } from 'vue'
    import { inject } from 'vue'
    import axios from 'axios'
    import { useAudioPlayerStore } from '@/stores/useAudioPlayerStore'
    import { Link } from '@inertiajs/inertia-vue3'

    import MoreIcon from '@/assets/icons/moreIcon.svg'

    const props = defineProps({
        playlistId: Object,
        artistId: Object
    })

    const playlistID = props.playlistId
    const artistID = props.artistId
    const store = useAudioPlayerStore()
    const tracks = ref([])
    const artists = ref([])
    const test = ref([])

    onMounted(async () => {
        if (!isNaN(playlistID)) {
            try {
                const response = await axios.get(`/api/playlist/${playlistID}/tracks`)
                tracks.value = response.data
                console.log(tracks)
            } catch (error) {
                console.error('Ошибка при загрузке треков плейлиста:', error)
            }
        }
        else if (!isNaN(artistID)) {
            try {
                const response = await axios.get(`/artist/${artistID}/tracks`)
                tracks.value = response.data
                console.log(tracks)
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
            albumPhoto: track.albumPhoto,
            audioSrc: `/storage/${track.path.replace('public/audio/', '')}`
        }
        
        console.log(trackData.audioSrc)

        await store.selectTrack()
        await store.play()
        // Загрузка трека в плеер
        const loaded = await store.setTrack(trackData)

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
        justify-content: center;
    }

</style>