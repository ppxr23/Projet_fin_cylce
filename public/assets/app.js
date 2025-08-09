import { createApp } from 'vue';
import Menu from './components/menu_horizontale.vue';

const app = createApp({});
app.component('Menu', Menu);
app.mount('#app');