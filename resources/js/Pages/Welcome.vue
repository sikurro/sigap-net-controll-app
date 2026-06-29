<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: false,
    },
    phpVersion: {
        type: String,
        required: false,
    },
});
</script>

<template>
    <Head title="Selamat Datang - SIGAP Pelindo" />

    <div class="min-h-screen bg-slate-900 text-white flex flex-col justify-between font-sans selection:bg-pelindo-cyan selection:text-slate-900">
        <!-- Top Navbar -->
        <header class="w-full py-6 px-8 flex justify-between items-center border-b border-white/10 backdrop-blur-md bg-slate-900/50 fixed top-0 z-50">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-tr from-pelindo-blue to-pelindo-cyan flex items-center justify-center shadow-lg shadow-pelindo-cyan/20 font-bold text-xl tracking-wider">
                    ⚓
                </div>
                <div>
                    <span class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-white via-slate-200 to-pelindo-cyan bg-clip-text text-transparent">
                        SIGAP
                    </span>
                    <span class="text-xs block text-pelindo-cyan font-semibold tracking-widest uppercase">
                        PELINDO GROUP
                    </span>
                </div>
            </div>

            <div v-if="canLogin" class="flex items-center space-x-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="px-5 py-2.5 rounded-full bg-gradient-to-r from-pelindo-blue to-pelindo-cyan hover:opacity-90 font-medium text-sm transition shadow-lg shadow-pelindo-cyan/20 flex items-center space-x-2"
                >
                    <span>Buka Command Center</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </Link>

                <template v-else>
                    <Link
                        :href="route('login')"
                        class="px-6 py-2.5 rounded-full bg-pelindo-blue hover:bg-pelindo-blue/80 border border-pelindo-cyan/30 text-white font-medium text-sm transition shadow-lg hover:shadow-pelindo-cyan/30"
                    >
                        Login Akses
                    </Link>
                </template>
            </div>
        </header>

        <!-- Hero Section -->
        <main class="flex-1 flex items-center justify-center px-6 lg:px-16 pt-24 pb-16 relative overflow-hidden">
            <!-- Background Glows -->
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-pelindo-blue/30 rounded-full blur-3xl pointer-events-none -z-10 animate-pulse"></div>
            <div class="absolute bottom-1/3 right-1/4 w-96 h-96 bg-pelindo-cyan/20 rounded-full blur-3xl pointer-events-none -z-10"></div>

            <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Left Content -->
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <div class="inline-flex items-center space-x-2 px-3 py-1.5 rounded-full bg-pelindo-blue/40 border border-pelindo-cyan/40 text-pelindo-cyan text-xs font-semibold uppercase tracking-wider">
                        <span class="w-2 h-2 rounded-full bg-pelindo-cyan animate-ping"></span>
                        <span>Sistem Manajemen SDM Pemeliharaan Terpadu</span>
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight">
                        Executive SDM <br />
                        <span class="bg-gradient-to-r from-pelindo-cyan via-sky-300 to-white bg-clip-text text-transparent">
                            Command Center
                        </span>
                    </h1>

                    <p class="text-slate-300 text-lg sm:text-xl leading-relaxed max-w-2xl">
                        Standardisasi dan kalkulasi akurat kebutuhan teknisi serta personel non-teknis pemeliharaan alat berat pelabuhan (Crane, RTG, Reach Stacker) berdasarkan regulasi dan jam kerja efektif.
                    </p>

                    <div class="pt-4 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                        <Link
                            :href="$page.props.auth.user ? route('dashboard') : route('login')"
                            class="w-full sm:w-auto px-8 py-4 rounded-xl bg-gradient-to-r from-pelindo-blue to-pelindo-cyan text-white font-bold text-base shadow-xl shadow-pelindo-cyan/20 hover:scale-[1.02] active:scale-[0.98] transition flex items-center justify-center space-x-3"
                        >
                            <span>{{ $page.props.auth.user ? 'Masuk ke Dashboard' : 'Mulai Sesi Kalkulasi' }}</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </Link>
                    </div>

                    <!-- Highlight Metrics -->
                    <div class="pt-8 grid grid-cols-3 gap-6 border-t border-white/10 max-w-lg mx-auto lg:mx-0">
                        <div>
                            <div class="text-2xl sm:text-3xl font-extrabold text-pelindo-cyan">3 Pilar</div>
                            <div class="text-xs text-slate-400 mt-1">Metode Kalkulasi SDM</div>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-extrabold text-white">100%</div>
                            <div class="text-xs text-slate-400 mt-1">Standar Job Plan</div>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-extrabold text-pelindo-orange">Real-Time</div>
                            <div class="text-xs text-slate-400 mt-1">Simulasi & Analitik</div>
                        </div>
                    </div>
                </div>

                <!-- Right Illustration Card -->
                <div class="lg:col-span-5 flex justify-center">
                    <div class="relative w-full max-w-md bg-slate-800/60 border border-white/10 rounded-2xl p-6 backdrop-blur-xl shadow-2xl">
                        <div class="flex items-center justify-between pb-4 border-b border-white/10 mb-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                                <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                                <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                            </div>
                            <span class="text-xs font-mono text-slate-400">STATUS: ACTIVE PORT ENGINE</span>
                        </div>

                        <div class="space-y-4">
                            <div class="p-4 rounded-xl bg-slate-900/80 border border-pelindo-blue/50 flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2.5 rounded-lg bg-pelindo-blue/30 text-pelindo-cyan">
                                        🏗️
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-white">Container Crane (CC)</div>
                                        <div class="text-xs text-slate-400">Bobot Kapasitas & Jam Operasi</div>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded bg-emerald-500/20 text-emerald-400 text-xs font-semibold">Optimal</span>
                            </div>

                            <div class="p-4 rounded-xl bg-slate-900/80 border border-pelindo-cyan/30 flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2.5 rounded-lg bg-pelindo-cyan/20 text-pelindo-cyan">
                                        ⚙️
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-white">Rubber Tyred Gantry (RTG)</div>
                                        <div class="text-xs text-slate-400">Preventive Maintenance Plan</div>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded bg-pelindo-cyan/20 text-pelindo-cyan text-xs font-semibold">Terjadwal</span>
                            </div>

                            <div class="p-4 rounded-xl bg-slate-900/80 border border-pelindo-orange/30 flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2.5 rounded-lg bg-pelindo-orange/20 text-pelindo-orange">
                                        📊
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-white">Breakdown Reserve</div>
                                        <div class="text-xs text-slate-400">Target Availability Aligned</div>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded bg-pelindo-orange/20 text-pelindo-orange text-xs font-semibold">Ready</span>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-white/10 flex justify-between items-center text-xs text-slate-400 font-mono">
                            <span>PT PELABUHAN INDONESIA (PERSERO)</span>
                            <span class="text-pelindo-cyan font-bold">V 1.0</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="w-full py-6 px-8 border-t border-white/5 bg-slate-950/80 text-center text-xs text-slate-500 flex flex-col sm:flex-row justify-between items-center gap-2">
            <div>
                &copy; {{ new Date().getFullYear() }} PT Pelabuhan Indonesia (Persero). All Rights Reserved.
            </div>
            <div class="flex space-x-4">
                <span class="hover:text-slate-400 transition">Sistem Kalkulasi SDM Pemeliharaan</span>
                <span>&bull;</span>
                <span class="text-pelindo-cyan font-semibold">SIGAP NET CONTROLL</span>
            </div>
        </footer>
    </div>
</template>
