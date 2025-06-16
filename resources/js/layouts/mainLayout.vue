<template>
  <div style="font-family: Vollkorn">

    <SideBar />

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
  import SideBar from '@/components/sidebar.vue'
  import { error } from 'laravel-mix/src/Log'
  import { useUserStore } from '@/stores/activeUserStore';


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

  const userImage = '/resources/images/templates/userImage.svg'

  onMounted(() =>{
    userStore.setUser(props.authUser)
  })
</script>