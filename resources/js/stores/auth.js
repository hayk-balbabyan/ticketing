import { defineStore } from "pinia";
import axios from "axios";

export const useAuthStore = defineStore("auth", {
    state: () => ({
        token: localStorage.getItem("token") || null,
        user: null,
    }),
    getters: {
        isAuthenticated: (state) => !!state.token, // Returns true if token exists
    },

    actions: {
        setToken(token) {
            console.log(token)
            this.token = token;
            localStorage.setItem("token", token);
            this.fetchUser(); // Fetch user after login/register
        },

        async fetchUser() {
            if (!this.token) return;
            try {
                const response = await axios.get("/api/user", {
                    headers: { Authorization: `Bearer ${this.token}` },
                });
                this.user = response.data;
            } catch (error) {
                console.error("Failed to fetch user:", error);
            }
        },

        logout() {
            this.token = null;
            this.user = null;
            localStorage.removeItem("token");
        },
    },
});
