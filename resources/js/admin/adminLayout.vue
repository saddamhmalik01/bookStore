<template>
    <div>
        <Header />
        <div class="admin-layout">
            <Sidebar />
            <main class="admin-content">
                <router-view></router-view>
            </main>
        </div>
    </div>
</template>

<script>
import Header from "../../utility/Header.vue";
import Sidebar from "../../Pages/admin/sidebar.vue";

export default {
    name: "AdminLayout",
    components: {
        Header,
        Sidebar
    }
};
</script>

<style scoped>
.admin-layout {
    display: flex;
}

.admin-content {
    flex: 1;
}
</style>
