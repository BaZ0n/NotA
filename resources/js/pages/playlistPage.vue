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

    <div v-show="showUpload" class="uploadBackground container-fluid" id="uploadCont" @click.self="closeWindow">
      <div class="uploadTrackContainer" @click.stop>
        <h4 class="text-center mb-5" style="color: white">Загрузка трека</h4>
        <form @submit.prevent="submitTrack" enctype="multipart/form-data" method="post">
          <input type="text" v-model="trackName" placeholder="Название" class="form-control fs-5 my-3" />
          <input type="text" v-model="trackArtist" placeholder="Исполнитель" class="form-control fs-5 my-3" />
          <input type="text" v-model="trackAlbum" placeholder="Альбом" class="form-control fs-5 my-3" />
          <label class="input-file form-group">
            <input type="file" @change="handleFileChange" accept="audio/*" multiple/>
            <span class="input-file-btn px-3 py-2">Выберите файл</span>          
            <span class="input-file-text">{{ fileName }}</span>
          </label>
          <div v-if="isUploading" class="progress my-3">
          <div class="progress-bar" role="progressbar" 
               :style="{ width: uploadProgress + '%' }" 
               :aria-valuenow="uploadProgress" 
               aria-valuemin="0" 
               aria-valuemax="100">
            {{ uploadProgress }}%
          </div>
        </div>
          <div class="form-group my-5 d-flex justify-content-center">
            <button class="button-primary py-2 px-4" type="submit">
              <h3>{{ isUploading ? 'Загрузка...' : 'Загрузить' }}</h3>
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
  import { Inertia, router } from '@inertiajs/inertia-vue3'
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
  const trackFiles = ref(null)
  const fileName = ref('Не выбран файл')
  const isUploading = ref(false)
  const uploadProgress = ref(0)

  
  const playlistTitle = ref(null)

  // Закрытие модального окна
  const closeWindow = () => {
      showUpload.value = false;
      store.trackUploadHide();
  };

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

  // const handleFileChange = (e) => {
  //   trackFiles.value = e.target.files
  //   fileName.value = trackFiles.value.length > 1 ? "Загружено " + trackFiles.value.length + " файлов": trackFiles.value[0].name
  // }

  // const submitTrack = async () => {
  //   if (!trackFiles.value[0]) {
  //     alert('Пожалуйста, выберите аудиофайл')
  //     return
  //   }

    
  //   const formData = new FormData()
  //   const csrfToken = document.querySelector('meta[name="csrf-token"]').content

  //   console.log(trackFiles)
  //   for (const trackFile of trackFiles.value) {
  //     console.log(trackFile)
  //     if (trackFiles.value.length > 1) {
  //       formData.append('trackName', '')
  //       formData.append('trackArtist', '')
  //       formData.append('trackAlbum', '')
  //       formData.append('file', trackFile.value)
  //       formData.append('_token', csrfToken);
  //     }
  //     else {
  //       formData.append('trackName', trackName.value)
  //       formData.append('trackArtist', trackArtist.value)
  //       formData.append('trackAlbum', trackAlbum.value)
  //       formData.append('file', trackFile.value)
  //       formData.append('_token', csrfToken);
  //     }
  //     showUpload.value = false
  //     store.trackUploadHide()
  //     try {
  //       await fetch(`/playlist/${playlist.id}/upload-audio`, {
  //         method: 'POST',
  //         body: formData,
  //         credentials: 'include',
  //       })
  //       console.log('Трек успешно загружен')

  //     } catch (error) {
  //       alert('Ошибка при загрузке трека')
  //       console.log(error.message)
  //     }
  //   }
    
  // }

  watch(() => store.trackUpload, (newValue) => {
    showUpload.value = newValue
  })

  const handleFileChange = (e) => {
    trackFiles.value = Array.from(e.target.files)
    fileName.value = trackFiles.value.length > 1 
      ? `Выбрано ${trackFiles.value.length} файлов` 
      : trackFiles.value[0]?.name || 'Не выбран файл'
  }

  const uploadSingleTrack = async (file, index) => {
    const formData = new FormData()
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content

    // Используем введенные данные или имя файла
    formData.append('trackName', trackName.value || file.name.replace(/\.[^/.]+$/, ""))
    formData.append('trackArtist', trackArtist.value)
    formData.append('trackAlbum', trackAlbum.value)
    formData.append('file', file)
    formData.append('_token', csrfToken)

    try {
      const response = await axios.post(`/playlist/${playlist.id}/upload-audio`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        },
        onUploadProgress: (progressEvent) => {
          const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total)
          uploadProgress.value = Math.round((index + percentCompleted / 100) / trackFiles.value.length * 100)
        }
      })
      return response.data
    } catch (error) {
      console.error('Ошибка загрузки:', error)
      throw error
    }
  }

  const submitTrack = async () => {
    if (trackFiles.value.length === 0) {
      alert('Пожалуйста, выберите аудиофайл')
      return
    }

    isUploading.value = true
    uploadProgress.value = 0

    try {
      const results = []
      for (let i = 0; i < trackFiles.value.length; i++) {
        const result = await uploadSingleTrack(trackFiles.value[i], i)
        results.push(result)
      }

      console.log('Все треки загружены:', results)
      alert(`Успешно загружено ${trackFiles.value.length} треков`)
      
      // Сброс формы
      trackFiles.value = []
      trackName.value = ''
      trackArtist.value = ''
      trackAlbum.value = ''
      fileName.value = 'Не выбран файл'
      showUpload.value = false
      store.trackUploadHide()
      
      // Обновление списка треков
      Inertia.visit(route('playlist.show', { id: playlist.id }), {
        preserveState: true,
        only: ['tracks'] // Укажите какие данные нужно обновить
      })
      Inertia.visit(route('playlist.show', ))
    } catch (error) {
      alert(`Ошибка при загрузке: ${error.message}`)
    } finally {
      isUploading.value = false
    }
  }
</script>

<style>

  .playlistContainerTracks {
    background-color: var(--sliderBG);
  }

</style>