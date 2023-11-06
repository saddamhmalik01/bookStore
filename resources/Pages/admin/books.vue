<template>
    <div class="container">
        <div class="container-fluid">
            <div class="card text-center">
                <h5 class="card-header" >
                    Search and Filters
                </h5>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-4 col-lg-6">
                            Search here..
                            <input type="text"  @input="fetchBooks" v-model="form.search" class="form-control">
                        </div>
                        <div class="col-12 col-sm-6 col-md-3 col-lg-2">
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
                        <div v-if="selected_filter" class="col-12 col-sm-6 col-md-4 col-lg-1">
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
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Author</th>
                    <th scope="col">Genre</th>
                    <th scope="col">Publisher</th>
                    <th scope="col">Published On</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(book, index) in books" :key="index">
                    <th scope="row">{{index +1    }}</th>
                    <td>{{ book.title }}</td>
                    <td>{{ book.description }}</td>
                    <td>{{ book.author.name }}</td>
                    <td>{{ book.genre.name }}</td>
                    <td>{{ book.publisher.name }}</td>
                    <td>{{ book.published_on }}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" @click="goToEdit(book)"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

</template>
<script>
import {computed, inject, onBeforeMount, onMounted, reactive, ref} from "vue";
import router from "../../routes/admin.js";
import axios from "axios";

export default {
    setup(to) {
        let books = ref()
        let filters = ref()
        let filterValues = ref()
        let selected_filter = ref('')
        let filtering = ref('')
        let selected_filter_value = ref()
        let searchAndFilters = ref(false)
        let is_admin = ref(false);
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
            if(cookies.get('role') === 'admin')
            {
                is_admin.value = true
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

            const numVisiblePages = 10;
            const startPage = Math.max(1, currentPage.value - Math.floor(numVisiblePages / 2));
            const endPage = Math.min(startPage + numVisiblePages - 1, totalPages.value);

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
        const goToPage = (page) => {
            if (page >= 1 && page <= totalPages.value) {
                currentPage.value = page;
                fetchBooks();
            }
        };
        const goToEdit = (book) => {
            router.push({ name: 'editBook', params: { id: book.id } });
        }
        onMounted(fetchFilters)
        onBeforeMount(fetchBooks);
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
            is_admin,
            goToNextPage,
            goToPage,
            goToPreviousPage,
            resetFilters,
            fetchFilterValues,
            applyFiltersAndFetch,
            fetchBooks,
            goToEdit,
        }
    }
}
</script>

