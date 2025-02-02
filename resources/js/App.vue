<script setup>
import Menu from "./components/Menu.vue";
</script>

<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation Menu -->
        <Menu />

        <!-- Page Content -->
        <div class="container mx-auto mt-8 p-4">
            <router-view></router-view>
        </div>
    </div>
</template>