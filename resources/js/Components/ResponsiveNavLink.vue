<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-pelindo-cyan text-start text-base font-bold text-pelindo-blue bg-pelindo-cyan/10 focus:outline-none focus:text-pelindo-blue focus:bg-pelindo-cyan/20 focus:border-pelindo-blue transition duration-150 ease-in-out'
        : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-pelindo-blue hover:bg-gray-50 hover:border-pelindo-cyan/50 focus:outline-none focus:text-pelindo-blue focus:bg-gray-50 focus:border-pelindo-cyan/50 transition duration-150 ease-in-out'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
