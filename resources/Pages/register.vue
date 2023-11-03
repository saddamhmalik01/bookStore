<template>
    <div class="container p-5">
        <div class="container-sm w-50">
            <div class="alert" v-if="alertVisible" :class="alertClass" role="alert">
                {{ alertMessage }}
            </div>
            <div class="card m-auto">
                <h4 class="card-header text-center">Register</h4>
                <div class="card-body">
                    <form @submit.prevent="register">
                        <div class="form-control mb-2">
                            <label for="name">Name</label>
                            <input class="form-control" type="text" id="name" v-model="form.name" required />
                        </div>
                        <div class="form-control mb-2">
                            <label for="email">Email</label>
                            <input class="form-control" type="email" id="email" v-model="form.email" required />
                        </div>
                        <div class="form-control mb-2">
                            <label for="password">Password</label>
                            <input class="form-control" type="password" id="password" v-model="form.password" required />
                        </div>
                        <div class="form-control mb-2">
                            <label for="password_confirmation">Confirm Password</label>
                            <input class="form-control" type="password" id="password_confirmation" v-model="form.password_confirmation" required />
                        </div>
                        <div class="form-control text-center">
                            <button class="btn btn-outline-success w-25 m-1" type="submit">Register</button>
                            <button class="btn btn-outline-success w-25 m-1" @click="login">Go to Login</button>
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
import router from "../routes/client.js";

export default {
    setup() {
        let $cookies = inject('$cookies');
        let isAuthenticated = ref(false);
        let alertClass = ref('');
        let alertMessage = ref('');
        let alertVisible = ref(false);

        const form = reactive({
            name : "",
            email: "",
            password: "",
            password_confirmation : "",
        });

        const register = async () => {
            try {
                const response = await axios.post("/api/register", form);
                if(response.data.success){
                    showAlert("alert-success", response.data.message);
                    $cookies.set('access_token', response.data.data.token);
                    isAuthenticated.value = true;
                    window.location = '/'
                }
                else{
                    showAlert("alert-danger", response.data.message);
                }

            } catch (error) {
                const emailError = error.response.data.errors.email ? error.response.data.errors.email[0] : '';
                const passwordError = error.response.data.errors.password ? error.response.data.errors.password[0] : '';
                showAlert("alert-danger", emailError + ' ' + passwordError);
            }
        };

        const isLoggedIn = () => {
            if ($cookies.get('access_token')) {
                isAuthenticated.value = true;
                router.push('/');
            }
        };
        const login = () =>{
            router.push('/login')
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
