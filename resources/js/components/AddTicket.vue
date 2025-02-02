<template>
    <div class="p-4 bg-white shadow-md rounded-lg">
        <button
            @click="showForm = true"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
        >
            Add Ticket
        </button>

        <div v-if="showForm" class="mt-4 p-4 border rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-2">Create Ticket</h3>
            <form @submit.prevent="createTicket" class="space-y-3">
                <input v-model="title" type="text" placeholder="Title" class="w-full p-2 border rounded" required />

                <textarea v-model="description" placeholder="Description" class="w-full p-2 border rounded" required></textarea>

                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Submit
                </button>

                <button @click="showForm = false" type="button" class="ml-2 text-gray-600 hover:underline">
                    Cancel
                </button>
            </form>
        </div>
    </div>
</template>

<script>
import { ref } from "vue";
import axios from "axios";
import { useAuthStore } from "@/stores/auth";

export default {
    setup(_, { emit }) {
        const authStore = useAuthStore();
        const showForm = ref(false);
        const title = ref("");
        const description = ref("");

        const createTicket = async () => {
            try {
                const response = await axios.post(
                    "/api/tickets",
                    { title: title.value, description: description.value, status: status.value },
                    { headers: { Authorization: `Bearer ${authStore.token}` } }
                );

                emit("ticketAdded", response.data);
                showForm.value = false;
                title.value = "";
                description.value = "";
            } catch (error) {
                console.error("Error creating ticket:", error);
            }
        };

        return { showForm, title, description, status, createTicket };
    },
};
</script>
