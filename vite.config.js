import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import svgLoader from 'vite-svg-loader';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/colors.scss',
                'resources/sass/audioplayer.scss', 
                'resources/js/components/audioplayer.vue',
                'resources/js/components/tracks.vue',
                'resources/js/components/artists.vue',
                'resources/js/components/playlistButtons.vue'
                ],
            refresh: [
                'resources/views/**/*.blade.php',
                'app/Http/Controllers/**/*.php',
                'routes/**/*.php'
            ],
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        svgLoader(),
    ],
    server: {
        hmr: {
            host: 'localhost',
        },
    },
    build: {
        chunkSizeWarningLimit: 1600,
        rollupOptions: {
            output: {
                manualChunks: {
                    vue: ['vue', 'vue-router'],
                    axios: ['axios'],
                },
            },
        },
    },
    resolve: {
        alias: {
            '@': '/resources/js',
            '~': '/resources',
            // "vue": "@vue/compat/dist/vue.esm-bundler.js",
        },
    },
    css: {
        preprocessorOptions: {
          scss: {
            api: 'modern-compiler' // or "modern"
          }
        }
    }
});
