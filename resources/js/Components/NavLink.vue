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
        ? 'inline-flex items-center px-3.5 py-2 border border-white/20 text-sm font-bold rounded-xl text-pelindo-cyan bg-white/15 shadow-inner transition duration-150 ease-in-out'
        : 'inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition duration-150 ease-in-out'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
