<template>
  <div style="font-family: Vollkorn">
    <button class="sidebarBTN" @click="toggleSidebar">
      <SidebarIcon class="icon"></SidebarIcon>
    </button>

    <div
      class="sidebar"
      :class="{
        hidden: isHiding,
        'd-none': isHidden,
      }"
      @transitionend="onTransitionEnd"
    >
      <!-- Контент сайдбара -->
      <Link href="/user" class="user" style="margin-bottom: 30px;">
        <img class="userImg" src="/storage/templates/userImage.svg" alt="Профиль" />
        <span class="userName">{{ userStore.activeUser?.name }}</span>
      </Link>

      <div style="flex-grow: 1;"></div>

      <Link href="/main" class="sidebar-item">
        <MainPageIcon class="icon"></MainPageIcon>
        <span>Главная</span>
      </Link>

      <Link href="/collection" class="sidebar-item">
        <CollectionIcon class="icon"></CollectionIcon>
        <span>Коллекция</span>
      </Link>

      <Link href="#" class="sidebar-item">
        <SettingsIcon class="icon"></SettingsIcon>
        <span>Настройки</span>
      </Link>

      <div style="flex-grow: 1;"></div>

      <Link href="#" class="sidebar-item">
        <SearchIcon class="icon"></SearchIcon>
        <span>Поиск</span>
      </Link>
    </div>

    <div class="bodyContainer" style="margin-bottom: 20vh;">
      <div v-if="hasErrors" class="alert alert-danger" style="position: fixed; top: 0; right: 0; z-index: 2000;">
        <ul>
          <li v-for="(error, index) in allErrors" :key="index">{{ error }}</li>
        </ul>
      </div>

      <slot />
    </div>

    <AudioPlayer />
  </div>
</template>

<script setup>
  import { ref, computed, onMounted } from 'vue'
  import { usePage, Link } from '@inertiajs/inertia-vue3'
  import AudioPlayer from '@/components/audioplayer.vue'
  import { error } from 'laravel-mix/src/Log'
  import { useUserStore } from '@/stores/activeUserStore';

  import MainPageIcon from '@/assets/icons/mainPageIcon.svg'
  import SearchIcon from '@/assets/icons/searchIcon.svg'
  import SettingsIcon from '@/assets/icons/settingsIcon.svg'
  import CollectionIcon from '@/assets/icons/collectionIcon.svg'
  import SidebarIcon from '@/assets/icons/sidebar.svg'

  const props = defineProps({
    playlists: Object,
    user_playlists: Object,
    artists: Object,
    authUser: Object
  })

  const userStore = useUserStore();
  const page = usePage()
  const user = computed(() => page.props.authUser ?? null) // Безопасный доступ
  const errors = computed(() => page.props.errors)
  // const flashError = computed(() => page.props.flash.error)

  // const allErrors = computed(() => {
  //     const result = Object.values(errors.value)
  //     if (flashError.value) result.push(flashError.value)
  //     return result
  // })

  // const hasErrors = computed(() => allErrors.value.length > 0)
  const userImage = '/resources/images/templates/userImage.svg'

  // === Логика отображения сайдбара ===
  const isHidden = ref(false)
  const isHiding = ref(false)

  function toggleSidebar() {
    if (!isHidden.value) {
      // Скрыть
      isHiding.value = true
      setTimeout(() => {
        isHidden.value = true
      }, 10) // подгоняем под CSS-анимацию
    } else {
      // Показать
      isHidden.value = false
      setTimeout(() => {
        isHiding.value = false
      }, 10) // дать Vue прорисовать
    }
  }

  onMounted(() =>{
    userStore.setUser(props.authUser)
  })
</script>


<style scoped>
  /* Здесь можно подключить свою анимацию */
  .sidebar {
    transition: opacity 0.3s ease;
    opacity: 1;
  }

  .sidebar.hidden {
    opacity: 0;
  }

  .sidebar.d-none {
    display: none;
  }
</style>