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
        ? 'inline-flex items-center px-2 pt-1 border-b-2 border-pelindo-cyan text-sm font-bold leading-5 text-pelindo-blue focus:outline-none focus:border-pelindo-blue transition duration-150 ease-in-out'
        : 'inline-flex items-center px-2 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-pelindo-blue hover:border-pelindo-cyan/50 focus:outline-none focus:text-pelindo-blue focus:border-pelindo-cyan/50 transition duration-150 ease-in-out'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
