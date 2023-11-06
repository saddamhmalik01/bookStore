import { createRouter, createWebHistory } from "vue-router";
import AdminLayout from "../js/admin/adminLayout.vue";
import adminDashboard from '../Pages/admin/dashboard.vue';
import Books from '../Pages/admin/books.vue';
import vueCookie from 'vue-cookies';
import editBook from "../Pages/admin/editBook.vue";

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
            },
            {
                path: '/admin/edit-book/:id',
                name: 'editBook',
                component: editBook,
                props: true
            }
        ],
    },
];

function getCookiesRole() {
    return vueCookie.get('role')
}



const router = createRouter({
    history: createWebHistory(),
    routes,
});
router.beforeEach((to, from, next) => {
    const role = getCookiesRole();
    if (to.name.startsWith("admin") && role !== "admin") {
        window.location = window.location.origin
    } else {
        next();
    }
});

export default router;
