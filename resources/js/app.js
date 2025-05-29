// import { createApp, h } from 'vue'
// import { createInertiaApp } from '@inertiajs/inertia-vue3'
// import { createPinia } from 'pinia'
// import Layout from './layouts/mainLayout.vue'
// import piniaPluginPersistedstate from 'pinia-plugin-persistedstate';
// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';

// import 'bootstrap/dist/css/bootstrap.css'
// import 'bootstrap-vue/dist/bootstrap-vue.css'

// // Создаем хранилище Pinia
// const pinia = createPinia()
// pinia.use(piniaPluginPersistedstate);

// createInertiaApp({
//   resolve: name => {
//     const pages = import.meta.glob('./pages/**/*.vue', { eager: true })
//     let page = pages[`./pages/${name}.vue`]
    
//     // Добавляем layout по умолчанию для всех страниц
//     page.default.layout = page.default.layout || Layout
    
//     return page
//   },
//   setup({ el, App, props, plugin }) {
//     createApp({ render: () => h(App, props) })
//       .use(plugin)
//       .use(pinia) // Используем Pinia во всем приложении

      
//       .mount(el)
//   },
// })

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import { createPinia } from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import Layout from './layouts/mainLayout.vue'

import './echo.js'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

// Pinia + persist
const pinia = createPinia()
pinia.use(piniaPluginPersistedstate)

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./pages/**/*.vue', { eager: true })
    const page = pages[`./pages/${name}.vue`]
    page.default.layout = page.default.layout || Layout
    return page
  },

  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(pinia)

    app.mount(el)
  },
})
