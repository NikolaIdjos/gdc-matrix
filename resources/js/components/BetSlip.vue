<template>
    <transition name="slide">
        <div
            v-if="show"
            class="fixed top-0 right-0 w-80 h-full bg-white shadow-lg z-50 p-6 flex flex-col"
        >
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Bet Slip</h2>
                <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700 text-2xl cursor-pointer">&times;</button>
            </div>

            <div class="overflow-y-auto">
                <!--Money Input Component-->
                <div v-if="bet.markets && bet.markets.length">
                    <p class="font-medium pb-6 border-b border-gray-300">{{ bet.name }}</p>

                    <div v-if="bet.markets?.length">
                        <div
                            v-for="market in bet.markets.filter(m => m.selection)"
                            :key="market.id"
                            class="mt-4 mb-4 border-b border-gray-300 pb-4 flex justify-between items-start"
                        >
                            <div>
                                <p class="font-semibold">{{ market.name }}</p>
                                <p class="text-gray-700">Selection: {{ market.selection.name }}</p>
                                <p class="text-gray-500">Odds: {{ market.selection.odds }}</p>
                            </div>
                            <button
                                @click="removeSelection(market.id)"
                                class="text-red-500 hover:text-red-700 text-2xl mt-1 cursor-pointer"
                            >
                                &times;
                            </button>
                        </div>
                    </div>

                    <!--Money Input Component-->
                    <MoneyInput v-model="stake"/>

                    <!--Combined odds and Potential payout-->
                    <div
                        class="mt-4 p-6 bg-gray-100 rounded-xl shadow-md text-center border border-gray-200"
                        v-if="betSummary.combinedOdds && betSummary.potentialPayout"
                    >
                        <p class="text-lg font-semibold text-gray-700 mb-2">
                            Combined Odds
                        </p>
                        <p class="text-2xl font-bold text-blue-600 mb-4">
                            {{ betSummary.combinedOdds }}
                        </p>

                        <hr class="border-gray-300 my-4">

                        <p class="text-lg font-semibold text-gray-700 mb-2">
                            Potential Payout
                        </p>
                        <p class="text-2xl font-bold text-green-600">
                            {{ betSummary.potentialPayout
                            ? new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(betSummary.potentialPayout)
                            : '$0.00' }}
                        </p>
                    </div>
                </div>
                <div v-else class="text-gray-400 mt-4">
                    No selections yet.
                </div>
            </div>
        </div>
    </transition>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import axios from 'axios';
import debounce from 'lodash/debounce';
import MoneyInput from './MoneyInput.vue';

const props = defineProps<{
    show: boolean;
    bet: Bet;
}>();

const stake = ref<number>(0);
const errors = ref<string[]>([]);
const betSummary = ref<{ combinedOdds?: number; potentialPayout?: number }>({});

const removeSelection = (marketId: number) => {
    props.bet.markets = props.bet.markets.filter(m => m.id !== marketId);
    if (props.bet.markets.length === 0) {
        stake.value = 0;
    }
};

const calculate = async () => {
    if (stake.value <= 0 || !props.bet.markets.length) {
        betSummary.value = {};
        return;
    }

    const response = await axios.post('/api/bets', {
        stake: stake.value,
        selection_ids: props.bet.markets.map(m => m.selection.id),
    });

    betSummary.value = {
        combinedOdds: response.data.combined_odds,
        potentialPayout: response.data.potential_payout,
    };
};

const debouncedCalculate = debounce(calculate, 500);

watch(
    [stake, () => props.bet.markets?.map(m => m.selection?.id) || []],
    debouncedCalculate,
    { deep: true }
);
</script>

<style>
.slide-enter-active,
.slide-leave-active {
    transition: transform 0.3s;
}
.slide-enter-from {
    transform: translateX(100%);
}
.slide-leave-to {
    transform: translateX(100%);
}
</style>
