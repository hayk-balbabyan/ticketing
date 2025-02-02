import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from "@/stores/auth"; // Import auth store
import Login from '../components/Login.vue';
import Register from '../components/Register.vue';
import Dashboard from '../components/Dashboard.vue';
import Home from '../components/Home.vue';

const routes = [
    { path: '/', component: Home },
    { path: '/login', component: Login },
    { path: '/register', component: Register },
    { path: '/dashboard', component: Dashboard, meta: { requiresAuth: true } },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();
    const isAuthenticated = authStore.isAuthenticated;
  
    // Redirect if user is NOT logged in and trying to access /dashboard
    if (to.path === "/dashboard" && !isAuthenticated) {
      return next("/login");
    }
  
    // Redirect if user IS logged in and trying to access login or register
    if ((to.path === "/login" || to.path === "/register") && isAuthenticated) {
      return next("/dashboard");
    }
  
    next(); // Continue as normal
});
  
export default router;