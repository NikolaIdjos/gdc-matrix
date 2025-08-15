<template>
    <div class="relative min-h-screen bg-gray-50 p-6 lg:p-12">
        <div v-if="event" class="space-y-6">
            <div class="bg-white shadow rounded-lg p-6 text-center">
                <p class="text-xl font-semibold">
                    {{ event.teams[0]?.name }} vs {{ event.teams[1]?.name }}
                </p>
            </div>
            <!-- Event Card -->
            <div class="bg-white shadow rounded-lg p-6 text-center">
                <div class="space-y-2">
                    <p class="font-semibold"> {{ event.status ? event.status.replace(/_/g, ' ') : '-' }}</p>
                    <p>
                        <span class="block">{{ $formatDate(event.scheduled_at).split(' ')[0] }}</span>
                        <span class="text-sm text-gray-400">{{ $formatDate(event.scheduled_at).split(' ')[1] }}</span>
                    </p>
                    <p><span class="font-semibold">League:</span> {{ event.league?.name || '-' }}</p>

                </div>
            </div>


            <!--Markets-->
            <div v-for="market in event.markets" :key="market.id" class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">{{ market.name }}</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    <!--Selections in market-->
                    <label
                        v-for="selection in market.selections"
                        :key="selection.id"
                        class="border border-gray-300 rounded-lg p-4 cursor-pointer hover:bg-gray-50 flex items-center space-x-3"
                    >
                        <input
                            type="radio"
                            :name="'market-' + market.id + '-event-' + event.id"
                            :checked="bet.markets.find(m => m.id === market.id)?.selection?.id === selection.id"
                            @click="selectOption(selection)"
                            class="form-radio h-5 w-5 text-blue-600"
                        />
                        <div>
                            <p class="font-medium">{{ selection.name }}</p>
                            <p class="text-gray-500">Odds: <span class="font-semibold">{{ selection.odds }}</span></p>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!--Sticky View Bet Slip Button-->
        <button
            @click="showSlip = !showSlip"
            class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-blue-600 text-white px-6 py-3 rounded-full shadow-lg hover:bg-blue-700 transition-colors flex items-center space-x-2 z-50 cursor-pointer"
        >
            <span>View Bet Slip</span>
        </button>

        <!--Bet Slip-->
        <BetSlip
            :show="showSlip"
            :bet="bet"
            @close="showSlip = false"
        />
    </div>
</template>


<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { usePage } from '@inertiajs/vue3';
import BetSlip from '@/components/BetSlip.vue';
import Bet from '@/types/Bet';
import { Selection } from '@/types/Selection';

const event = ref<any>(null);
const eventId = usePage().props.event_id;
const bet = ref<Bet>({});
const showSlip = ref(false);

// Fetch data
const fetchEvent = async () => {
    const response = await axios.get(`/api/events/${eventId}`);
    event.value = response.data;

    bet.value ={
        eventId: event.value.id,
        name: event.value.name,
        markets: [],
    };
};

// Fetch data on mount
onMounted(fetchEvent);

// On Select option
const selectOption = (selection: Selection) => {
    if (!event.value) {
        return;
    }

    const marketIndex = bet.value.markets.findIndex(m => m.id === selection.market_id);

    if (marketIndex !== -1) {
        const market = bet.value.markets[marketIndex];
        if (market.selection?.id === selection.id) {
            bet.value.markets.splice(marketIndex, 1);
        } else {
            market.selection = selection;
        }
    } else {
        const marketName = event.value.markets.find(m => m.id === selection.market_id)?.name || '';
        bet.value.markets.push({
            id: selection.market_id,
            name: marketName,
            selection,
        });
    }
};

</script>
