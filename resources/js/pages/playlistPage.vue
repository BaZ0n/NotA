<template>
  <div class="container playlistContainerTracks p-4 d-flex mt-5 mb-2">
    <div class="playlistInfo mx-3"> 
      <img class="playlistImage" src="/storage/templates/playlistImage.svg" alt="playlist image" />

      <h3
        class="text-center my-2 editable"
        contenteditable="true"
        @blur="updatePlaylistName"
        ref="playlistTitle"
        :data-original="playlist.playlistName"
      >
        {{ playlist.playlistName }}
      </h3>

      <h4 class="authorName text-center mb-3" style="color: var(--placeholder)">
        {{ playlist.userName }}
      </h4>

      <div class="buttonsCont mt-3" style="flex-direction: column;">
        <PlaylistButtons :playlistId="playlist.id" />
      </div>
    </div>

    <div class="tracksList mx-3 py-3 px-3">
      <Tracks :playlistId="playlist.id" />
    </div>

    <div v-show="showUpload" class="uploadBackground container-fluid" id="uploadCont">
      <div class="uploadTrackContainer">
        <h4 class="text-center mb-5" style="color: white">Загрузка трека</h4>
        <form @submit.prevent="submitTrack" enctype="multipart/form-data" method="post">
          <input type="text" v-model="trackName" placeholder="Название" class="form-control fs-5 my-2" />
          <input type="text" v-model="trackArtist" placeholder="Исполнитель" class="form-control fs-5 my-2" />
          <input type="text" v-model="trackAlbum" placeholder="Альбом" class="form-control fs-5 my-2" />
          <label class="input-file form-group">
            <input type="file" @change="handleFileChange" accept="audio/*" />
            <span class="input-file-btn px-3 py-2">Выберите файл</span>          
            <span class="input-file-text">{{ fileName }}</span>
          </label>
          <div class="form-group my-5 d-flex justify-content-center">
            <button class="button-primary py-2 px-4" type="submit">
              <h3>Загрузить</h3>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
  import axios from 'axios'
  import { ref } from 'vue'
  import { Inertia } from '@inertiajs/inertia-vue3'
  import { useForm } from '@inertiajs/inertia-vue3'
  import { watch } from 'vue'
  import { useAudioPlayerStore } from '@/stores/useAudioPlayerStore'

  import Tracks from '@/components/tracks.vue' // Треки
  import PlaylistButtons from '@/components/playlistButtons.vue' // путь  

  const props = defineProps({
    playlist: Object,
  })

  const playlist = props.playlist

  const showUpload = ref(false)

  const store = useAudioPlayerStore()
  const trackName = ref('')
  const trackArtist = ref('')
  const trackAlbum = ref('')
  const trackFile = ref(null)
  const fileName = ref('Не выбран файл')

  
  const playlistTitle = ref(null)

  watch(() => store.trackUpload, async (newValue) => {
    if (!newValue) return;
    showUpload.value = store.trackUpload
  });

  async function updatePlaylistName() {
    const newName = playlistTitle.value.innerText.trim()
    const original = playlistTitle.value.dataset.original

    if (newName && newName !== original) {
      try {
        const response = await axios.put(`/playlist/update/${playlist.id}`, {
          playlistName: newName
        })

        playlistTitle.value.dataset.original = response.data.playlistName
      } catch (error) {
        console.log(error.message)
        playlistTitle.value.innerText = original
        if (error.response?.data?.errors?.playlistName) {
          alert('Ошибка: ' + error.response.data.errors.playlistName[0])
        } else {
          alert('Не удалось обновить название.')
        }
      }
    }
  }

  const handleFileChange = (e) => {
    trackFile.value = e.target.files[0]
    fileName.value = trackFile.value ? trackFile.value.name : 'Не выбран файл'
  }

  const submitTrack = async () => {
    if (!trackFile.value) {
      alert('Пожалуйста, выберите аудиофайл')
      return
    }

    const formData = new FormData()
    formData.append('trackName', trackName.value)
    formData.append('trackArtist', trackArtist.value)
    formData.append('trackAlbum', trackAlbum.value)
    formData.append('file', trackFile.value)
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content)

    try {
      await fetch(`/playlist/${playlist.id}/upload-audio`, {
        method: 'POST',
        body: formData,
        credentials: 'include',
      })
      alert('Трек успешно загружен')
      showUpload.value = false
      store.trackUploadHide()
    } catch (error) {
      alert('Ошибка при загрузке трека')
      console.log(error.message)
    }
  }
</script>

<style>

  .playlistContainerTracks {
    background-color: var(--sliderBG);
  }

</style>