<template>
    <div class="container p-5">
        <div class="container-sm w-50">
            <div class="alert" v-if="alertVisible" :class="alertClass" role="alert">
                {{ alertMessage }}
            </div>
            <div class="card m-auto">
                <h4 class="card-header text-center">Login</h4>
                <div class="card-body">
                    <form @submit.prevent="login">
                        <div class="form-control mb-2">
                            <label for="email">Email</label>
                            <input class="form-control" type="email" id="email" v-model="form.email" required />
                        </div>
                        <div class="form-control mb-2">
                            <label for="password">Password</label>
                            <input class="form-control" type="password" id="password" v-model="form.password" required />
                        </div>
                        <div class="form-control text-center">
                            <button class="btn btn-outline-success w-25 m-1" type="submit">Login</button>
                            <button class="btn btn-outline-success w-25 m-1" @click="register">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import { reactive, ref, onMounted, inject, onBeforeMount } from 'vue';
import router from "../routes/admin.js";

export default {
    setup() {
        let $cookies = inject('$cookies');
        let isAuthenticated = ref(false);
        let alertClass = ref('');
        let alertMessage = ref('');
        let alertVisible = ref(false);

        const form = reactive({
            email: "",
            password: "",
        });

        const login = async () => {
            try {
                const response = await axios.post("/api/login", form);
                if(response.data.success){
                    showAlert("alert-success", response.data.message);
                    $cookies.set('access_token', response.data.data.token);
                    isAuthenticated.value = true;
                    window.location = '/';
                }
                else{
                    showAlert("alert-danger", response.data.message);
                }

            } catch (error) {
                showAlert("alert-danger", "Error: Something went wrong.");
            }
        };

        const isLoggedIn = () => {
            if ($cookies.get('access_token')) {
                isAuthenticated.value = true;
                router.push('/');
            }
        };
        const register = () =>{
            router.push('/register')
        }

        function showAlert(className, message) {
            alertClass.value = className;
            alertMessage.value = message;
            alertVisible.value = true;
            setTimeout(() => {
                alertVisible.value = false;
            }, 5000);
        }
        onMounted(isLoggedIn);

        return {
            form,
            login,
            isAuthenticated,
            alertClass,
            alertMessage,
            alertVisible,
            register,
        };
    },
};
</script>
