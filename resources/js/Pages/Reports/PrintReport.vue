<script setup>
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    regionFilter: String,
    printDate: String,
    summary: Object,
    detailedSites: Array,
});

const triggerPrint = () => {
    window.print();
};
</script>

<template>
    <Head title="Laporan Resmi Kebutuhan SDM" />

    <div class="min-h-screen bg-slate-100 py-8 px-4 sm:px-6 lg:px-8 print:bg-white print:py-0 print:px-0">
        <!-- Action Bar (Hidden on Print) -->
        <div class="max-w-5xl mx-auto mb-6 flex items-center justify-between bg-white p-4 rounded-xl shadow print:hidden">
            <div class="flex items-center gap-3">
                <Link :href="route('dashboard')" class="inline-flex items-center gap-2 px-3 py-1.5 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                    &larr; Kembali
                </Link>
                <span class="text-sm font-semibold text-slate-600">Pratinjau Cetak Laporan Resmi</span>
            </div>
            <button @click="triggerPrint" class="inline-flex items-center gap-2 px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-lg shadow transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak Laporan Sekarang
            </button>
        </div>

        <!-- Official Report Document Page -->
        <div class="max-w-5xl mx-auto bg-white p-10 shadow-lg rounded-xl print:shadow-none print:p-0 print:rounded-none text-slate-800">
            
            <!-- Kop Surat Resmi -->
            <div class="border-b-4 border-double border-slate-800 pb-6 mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-extrabold tracking-tight uppercase text-slate-900">PT PELABUHAN INDONESIA (PERSERO)</h1>
                    <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wide">SUB-HOLDING PELINDO TERMINAL PETIKEMAS</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Sistem Pengendalian dan Perencanaan SDM Operasional Alat Berat (SIGAP)</p>
                </div>
                <div class="text-right">
                    <span class="inline-block px-3 py-1 bg-slate-900 text-white font-mono font-bold text-xs rounded">DOKUMEN RESMI</span>
                    <p class="text-xs text-slate-500 mt-1">Tanggal Cetak: <strong class="text-slate-800">{{ printDate }}</strong></p>
                </div>
            </div>

            <!-- Title & Subtitle -->
            <div class="text-center my-6">
                <h3 class="text-lg font-bold uppercase underline tracking-wider text-slate-900">LAPORAN REKAPITULASI & EVALUASI KEBUTUHAN SDM</h3>
                <p class="text-xs font-semibold text-slate-600 mt-1 uppercase">
                    CAKUPAN WILAYAH: {{ regionFilter === 'ALL' ? 'NASIONAL (SELURUH INDONESIA)' : 'WILAYAH ' + regionFilter }}
                </p>
            </div>

            <!-- Executive Summary Table -->
            <div class="mb-8 border border-slate-300 rounded-lg overflow-hidden bg-slate-50 p-4 print:bg-white print:border-slate-400">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3 border-b pb-1">Ringkasan Eksekutif</h4>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div class="border-r border-slate-200 pr-4">
                        <span class="text-xs text-slate-500 block">Total Terminal Evaluasi</span>
                        <span class="text-2xl font-extrabold text-slate-800">{{ summary.totalSites }} <small class="text-xs font-normal">Site</small></span>
                    </div>
                    <div class="border-r border-slate-200 pr-4">
                        <span class="text-xs text-slate-500 block">Total Teknisi (Eksisting / Kebutuhan)</span>
                        <span class="text-2xl font-extrabold text-slate-800">{{ summary.techExisting }}</span>
                        <span class="text-sm font-semibold text-slate-500"> / {{ summary.techNeeded }} <small class="text-xs font-normal">Orang</small></span>
                    </div>
                    <div>
                        <span class="text-xs text-slate-500 block">Total Staf Non-Teknis (Eksisting / Kebutuhan)</span>
                        <span class="text-2xl font-extrabold text-slate-800">{{ summary.nonTechExisting }}</span>
                        <span class="text-sm font-semibold text-slate-500"> / {{ summary.nonTechNeeded }} <small class="text-xs font-normal">Orang</small></span>
                    </div>
                </div>
            </div>

            <!-- Detailed Sites Section -->
            <div class="space-y-8">
                <div v-for="(item, idx) in detailedSites" :key="item.site.id" class="border border-slate-300 rounded-lg p-5 print:break-inside-avoid print:border-slate-400">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-3 mb-4">
                        <div>
                            <span class="text-xs font-bold text-blue-600 uppercase tracking-widest">Site #{{ idx + 1 }}</span>
                            <h4 class="text-md font-extrabold text-slate-900">{{ item.site.name }}</h4>
                        </div>
                        <div class="text-right text-xs">
                            <span class="font-semibold bg-slate-100 px-2 py-0.5 rounded border border-slate-300 mr-2">Wilayah {{ item.site.region }}</span>
                            <span class="font-bold bg-blue-50 text-blue-800 px-2 py-0.5 rounded border border-blue-200">{{ item.site.site_class }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
                        <!-- Pilar Teknisi -->
                        <div>
                            <h5 class="font-bold text-slate-700 underline mb-2 uppercase">1. Rincian 3 Pilar Pembentuk Teknisi:</h5>
                            <table class="w-full border-collapse border border-slate-200 text-left">
                                <tbody class="divide-y divide-slate-200">
                                    <tr>
                                        <th class="py-1.5 px-2 bg-slate-50 font-medium">Pilar 1: Kesiagaaan Baseline ({{ item.breakdown.highest_baseline_category }})</th>
                                        <td class="py-1.5 px-2 font-bold text-right">{{ item.breakdown.highest_baseline }} Orang</td>
                                    </tr>
                                    <tr>
                                        <th class="py-1.5 px-2 bg-slate-50 font-medium">Pilar 2: Jam Beban Pemeliharaan Rutin</th>
                                        <td class="py-1.5 px-2 font-bold text-right">{{ item.breakdown.additional_tech }} Orang <span class="text-[10px] font-normal text-slate-400">({{ item.breakdown.total_maintenance_hours }} Jam)</span></td>
                                    </tr>
                                    <tr>
                                        <th class="py-1.5 px-2 bg-slate-50 font-medium">Pilar 3: Cadangan Penanganan Gangguan</th>
                                        <td class="py-1.5 px-2 font-bold text-right">{{ item.breakdown.breakdown_tech }} Orang <span class="text-[10px] font-normal text-slate-400">({{ item.breakdown.total_breakdown_hours }} Jam)</span></td>
                                    </tr>
                                    <tr class="bg-blue-50/50 font-extrabold border-t-2 border-slate-300">
                                        <th class="py-2 px-2 text-slate-900">Total Rekomendasi Kebutuhan Teknisi</th>
                                        <td class="py-2 px-2 text-right text-blue-700 text-sm">{{ Math.round(item.tech_needed * 100) / 100 }} Orang</td>
                                    </tr>
                                    <tr>
                                        <th class="py-1.5 px-2 text-slate-600">Personel Eksisting Saat Ini</th>
                                        <td class="py-1.5 px-2 text-right font-bold">{{ item.tech_existing }} Orang</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Formasi Non-Teknis -->
                        <div>
                            <h5 class="font-bold text-slate-700 underline mb-2 uppercase">2. Matriks Formasi Staf Non-Teknis:</h5>
                            <table class="w-full border-collapse border border-slate-200 text-left">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-200 font-bold">
                                        <th class="py-1.5 px-2">Jabatan / Formasi</th>
                                        <th class="py-1.5 px-2 text-right">Rekomendasi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    <tr v-for="pos in item.breakdown.non_technical_positions" :key="pos.title">
                                        <td class="py-1 px-2">{{ pos.title }}</td>
                                        <td class="py-1 px-2 font-bold text-right">{{ pos.quantity }} Orang</td>
                                    </tr>
                                    <tr v-if="!item.breakdown.non_technical_positions || item.breakdown.non_technical_positions.length === 0">
                                        <td colspan="2" class="py-2 px-2 text-center italic text-slate-400">Belum ada matriks untuk kelas ini.</td>
                                    </tr>
                                    <tr class="bg-amber-50/50 font-extrabold border-t-2 border-slate-300">
                                        <th class="py-2 px-2 text-slate-900">Total Rekomendasi Non-Teknis</th>
                                        <td class="py-2 px-2 text-right text-amber-700 text-sm">{{ item.non_tech_needed }} Orang</td>
                                    </tr>
                                    <tr>
                                        <th class="py-1.5 px-2 text-slate-600">Staf Eksisting Saat Ini</th>
                                        <td class="py-1.5 px-2 text-right font-bold">{{ item.non_tech_existing }} Orang</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kotak Tanda Tangan Persetujuan (Approval Signature Box) -->
            <div class="mt-16 pt-8 border-t-2 border-slate-800 print:break-inside-avoid">
                <h4 class="text-xs font-bold uppercase tracking-wider text-center text-slate-600 mb-8">LEMBAR PERSETJUAN DAN EVALUASI SDM OPERASIONAL</h4>
                
                <div class="grid grid-cols-3 gap-8 text-center text-xs">
                    <div>
                        <p class="font-semibold text-slate-500 mb-16">Disiapkan Oleh,<br><strong class="text-slate-800">Planner SDM Operasional</strong></p>
                        <div class="border-b border-slate-800 w-4/5 mx-auto"></div>
                        <p class="mt-1 font-bold text-slate-800">( ............................................ )</p>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-500 mb-16">Diperiksa Oleh,<br><strong class="text-slate-800">Senior Manager Teknik / Fasilitas</strong></p>
                        <div class="border-b border-slate-800 w-4/5 mx-auto"></div>
                        <p class="mt-1 font-bold text-slate-800">( ............................................ )</p>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-500 mb-16">Disetujui Oleh,<br><strong class="text-slate-800">Direktur / General Manager</strong></p>
                        <div class="border-b border-slate-800 w-4/5 mx-auto"></div>
                        <p class="mt-1 font-bold text-slate-800">( ............................................ )</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<style scoped>
@media print {
    body {
        background-color: white !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
}
</style>
