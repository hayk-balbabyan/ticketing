<template>
    <div class="flex justify-center items-center h-screen bg-gray-100">
        <div class="bg-white p-8 rounded-lg shadow-md w-96">
            <h2 class="text-2xl font-bold text-center mb-4">Login</h2>
            <form @submit.prevent="login" class="space-y-4">
                <input v-model="email" type="email" placeholder="Email"
                    class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                <input v-model="password" type="password" placeholder="Password"
                    class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">Login</button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import axios from "axios";

const email = ref("");
const password = ref("");
const authStore = useAuthStore();
const router = useRouter();

const login = async () => {
    try {
        const response = await axios.post("/api/login", {
            email: email.value,
            password: password.value
        });

        authStore.login(response.data.token);
        router.push("/dashboard");
    } catch (error) {
        console.error("Login failed", error);
    }
};
</script>
