<template>
    <nav class="sidebar" style="height: 100vh">
        <ul class="table-striped">
            <router-link to="/admin/dashboard" tag="li" active-class="active-link">
                Dashboard
            </router-link>
            <router-link to="/admin/books" tag="li" active-class="active-link">
                Books
            </router-link>
        </ul>
    </nav>
</template>

<script>
export default {
    name: "Sidebar"
};
</script>

<style scoped>
.sidebar {
    width: 200px;
    background-color: #333;
    color: #fff;
}

ul {
    list-style: none;
    padding: 0;
}

li {
    padding: 10px;
}

.active-link {
    background-color: #555;
    color: #fff;
}

a {
    color: #fff;
    text-decoration: none;
    display: block;
    padding: 10px;
    transition: background-color 0.3s, color 0.3s;
}

a:hover {
    background-color: #555;
}
</style>
