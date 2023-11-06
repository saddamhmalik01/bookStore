<template>
    <nav class="navbar navbar-expand-lg navbar-dark text-bg-success p-3">
        <a class="navbar-brand">BookStore</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">

        </div>
        <button v-if="isAuthenticated && is_admin" class="btn btn-outline-light my-2 my-sm-0 mx-2" @click="changePanel">
            {{ admin ? 'Admin Panel' : 'Client Panel' }}
        </button>
        <button v-if="isAuthenticated" class="btn btn-outline-light my-2 my-sm-0" @click="logout">Logout</button>

    </nav>
</template>

<script>
import { computed, inject, onMounted, ref } from "vue";
import {useRoute} from "vue-router";

export default {
    name: "Header",
    setup() {
        let cookies = inject('$cookies');
        let isAuthenticated = ref(false);
        let is_admin = ref(false);
        let admin = !window.location.href.includes('admin');
        let router = null;

        const isLogin = () => {
            if (cookies.get('access_token')) {
                isAuthenticated.value = true;
            }
            if (cookies.get('role') === 'admin') {
                is_admin.value = true;
            }
        };

        const logout = () => {
            if (cookies.get('access_token')) {
                cookies.remove('access_token');
                cookies.remove('role');
                isAuthenticated.value = false;
                import("../routes/admin.js").then((adminRouter) => {
                    window.location = window.location.origin
                });
            } else {
                import("../routes/client.js").then((clientRouter) => {
                    router = clientRouter.default;
                });
            }
            router.push('/login');
        };

        const changePanel = () => {
            let location = window.location;
            if (location.href.includes('admin')) {
                window.location.href = '/';
            } else {
                window.location.href = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '') + '/admin/dashboard';
            }
        };

        const route = useRoute();
        const isHomeRoute = computed(() => route.path === '/');

        onMounted(() => {
            isLogin();
        });

        return {
            isAuthenticated,
            is_admin,
            admin,
            changePanel,
            logout,
        };
    },
};

</script>
