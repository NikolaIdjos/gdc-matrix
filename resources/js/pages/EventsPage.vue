<template>
    <div class="min-h-screen bg-gray-50 p-6 lg:p-12">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Events</h1>

        <!--Filters-->
        <div class="mb-6 flex flex-wrap gap-4">
            <input
                v-model="search"
                @input="fetchEvents"
                type="text"
                placeholder="Search events"
                class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition w-full sm:w-auto"
            />

            <select
                v-model="status"
                @change="fetchEvents"
                class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition w-full sm:w-auto"
            >
                <option value="">All Statuses</option>
                <option value="1">Scheduled</option>
                <option value="2">In Play</option>
                <option value="3">Finished</option>
                <option value="4">Cancelled</option>
            </select>

            <!--DatePicker component-->
            <DatePicker v-model="startsAfter" @change="fetchEvents" :title="'The match starts after the selected date'"/>

        </div>

        <!--Show all events-->
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Event Title</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">League</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Scheduled At</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Home vs Away</th>
                    <th class="px-6 py-3"></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                <tr
                    v-for="event in events"
                    :key="event.id"
                    class="hover:bg-gray-200 cursor-pointer transition"
                    @click="goToEvent(event.id)"
                >
                    <td class="px-6 py-4 text-gray-900 font-medium">{{ event.name }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ event.league?.name || '-' }}</td>
                    <td class="px-6 py-4 text-gray-500">
                        <div class="flex flex-col">
                            <span class="font-medium">{{ $formatDate(event.scheduled_at).split(' ')[0] }}</span>
                            <span class="text-sm text-gray-400">{{ $formatDate(event.scheduled_at).split(' ')[1] }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500">
                        {{ event.teams[0].name }} vs {{ event.teams[1].name }}
                    </td>
                    <td class="px-6 py-4 text-gray-500 font-medium">
                        {{ event.status }}
                    </td>
                </tr>
                </tbody>

            </table>
        </div>

        <!--Pagination component-->
        <div class="mt-6 flex justify-center">
            <Pagination
                v-model:currentPage="page"
                :lastPage="lastPage || 1"
            />
        </div>
    </div>
</template>
<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import Pagination from '@/components/Pagination.vue';
import { Event } from '@/types/Event';
import DatePicker from '@/components/DatePicker.vue';
import { toUTCDateOnly } from '@/lib/dateUtils';

const events = ref<Event[]>([]);
const page = ref(1);
const lastPage = ref(1);
const search = ref('');
const status = ref('');
const startsAfter = ref('');

const fetchEvents = async () => {
    try {
        const params = {
            page: page.value,
            ...(search.value && { search: search.value }),
            ...(status.value && { status_id: status.value }),
            ...(startsAfter.value && { starts_after: toUTCDateOnly(startsAfter.value) }),
        };

        const response = await axios.get('/api/events', { params });
        events.value = response.data.data;
        lastPage.value = response.data.meta.last_page || 1;
    } catch (error) {
        console.error(error);
    }
};

const goToEvent = (id: number) => {
    window.location.href = `/events/${id}`;
};

watch(page, fetchEvents);
onMounted(fetchEvents);
</script>
