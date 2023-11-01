import {createRouter, createWebHistory} from "vue-router";
import login from '../Pages/login.vue';
import Dashboard from '../Pages/dashboard.vue';
import Register from '../Pages/register.vue';
const routes = [
    {
        path : '/',
        name : 'Dashboard',
        component: Dashboard
    },
    {
        path: '/register',
        name : 'Register',
        component: Register
    },
    {
        path : '/login',
        name : 'login',
        component : login
    },

];

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router;
