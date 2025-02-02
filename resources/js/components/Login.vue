<template>
    <div class="flex justify-center items-center h-screen">
        <div class="bg-white p-6 rounded shadow-lg w-96">
            <h2 class="text-2xl font-bold mb-4">Login</h2>

            <input v-model="email" type="email" placeholder="Email" class="w-full border p-2 mb-2" />
            <input v-model="password" type="password" placeholder="Password" class="w-full border p-2 mb-4" />

            <button @click="login" class="w-full bg-blue-500 text-white p-2 rounded">Login</button>

            <p class="text-red-500 mt-2" v-if="error">{{ error }}</p>
        </div>
    </div>
</template>

<script>
import { ref } from "vue";
import axios from "axios";
import { useAuthStore } from "../stores/auth.js";
import { useRouter } from "vue-router";

export default {
    setup() {
        const authStore = useAuthStore();
        const router = useRouter();
        const email = ref("");
        const password = ref("");
        const error = ref("");

        const login = async () => {
            try {
                const response = await axios.post("/api/login", {
                    email: email.value,
                    password: password.value,
                });

                authStore.setToken(response.data.token);
                router.push("/dashboard");
            } catch (err) {
                error.value = err.response?.data?.message || "Login failed";
            }
        };

        return { email, password, error, login };
    },
};
</script>
