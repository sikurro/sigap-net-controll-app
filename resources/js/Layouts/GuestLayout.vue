<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-slate-900 via-[#002447] to-pelindo-blue selection:bg-pelindo-cyan selection:text-slate-900 px-4 relative overflow-hidden">
        <!-- Background Ambient Glows -->
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-pelindo-cyan/20 rounded-full blur-3xl pointer-events-none animate-pulse"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-pelindo-blue/40 rounded-full blur-3xl pointer-events-none"></div>

        <div class="z-10 mb-6">
            <Link href="/" class="hover:scale-105 transition duration-300 block">
                <ApplicationLogo />
            </Link>
        </div>

        <div class="w-full sm:max-w-md px-8 py-8 bg-white/95 backdrop-blur-xl shadow-2xl shadow-black/40 border border-white/20 rounded-2xl z-10">
            <slot />
        </div>

        <div class="mt-8 text-center text-xs text-slate-400 z-10 font-mono">
            PT PELABUHAN INDONESIA (PERSERO) &bull; COMMAND CENTER
        </div>
    </div>
</template>
