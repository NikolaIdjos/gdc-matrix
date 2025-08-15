<template>
    <div class="mb-4 flex w-full sm:w-auto">
        <span
            class="inline-flex items-center px-3 border border-r-0 border-gray-300 rounded-l-lg bg-gray-50 text-gray-500 text-sm shadow-sm"
        >
            $
          </span>
        <input
            v-imask="maskOptions"
            @accept="onAccept"
            @blur="addTrailingZeros"
            placeholder="0.00"
            class="flex-1 min-w-0 block w-full px-3 py-3 border border-gray-300 rounded-r-lg shadow-sm transition text-sm focus:outline-none focus:ring-0 focus:border-gray-300"
        />
    </div>

    <div v-if="error" class="mb-4 text-red-500 text-sm">
        <ul>
            <li>Stake must be greater than 0.</li>
        </ul>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const props = defineProps({
    modelValue: [Number, null],
});

const error = ref<boolean>(false);
const emit = defineEmits(['update:modelValue']);
const localStake = ref<number | null>(props.modelValue);

// Mask setup
const maskOptions = {
    mask: Number,
    scale: 2,
    signed: false,
    thousandsSeparator: ',',
    radix: '.',
    mapToRadix: ['.'],
    unmask: 'typed',
    normalizeZeros: true,
    padFractionalZeros: true,
    lazy: false,
    min: 0,
    max: 1000000,
};

// @accept action
const onAccept = (e: any) => {
    localStake.value = e.detail.unmaskedValue;

    if (!localStake.value || localStake.value <= 0) {
        error.value = true;
        emit('update:modelValue', null);
    } else {
        error.value = false;
        emit('update:modelValue', localStake.value);
    }
};
</script>
