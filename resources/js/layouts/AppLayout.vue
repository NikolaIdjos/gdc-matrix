<template>
    <div>
        <nav class="bg-gray-800 text-white p-4 flex justify-center">
            <Link href="/" class="font-bold text-lg">Events</Link>
        </nav>

        <main class="p-6">
            <slot />
        </main>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>
