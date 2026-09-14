import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import { setupSoundInteractions } from './utils/sound';

// Aktifkan efek audio klik pada interaksi menu & tombol
setupSoundInteractions();

const app = createApp(App);
app.use(router);
app.mount('#app');
