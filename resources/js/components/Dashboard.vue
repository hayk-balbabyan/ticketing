<template>
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-semibold mb-4">Your Tickets</h2>

        <div v-if="tickets.length > 0">
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 p-2">Title</th>
                        <th class="border border-gray-300 p-2">Description</th>
                        <th class="border border-gray-300 p-2">Status</th>
                        <th class="border border-gray-300 p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="ticket in tickets" :key="ticket._id">
                        <td class="border border-gray-300 p-2">{{ ticket.title }}</td>
                        <td class="border border-gray-300 p-2">{{ ticket.description }}</td>
                        <td class="border border-gray-300 p-2">
                            <span :class="statusClass(ticket.status)">{{ ticket.status }}</span>
                        </td>
                        <td class="border border-gray-300 p-2 flex space-x-2">
                            <button @click="editTicket(ticket)" class="bg-blue-500 text-white px-4 py-1 rounded">Edit</button>
                            <button @click="deleteTicket(ticket._id)" class="bg-red-500 text-white px-4 py-1 rounded">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination Controls -->
            <div class="flex justify-between items-center mt-4">
                <button @click="prevPage" :disabled="page <= 1" class="px-4 py-2 bg-gray-300 rounded">
                    Previous
                </button>
                <span>Page {{ page }} of {{ totalPages }}</span>
                <button @click="nextPage" :disabled="page >= totalPages" class="px-4 py-2 bg-gray-300 rounded">
                    Next
                </button>
            </div>
        </div>

        <div v-else class="text-gray-500">No tickets found.</div>

        <!-- Edit Modal -->
        <div v-if="editingTicket" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
            <div class="bg-white p-6 rounded shadow-lg w-96">
                <h3 class="text-lg font-semibold mb-4">Edit Ticket</h3>
                <input v-model="editingTicket.title" type="text" class="w-full border p-2 mb-2" placeholder="Title" />
                <textarea v-model="editingTicket.description" class="w-full border p-2 mb-2" placeholder="Description"></textarea>
                <select v-model="editingTicket.status" class="w-full border p-2 mb-4">
                    <option value="open">Open</option>
                    <option value="in_progress">In Progress</option>
                    <option value="closed">Closed</option>
                </select>
                <div class="flex justify-between">
                    <button @click="updateTicket" class="bg-green-500 text-white px-4 py-1 rounded">Save</button>
                    <button @click="cancelEdit" class="bg-gray-500 text-white px-4 py-1 rounded">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import { useAuthStore } from "@/stores/auth";
import { ref, onMounted } from "vue";

export default {
    setup() {
        const authStore = useAuthStore();
        const tickets = ref([]);
        const editingTicket = ref(null);
        const page = ref(1);
        const totalPages = ref(1);

        const fetchTickets = async () => {
            try {
                const response = await axios.get(`/api/tickets?onlyOwn=true&page=${page.value}`, {
                    headers: { Authorization: `Bearer ${authStore.token}` },
                });
                tickets.value = response.data.data;
                totalPages.value = response.data.last_page;
            } catch (error) {
                console.error("Failed to fetch tickets", error);
            }
        };

        const deleteTicket = async (id) => {
            if (confirm("Are you sure you want to delete this ticket?")) {
                try {
                    await axios.delete(`/api/tickets/${id}`, {
                        headers: { Authorization: `Bearer ${authStore.token}` },
                    });
                    fetchTickets();
                } catch (error) {
                    console.error("Failed to delete ticket", error);
                }
            }
        };

        const editTicket = (ticket) => {
            editingTicket.value = { ...ticket };
        };

        const updateTicket = async () => {
            try {
                await axios.put(`/api/tickets/${editingTicket.value._id}`, editingTicket.value, {
                    headers: { Authorization: `Bearer ${authStore.token}` },
                });
                editingTicket.value = null;
                fetchTickets();
            } catch (error) {
                console.error("Failed to update ticket", error);
            }
        };

        const cancelEdit = () => {
            editingTicket.value = null;
        };

        const prevPage = () => {
            if (page.value > 1) {
                page.value--;
                fetchTickets();
            }
        };

        const nextPage = () => {
            if (page.value < totalPages.value) {
                page.value++;
                fetchTickets();
            }
        };

        const statusClass = (status) => {
            return {
                "text-green-500 font-semibold": status === "open",
                "text-yellow-500 font-semibold": status === "in_progress",
                "text-red-500 font-semibold": status === "closed",
            };
        };

        onMounted(fetchTickets);

        return { tickets, deleteTicket, editTicket, updateTicket, cancelEdit, editingTicket, statusClass, page, totalPages, prevPage, nextPage };
    },
};
</script>
