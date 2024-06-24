import { createApp } from 'vue';
import App from './App.vue';
import router from './router'; // assumindo que seu arquivo de roteamento seja 'router.js'
import '@fortawesome/fontawesome-free/css/all.css';


createApp(App)
  .use(router)
  .mount('#app');