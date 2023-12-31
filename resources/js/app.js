import './bootstrap';
import { createApp } from 'vue';
import { ObserveVisibility } from 'vue-observe-visibility';
import vue_cookie from 'vue-cookies';
import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap"
import root from './app.vue';
import router from '../routes/client.js';

const app  = createApp(root).directive('observe-visibility', ObserveVisibility)
app.provide('cookies',app.config.globalProperties.$cookies)
app.use(router)
app.use(vue_cookie)
app.mount('#app')
