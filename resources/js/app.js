import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import { setupSoundInteractions } from './utils/sound';
import { vReveal } from './directives/vReveal';

// Aktifkan efek audio klik pada interaksi menu & tombol
setupSoundInteractions();

const app = createApp(App);
app.directive('reveal', vReveal);
app.use(router);
app.mount('#app');
