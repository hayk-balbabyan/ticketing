<template>
    <div class="flex justify-center items-center h-screen">
        <div class="bg-white p-6 rounded shadow-lg w-96">
            <h2 class="text-2xl font-bold mb-4">Register</h2>

            <input v-model="name" type="text" placeholder="Name" class="w-full border p-2 mb-2" />
            <input v-model="email" type="email" placeholder="Email" class="w-full border p-2 mb-2" />
            <input v-model="password" type="password" placeholder="Password" class="w-full border p-2 mb-2" />
            <input v-model="password_confirmation" type="password" placeholder="Confirm Password" class="w-full border p-2 mb-4" />

            <button @click="register" class="w-full bg-green-500 text-white p-2 rounded">Register</button>

            <p class="text-red-500 mt-2" v-if="error">{{ error }}</p>
        </div>
    </div>
</template>

<script>
import { ref } from "vue";
import axios from "axios";
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";

export default {
    setup() {
        const authStore = useAuthStore();
        const router = useRouter();
        const name = ref("");
        const email = ref("");
        const password = ref("");
        const password_confirmation = ref("");
        const error = ref("");

        const register = async () => {
            try {
                const response = await axios.post("/api/register", {
                    name: name.value,
                    email: email.value,
                    password: password.value,
                    password_confirmation: password_confirmation.value,
                });

                authStore.setToken(response.data.token);
                router.push("/dashboard");
            } catch (err) {
                error.value = err.response?.data?.message || "Registration failed";
            }
        };

        return { name, email, password, password_confirmation, error, register };
    },
};
</script>
