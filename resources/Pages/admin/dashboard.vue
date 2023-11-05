<template>
    <div class="container mt-5">
        <div class="container-fluid">
            <div class="card text-center mt-2">
                <h3 class="card-header">Admin Dashboard</h3>
            </div>
            <div class="row justify-content-center text-center mt-3">
                <div class="col-lg-2 mb-3">
                    <div class="card">
                        <h4 class="card-header">Books</h4>
                        <div class="card-body">
                            <i class="fas fa-book fa-2x"></i>
                        </div>
                        <h1 class="text-center">{{ books }}</h1>
                    </div>
                </div>
                <div class="col-lg-2 mb-3">
                    <div class="card">
                        <h4 class="card-header">Authors</h4>
                        <div class="card-body">
                            <i class="fas fa-user-secret fa-2x"></i>
                        </div>
                        <h1 class="text-center">{{ authors }}</h1>
                    </div>
                </div>
                <div class="col-lg-2 mb-3">
                    <div class="card">
                        <h4 class="card-header">Genres</h4>
                        <div class="card-body">
                            <i class="fas fa-bars fa-2x"></i>
                        </div>
                        <h1 class="text-center">{{ genres }}</h1>
                    </div>
                </div>
                <div class="col-lg-2 mb-3">
                    <div class="card">
                        <h4 class="card-header">Publishers</h4>
                        <div class="card-body">
                            <i class="fab fa-pinterest-p fa-2x"></i>
                        </div>
                        <h1 class="text-center">{{ publishers }}</h1>
                    </div>
                </div>
                <div class="col-lg-2 mb-3">
                    <div class="card">
                        <h4 class="card-header">Users</h4>
                        <div class="card-body">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <h1 class="text-center">{{ users }}</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {inject, onBeforeMount, ref} from "vue";
import axios from "axios";

export default {
    name : 'admin-dashboard',
    setup()
    {
        let cookies = inject('$cookies');
        let books = ref();
        let authors = ref();
        let publishers = ref();
        let genres = ref();
        let users = ref();
        const axiosConfig = {
            headers: {
                Authorization: `Bearer ${cookies.get('access_token')}`,
            },
        };
        const fetchDashboardData = async () =>{
            console.log(cookies);
            const response = await axios.get('/api/admin/dashboard',axiosConfig)
            books.value = response.data.data.books;
            publishers.value = response.data.data.publishers;
            authors.value = response.data.data.authors;
            genres.value = response.data.data.genres;
            users.value = response.data.data.users;
        }
        onBeforeMount(fetchDashboardData)
        return {
            books,
            authors,
            publishers,
            genres,
            users,
            fetchDashboardData
        }
    }
}
</script>
