<template>
    <div class="container mt-5">
        <div class="container-fluid">
            <div class="card text-center">
                <h3 class="card-header">Books</h3>
            </div>
            <div class="card m-2">
                <div class="card-body">
                    <div class="card text-center">
                        <h5 class="card-header" @click="expandOrClose" >
                            Search and Filters
                            <i :class="searchAndFilters ? 'fa-solid fa-caret-up fa-2xs' : 'fa-solid fa-caret-down fa-2xs'"></i>
                        </h5>

                        <div class="card-body" v-show="searchAndFilters">
                            <div class="row">
                                <div class="col-12 col-sm-6 col-md-4 col-lg-6">
                                    Search here..
                                    <input type="text"  @input="fetchBooks" v-model="form.search" class="form-control">
                                </div>
                                <div class="col-12 col-sm-6 col-md-3 col-lg-1">
                                    Sort Field
                                    <select class="form-control" v-on:change="fetchBooks" v-model="form.sort_by">
                                        <option value="id">ID</option>
                                        <option value="title">Title</option>
                                        <option value="isbn">ISBN</option>
                                        <option value="published_on">Published On</option>
                                        <option value="created_at">Created At</option>
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6 col-md-2 col-lg-1">
                                    Direction
                                    <select class="form-control" v-on:change="fetchBooks" v-model="form.sort_direction">
                                        <option value="asc">ASC</option>
                                        <option value="desc">DESC</option>
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6 col-md-3 col-lg-1">
                                    Filter
                                    <select class="form-control" v-on:change="fetchFilterValues" v-model="selected_filter">
                                        <option value=""> --Select Filter-- </option>
                                        <option v-for="(filter, index) in filters" :value="index">{{ filter }}</option>
                                    </select>
                                </div>
                                <div v-if="selected_filter" class="col-12 col-sm-6 col-md-4 col-lg-2">
                                    Filter by
                                    <select class="form-control" v-on:change="applyFiltersAndFetch" v-model="selected_filter_value">
                                        <option v-for="(filterValue, index) in filterValues" :value="filterValue.id">{{ filterValue.name }}</option>
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6 col-md-3 col-lg-1">
                                   Reset
                                    <button type="reset" @click="resetFilters" class="btn btn-secondary">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                   <div class="books-container mt-2">
                       <div v-for="(book, index) in books" :key="index" class="card book p-2 w-100">
                           <img class="card-img-top" :src="book.image" :alt="book.title">
                           <hr>
                           <div class="card-body">
                               <h5 class="card-header text-center">{{ book.title }}</h5>
                                   <label><small>Author: {{book.author.name}}</small></label><br>
                                   <label ><small>Publisher: {{book.publisher.name}}</small></label><br>
                                   <label><small>Genre: {{book.genre.name}}</small></label><br>
                                   <label><small>Published On: {{book.published_on}}</small></label>
                           </div>
                       </div>
                   </div>
                    <div class="card m-1 d-flex text-center">
                        <nav aria-label="Page navigation" class="pt-3">
                            <ul class="pagination justify-content-center">
                                <li class="page-item" :class="{ disabled: currentPage === 1 }">
                                    <a class="page-link" @click="goToPreviousPage" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                </li>
                                <li class="page-item" v-for="page in getPages" :key="page" :class="{ active: currentPage === page }">
                                    <a class="page-link" @click="goToPage(page)">{{ page }}</a>
                                </li>
                                <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                                    <a class="page-link" @click="goToNextPage" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>


                </div>
            </div>


        </div>
    </div>
</template>
<script>
import {ref, onBeforeMount, onMounted, inject, reactive, computed} from 'vue';
import axios from "axios";
import router from "../routes/admin.js";
export default {
    setup(){
        let books = ref()
        let filters = ref()
        let filterValues = ref()
        let selected_filter = ref('')
        let filtering = ref('')
        let selected_filter_value = ref()
        let searchAndFilters = ref(false)
        const currentPage = ref(1)
        const totalPages = ref(1)

        let cookies = inject('$cookies');
        const form = reactive({
            search: "",
            sort_by : 'title',
            sort_direction : 'desc'
        });
        const axiosConfig = {
            headers: {
                Authorization: `Bearer ${cookies.get('access_token')}`,
            },
        };
        const fetchBooks = async () => {
            if(!cookies.get('access_token')){
                await router.push('/login');
                return;
            }
            const response = await axios.get("/api/books?page="+currentPage.value+"&search="+form.search+
                "&sort_by="+form.sort_by+"&sort_direction="+form.sort_direction+filtering.value,
                axiosConfig);
            if(response.data.success){
               books.value = response.data.data.data
                totalPages.value = response.data.data.last_page;
            }
        }
        const fetchFilters = async () =>{
            const response = await axios.get("/api/books/getFilters", axiosConfig);
            if(response.data.success){
                filters.value = response.data.data
            }
        }
        const fetchFilterValues =  async ()=> {
            const response = await axios.get("/api/books/getFilterValues?filter="+selected_filter.value, axiosConfig);
            if(response.data.success){
                filterValues.value = response.data.data
            }
        }
        const applyFiltersAndFetch = () => {
            currentPage.value=1
            if (selected_filter_value.value) {
                filtering.value = `&${selected_filter.value}=${selected_filter_value.value}`;
            } else {
                filtering.value = '';
            }
            fetchBooks();
        }
        const goToPreviousPage = () => {
            if (currentPage.value > 1) {
                currentPage.value--;
                fetchBooks();
            }
        };

        const goToNextPage = () => {
            if (currentPage.value < totalPages.value) {
                currentPage.value++;
                fetchBooks();
            }
        };
        const getPages = computed(() => {
            console.log("Total Pages: ", totalPages.value);
            console.log("Current Page: ", currentPage.value);

            const numVisiblePages = 10;
            const startPage = Math.max(1, currentPage.value - Math.floor(numVisiblePages / 2));
            const endPage = Math.min(startPage + numVisiblePages - 1, totalPages.value);

            console.log("Start Page: ", startPage);
            console.log("End Page: ", endPage);
            return Array.from({ length: endPage - startPage + 1 }, (_, index) => startPage + index);
        });


        const resetFilters = () => {
            form.search = "";
            form.sort_by = "title";
            form.sort_direction = "desc";
            filtering.value = '';
            selected_filter.value = '';
            selected_filter_value.value = '';
            currentPage.value = 1
            fetchBooks();
        }
        const  expandOrClose = () => {
            console.log(searchAndFilters.value);
            searchAndFilters.value = !searchAndFilters.value
            console.log(searchAndFilters.value);
        }
        const goToPage = (page) => {
            if (page >= 1 && page <= totalPages.value) {
                currentPage.value = page;
                fetchBooks();
            }
        };

        onBeforeMount(fetchBooks)
        onMounted(fetchFilters)
        return {
            form,
            books,
            currentPage,
            totalPages,
            filterValues,
            filters,
            selected_filter,
            selected_filter_value,
            searchAndFilters,
            getPages,
            goToNextPage,
            goToPage,
            goToPreviousPage,
            resetFilters,
            fetchFilterValues,
            applyFiltersAndFetch,
            fetchBooks,
            expandOrClose
        }
    }
}
</script>

