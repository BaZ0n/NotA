import Paths from 'laravel-mix/src/Paths';
import { defineStore } from 'pinia';
import { ref } from 'vue'

export const useUserStore = defineStore('user', {
    state: () => ({
        activeUser: null,
    }),
    actions: {
        setUser(user) {
            this.activeUser = user;
        },
        // Загрузка данных из localStorage
        loadFromStorage() {
            const savedData = localStorage.getItem('activeUser'); // Ключ по умолчанию
            if (savedData) {
                const parsed = JSON.parse(savedData);
                this.activeUser = parsed.activeUser;
            }
        },
    },
    persistedState: {
        storage: window.localStorage,
        pick: ['activeUser.id', 'activeUser.name'] 
    },
});