<template>
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-4">Tickets</h2>

        <AddTicket @ticketAdded="fetchTickets" />

        <div class="my-4 flex space-x-4">
            <input
                v-model="filters.title"
                type="text"
                placeholder="Filter by Title"
                class="p-2 border rounded w-1/3"
                @input="fetchTickets"
            />
            <input
                v-model="filters.description"
                type="text"
                placeholder="Filter by Description"
                class="p-2 border rounded w-1/3"
                @input="fetchTickets"
            />
            <select v-model="filters.status" class="p-2 border rounded w-1/3" @change="fetchTickets">
                <option value="">All Statuses</option>
                <option value="open">Open</option>
                <option value="in_progress">In Progress</option>
                <option value="closed">Closed</option>
            </select>
        </div>

        <div v-if="tickets.length === 0" class="text-gray-600">No tickets found.</div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div
                v-for="ticket in tickets"
                :key="ticket._id"
                class="bg-white p-4 rounded shadow"
            >
                <h3 class="font-semibold">{{ ticket.title }}</h3>
                <p class="text-gray-600">{{ ticket.description }}</p>
                <span
                    class="px-2 py-1 rounded text-sm"
                    :class="{
                        'bg-blue-500 text-white': ticket.status === 'open',
                        'bg-yellow-500 text-white': ticket.status === 'in_progress',
                        'bg-red-500 text-white': ticket.status === 'closed',
                    }"
                >
                    {{ ticket.status }}
                </span>
            </div>
        </div>

        <!-- Pagination Controls -->
        <div class="flex justify-center mt-4 space-x-4">
            <button @click="changePage(prevPage)" :disabled="!prevPage" class="px-4 py-2 bg-gray-500 text-white rounded disabled:opacity-50">
                Previous
            </button>
            <span class="px-4 py-2">{{ currentPage }} / {{ lastPage }}</span>
            <button @click="changePage(nextPage)" :disabled="!nextPage" class="px-4 py-2 bg-gray-500 text-white rounded disabled:opacity-50">
                Next
            </button>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useAuthStore } from "@/stores/auth";
import AddTicket from "@/components/AddTicket.vue";

export default {
    components: { AddTicket },

    setup() {
        const authStore = useAuthStore();
        const tickets = ref([]);
        const filters = ref({ title: "", description: "", status: "" });

        const currentPage = ref(1);
        const lastPage = ref(null);
        const prevPage = ref(null);
        const nextPage = ref(null);

        const fetchTickets = async (page = 1) => {
            try {
                const response = await axios.get(`/api/tickets`, {
                    params: { ...filters.value, page },
                    headers: { Authorization: `Bearer ${authStore.token}` },
                });

                tickets.value = response.data.data; // Laravel pagination response
                currentPage.value = response.data.current_page;
                lastPage.value = response.data.last_page;
                prevPage.value = response.data.prev_page_url ? currentPage.value - 1 : null;
                nextPage.value = response.data.next_page_url ? currentPage.value + 1 : null;
            } catch (error) {
                console.error("Error fetching tickets:", error);
            }
        };

        const changePage = (page) => {
            if (page) fetchTickets(page);
        };

        onMounted(() => fetchTickets());

        return { tickets, filters, fetchTickets, currentPage, lastPage, prevPage, nextPage, changePage };
    },
};
</script>
