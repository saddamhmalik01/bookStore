<template>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h1 class="text-center">Edit Book</h1>
            </div>
            <div class="card-body">
                <div class="alert" :class="messageClass" v-if="message">{{ message }}</div>
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" class="form-control" id="title" v-model="bookDetails.title" />
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea class="form-control" id="description" rows="3" v-model="bookDetails.description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="isbn">ISBN:</label>
                        <input type="text" class="form-control" id="isbn" v-model="bookDetails.isbn" />
                    </div>
                    <div class="form-group">
                        <label for="published_on">Published On:</label>
                        <input type="date" class="form-control" id="published_on" v-model="bookDetails.published_on" />
                    </div>
                    <div class="form-group">
                        <label for="image">Image:</label>
                        <input type="text" class="form-control" id="image" v-model="bookDetails.image" />
                    </div>
                <div class="form-group">
                    <label for="author">Author:</label>
                    <select class="form-control" id="author" v-model="bookDetails.author_id" >
                        <option v-for="author in authors" :key="author.id" :value="author.id">{{ author.name }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="publisher">Publisher:</label>
                    <select class="form-control" id="publisher" v-model="bookDetails.publisher_id">
                        <option v-for="publisher in publishers" :key="publisher.id" :value="publisher.id">{{ publisher.name }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="genre">Genre:</label>
                    <select class="form-control" id="genre" v-model="bookDetails.genre_id">
                        <option v-for="genre in genres" :key="genre.id" :value="genre.id">{{ genre.name }}</option>
                    </select>
                </div>

                <div class="text-center">
                        <button class="btn btn-primary" @click="saveChanges">Save Changes</button>
                    </div>
            </div>
        </div>
    </div>
</template>



<script>
import axios from "axios";
import VueCookie from "vue-cookies";
import router from '../../routes/admin.js'


export default {
    props: {
        id: String,
    },
    data() {
        return {
            message: "",
            messageClass: "alert-success",
            bookDetails: {
                title: "",
                description: "",
                isbn: "",
                image: "",
                genre_id : "",
                author_id : "",
                publisher_id : "",
                published_on: "",
                author: { id: null },
                publisher: { id: null },
                genre: { id: null },
            },
            authors: [],
            publishers: [],
            genres: [],
        };
    },
    beforeCreate() {
        axios.get('/api/getBookDetails/' + this.id, {
            headers: {
                Authorization: `Bearer ${VueCookie.get('access_token')}`,
            },
        })
            .then((response) => {
                this.bookDetails = response.data.data.bookDetails;
                this.authors = response.data.data.authors;
                this.publishers = response.data.data.publishers;
                this.genres = response.data.data.genres;
            });
    },
    methods : {
        async saveChanges() {
           await axios
                .post(`/api/updateBook/${this.id}`, this.bookDetails, {
                    headers: {
                        Authorization: `Bearer ${VueCookie.get('access_token')}`,
                    },
                })
                .then((response) => {
                    this.message = response.data.message;
                    this.messageClass = "alert-success";
                    setTimeout(function(){

                       router.push('/admin/books')
                    }, 5000);
                })
                .catch((error) => {
                    this.message = error.message
                    this.messageClass = "alert-danger";
                });
        },
    },
};
</script>

<style>
.form-control {
    margin: 3px;
    padding: 5px;
}
</style>
