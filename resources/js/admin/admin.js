import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap";
import "vue-observe-visibility";
import vue_cookie from 'vue-cookies';
import { createApp } from 'vue';
import { ObserveVisibility } from 'vue-observe-visibility';

import root from '../admin/adminApp.vue';
import router from '../../routes/admin.js';

const app = createApp(root);

app.directive('observe-visibility', ObserveVisibility);

app.provide('cookies', app.config.globalProperties.$cookies);

app.use(router);
app.use(vue_cookie);
app.mount('#admin_app');
