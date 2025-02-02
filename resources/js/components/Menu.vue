<script setup>
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";

const authStore = useAuthStore();
const router = useRouter();

const logout = () => {
    authStore.logout();
    router.push("/login");
};
</script>

<template>
    <nav class="bg-blue-600 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-lg font-semibold">My App</div>

            <ul class="flex items-center space-x-6">
                <li><router-link to="/" class="hover:underline">Home</router-link></li>

                <template v-if="authStore.isAuthenticated">
                    <li><router-link to="/dashboard" class="hover:underline">Dashboard</router-link></li>
                    <li>
                        <button @click="logout" class="bg-red-500 px-4 py-2 rounded hover:bg-red-600">
                            Logout
                        </button>
                    </li>
                </template>
                <template v-else>
                    <li><router-link to="/login" class="hover:underline">Login</router-link></li>
                    <li><router-link to="/register" class="hover:underline">Register</router-link></li>
                </template>
            </ul>
        </div>
    </nav>
</template>
