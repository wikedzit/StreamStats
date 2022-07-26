import "./bootstrap";
import { createApp } from "vue";
import App from './App.vue';
import VueAxios from 'vue-axios';
import axios from 'axios';
import {router} from './routes';

createApp(App)
    .use(router, VueAxios, axios)
    .mount('#app')
