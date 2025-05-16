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
        <div class="dropdown" @click.stop="toggleDropdown">
            <button class="toolButton" id="moreIcon" @click="dropdownMenu">
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
                            <li class="dropdown__item">Добавить в плейлист</li>
                            <li class="dropdown__item" @click="deletePlaylist">Удалить плейлист</li>
                            <li class="dropdown__item" @click="addModer" v-if="isModer">Добавить модератора</li>
                        </ul>
                    </slot>
                </div>
            </transition>
        </div>
    </div>
    
    <button class="button-primary my-3 px-3 py-3" style="background-color: var(--placeholder)" id="uploadTrackBTN" v-show="isModer">
        <h5 class="text my-0" style="color: white">Загрузить трек</h5>
    </button>
    
    <div class="addModerBG" v-show="moddersAdd" @click.self="closeModerModal">
        <div class="addModerContainer" @click.stop> 
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

</template>

<script setup>

    import { ref, onMounted, onUnmounted, watch } from 'vue'
    import { inject } from 'vue'
    import axios from 'axios'

    import PlayIcon from '@/assets/icons/playIcon.svg'
    import LikeIcon from '@/assets/icons/likeIcon.svg'
    import FavoriteIcon from '@/assets/icons/favoriteIcon.svg'
    import MoreIcon from '@/assets/icons/moreIcon.svg'
    

    const playlistID = inject('playlistID')
    const isFavorite = ref(true)
    const isOpen = ref(false)
    const moddersAdd = ref(false)
    const users = ref([])
    const searchQuery = ref('')
    const isModer = ref(false)

    const toggleDropdown = () => {
        isOpen.value = !isOpen.value;
    }

    // Закрытие модального окна
    const closeModerModal = () => {
        moddersAdd.value = false;
        searchQuery.value = '';
    };

    // Закрытие при клике вне меню
    const closeOnClickOutside = (event) => {
        if (!event.target.closest('.dropdown')) {
            isOpen.value = false
        }
        if (!event.target.closet('.addModerContainer')) {
            moddersAdd.value = false
        }
        
    }

    watch(searchQuery, async (newSearch) => {
        console.log(searchQuery.value)
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

    const addToModerPlaylist = async (userID) => {
        try {
            console.log(userID)
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
    

    onMounted(async () => {
        try {
            const response = await axios.get(`/playlist/${playlistID}/isFavoriteAndUserModer`)
            isFavorite.value = response.data.isFavorite
            isModer.value = response.data.isModer
            console.log(isModer.value)
        } catch (error) {
            console.error('Ошибка при проверке плейлиста:', error)
        }
        document.addEventListener('click', closeOnClickOutside);
        document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            isOpen.value = false;
            moddersAdd.value = false;
        }
        });
    })
    
    onUnmounted(() => {
        document.removeEventListener('click', closeOnClickOutside);
    });
</script>
