<template>
    <input
        type="date"
        class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition w-full sm:w-auto"
        v-model="localDate"
        @change="onChange"
        :title="props.title"
    />
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';

const props = defineProps<{
    modelValue?: string;
    title: string;
}>();

const emit = defineEmits(['update:modelValue']);

const localDate = ref(props.modelValue || '');

watch(() => props.modelValue, (val) => {
    localDate.value = val || '';
});

const onChange = () => {
    if (!localDate.value) {
        emit('update:modelValue', '');
        return;
    }
    emit('update:modelValue', localDate.value);
};
</script>
