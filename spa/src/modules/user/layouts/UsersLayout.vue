<template>
    <!-- Aquí tu formulario... -->
    <div>
        <MainNavbar />
        <div class="container-fluid">
            <h2 class="mt-2">Usuarios</h2>
            <hr>
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
import MainNavbar from '@/components/MainNavbar.vue';
export default {
    data() {
        return {

        }
    },
    components: {
        MainNavbar
    },
};
</script>