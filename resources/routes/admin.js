import { createRouter, createWebHistory } from "vue-router";
import AdminLayout from "../js/admin/adminLayout.vue";
import adminDashboard from '../Pages/admin/dashboard.vue';
import Books from '../Pages/admin/books.vue';

const routes = [
    {
        path: "/admin",
        component: AdminLayout,
        children: [
            {
                path: "/admin/dashboard",
                name: "adminDashboard",
                component: adminDashboard,
            },
            {
                path : "/admin/books",
                name : 'BooksDashboard',
                component: Books
            }
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
