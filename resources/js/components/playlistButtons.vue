<template>
    <div class="lightButtons">
        <button class="toolButton" id="playButton" @click="playPlaylist">
            <PlayIcon class="icon"></PlayIcon>
        </button>
        <button class="toolButton" id="likeButton" @click="addToFavorite" v-show="!isFavorite">
            <LikeIcon class="icon"></LikeIcon>
        </button>
        <button class="toolButton" id="likeButton" @click="deleteFromFavorite" v-show="isFavorite">
            <FavoriteIcon class="icon"></FavoriteIcon>
        </button>
        <div class="dropdown" @click.stop="toggleDropdown" v-if="isModer">
            <button class="toolButton" id="moreIcon">
                <MoreIcon class="icon"></MoreIcon>
            </button> 
            <transition name="fade">
                <div 
                    v-if="isOpen" 
                    class="dropdown__menu"
                    @click.stop>
                    <slot name="content">
                        <!-- Слот для кастомного содержимого -->
                        <ul>
                            <li class="dropdown__item" @click="addTrack">Добавить в плейлист</li>
                            <li class="dropdown__item" @click="deletePlaylist">Удалить плейлист</li>
                            <li class="dropdown__item" @click="addModer">Добавить модератора</li>
                        </ul>
                    </slot>
                </div>
            </transition>
        </div>
    </div>
    
    <button class="button-primary my-3 px-3 py-3" style="background-color: var(--placeholder)" id="uploadTrackBTN" v-show="isModer" @click="trackUploadShow">
        <h5 class="text my-0" style="color: white">Загрузить трек</h5>
    </button>
    
    <div class="addBG" v-show="moddersAdd" @click.self="closeWindow">
        <div class="addContainer" @click.stop> 
            <h2>Поиск пользователя</h2>
            <input type="text" class="searchInput" placeholder="Поиск..." v-model="searchQuery">
            <hr>
            <ul class="userList" style="padding-left: 0;">
                <li class="user_element" v-for="(user) in users" :key="user.id" @click="addToModerPlaylist(user.userID)">
                    <p class="userName">{{ user.userName }}</p>
                    <p class="addModers">+</p>
                </li>
            </ul>
        </div>
    </div>

    <div class="addBG" v-show="tracksAdd" @click.self="closeWindow">
        <div class="addContainer" @click.stop> 
            <h2>Поиск трека</h2>
            <input type="text" class="searchInput" placeholder="Поиск..." v-model="searchQueryTrack">
            <hr>
            <ul class="tracks_list" style="padding-left: 0; background-color: transparent; border: 0;">
                <li class="track_element" v-for="(track, index) in tracks" :key="track.id" @click="addTrackToPlaylist(track.id)">
                    <div class="track d-flex py-2 px-3" style="align-items: center;">
                        <div class="leftContainer" style="display: flex; align-items: center;">
                            <img class="albumCover" :src="'/storage/' + track.albumCover">
                            <div class="trackInfo">
                                <h6 class="trackArtist">{{ track.artistName }}</h6>
                                <h5 class="trackName">{{track.trackName}}</h5>
                            </div>
                        </div>
                        <h5 class="trackDuration">{{ formatDuration(track.duration) }}</h5>
                    </div> 
                </li>
            </ul>
        </div>
    </div>

</template>


<script setup>

    import { ref, onMounted, onUnmounted, watch } from 'vue'
    import { inject } from 'vue'
    import axios from 'axios'
    import { useAudioPlayerStore } from '@/stores/useAudioPlayerStore'

    import PlayIcon from '@/assets/icons/playPlaylistIcon.svg'
    import LikeIcon from '@/assets/icons/likeIcon.svg'
    import FavoriteIcon from '@/assets/icons/favoriteIcon.svg'
    import MoreIcon from '@/assets/icons/moreIcon.svg'

    const props = defineProps({
        playlistId: Object
    })

    const playlistID = props.playlistId
    const isFavorite = ref(true)
    const isOpen = ref(false)
    const moddersAdd = ref(false)
    const users = ref([])
    const searchQuery = ref('')
    const searchQueryTrack = ref('')
    const isModer = ref(false)
    const tracksAdd = ref(false)
    const tracks = ref([])
    const store = useAudioPlayerStore()

    const toggleDropdown = () => {
        isOpen.value = !isOpen.value;
    }

    // Закрытие модального окна
    const closeWindow = () => {
        moddersAdd.value = false;
        tracksAdd.value = false;
        searchQuery.value = '';
    };

    // Закрытие при клике вне меню
    const closeOnClickOutside = (event) => {
        if (!event.target.closest('.dropdown')) {
            isOpen.value = false
        }
        if (!event.target.closest('.addContainer')) {
            moddersAdd.value = false
            tracksAdd.value = false
        }
    }

    watch(searchQuery, async (newSearch) => {
        try {
            const response = await axios.get(
                `/users/get/${searchQuery.value}`
            )
            users.value = response.data.users
        }
        catch (error) {
            console.log(error)
        }
    })

    watch(searchQueryTrack, async(newSearch) => {
        try {
            const response = await axios.get(
                `/tracks/get/${searchQueryTrack.value}`
            )
            tracks.value = response.data.tracks
        } catch(error) {
            console.log(error)
        }
    })


    const addToModerPlaylist = async (userID) => {
        try {
            const response = await axios.put(
                `/playlist/${playlistID}/moders/${userID}`
            )
        }
        catch (error) {
            console.log('Ошибка, ', error)
        }
    }

    const addToFavorite = async () => {
        try {
            const response = await axios.put(
                `/playlist/${playlistID}/favorite`  
            )

            isFavorite.value = true
        }
        catch(error) {
            console.log(error)
            alert("Ошибка")
        }
    }

    const deleteFromFavorite = async () => {
        try {
            const response = await axios.delete(
                `/playlist/${playlistID}/deleteFavorite`  
            )
            if (response.data.isDeleted) {
                isFavorite.value = false
            }
        }
        catch(error) {
            console.log(error)
            alert("Ошибка")
        }
    }

    const addModer = async () => {
        isOpen.value = !isOpen.value
        if (!moddersAdd.value) {
            moddersAdd.value = !moddersAdd.value
            const response = await axios.get(
                '/users/get'
            )
            
            users.value = response.data.users
        }
        else {
            
            moddersAdd.value = !moddersAdd.value
        }
    }

    const addTrack = async () => {
        isOpen.value = !isOpen.value
        try {
            if (!tracksAdd.value) {
                tracksAdd.value = !tracksAdd.value
                const response = await axios.get(
                    '/tracks/get'
                )
                tracks.value = response.data.tracks
            }
            else {
                
                tracksAdd.value = !tracksAdd.value
            }
        } catch (error) {
            console.log(error)
        }
        
    }

    const addTrackToPlaylist = async(trackID) => {
        try {
            const response = await axios.put(
                `/playlist/${playlistID}/addTrack/${trackID}`
            )
        }
        catch(error) {
            console.log(error)
        }
    }

    const trackUploadShow = () => {
        store.trackUploadShow()
    }

    function formatDuration(seconds) {
        const mins = Math.floor(seconds / 60)
        const secs = Math.floor(seconds % 60).toString().padStart(2, '0')
        return `${mins}:${secs}`
    }

    
    

    onMounted(async () => {
        try {
            const response = await axios.get(`/playlist/${playlistID}/isFavoriteAndUserModer`)
            isFavorite.value = response.data.isFavorite
            isModer.value = response.data.isModer
        } catch (error) {
            console.error('Ошибка при проверке плейлиста:', error)
        }
        document.addEventListener('click', closeOnClickOutside);
        document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            isOpen.value = false;
            moddersAdd.value = false;
            tracksAdd.value = false;
        }
        });
    })
    
    onUnmounted(() => {
        document.removeEventListener('click', closeOnClickOutside);
    });
</script>
