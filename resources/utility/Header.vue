<template>
    <nav class="navbar navbar-expand-lg navbar-dark text-bg-success p-3">
        <a class="navbar-brand" href="#">BookStore</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
<!--            <ul class="navbar-nav mr-auto">-->
<!--                <li class="nav-item active">-->
<!--                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>-->
<!--                </li>-->
<!--            </ul>-->
        </div>
            <button v-if="isAuthenticated" class="btn btn-outline-light my-2 my-sm-0" @click="logout">Logout</button>
    </nav>
</template>

<script>

import {onMounted,inject,ref} from "vue";
import router from "../routes/admin.js";

export default {
    setup()
    {
        let cookies = inject('$cookies');
        let isAuthenticated = ref(false);
        const isLogin = () => {
            if(cookies.get('access_token'))
            {
                isAuthenticated.value = true;
            }
        }
        const logout = () => {
            if(cookies.get('access_token'))
            {
                cookies.remove('access_token');
                isAuthenticated.value = false;
                router.push('/login')
            }
        }

        onMounted(isLogin)
        return {
            isAuthenticated,
            logout
        }
    }
};
</script>

<style scoped>
router-link {
    color: #fff;
    text-decoration: none;
}
</style>
