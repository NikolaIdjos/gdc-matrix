<template>
    <nav class="flex justify-center items-center gap-2 mt-6">
        <button
            @click="goToPage(currentPage - 1)"
            :disabled="currentPage === 1"
            class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed transition"
        >
            Prev
        </button>

        <button
            v-for="pageNumber in pages"
            :key="pageNumber"
            @click="goToPage(pageNumber)"
            :class="[
                'px-4 py-2 rounded-lg font-medium transition',
                pageNumber === currentPage
                  ? 'bg-blue-600 text-white shadow-lg'
                  : 'bg-gray-100 hover:bg-gray-200',
            ]"
        >
            {{ pageNumber }}
        </button>

        <button
            @click="goToPage(currentPage + 1)"
            :disabled="currentPage === lastPage"
            class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed transition"
        >
            Next
        </button>
    </nav>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps({
    currentPage: { type: Number, required: true },
    lastPage: { type: Number, required: true, default: 1 },
});

const emit = defineEmits(['update:currentPage']);
const lastPage = 1;

const pages = computed(() => Array.from({ length: props.lastPage }, (_, i) => i + 1));

const goToPage = (pageNumber) => {
    if (pageNumber >= 1 && pageNumber <= props.lastPage) {
        emit('update:currentPage', pageNumber);
    }
};
</script>
