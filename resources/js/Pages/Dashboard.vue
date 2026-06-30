<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import VueApexCharts from 'vue3-apexcharts';

const props = defineProps({
    regionFilter: String,
    regions: Array,
    summary: Object,
    chartData: Object,
    sitesTable: Array,
});

const selectedRegion = ref(props.regionFilter || 'ALL');

const onRegionChange = () => {
    router.get(route('dashboard'), { region: selectedRegion.value }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const printReportUrl = computed(() => {
    return route('reports.print', { region: selectedRegion.value });
});

const exportExcelUrl = computed(() => {
    return route('reports.excel', { region: selectedRegion.value });
});

// ApexCharts Options
const comparisonOptions = computed(() => ({
    chart: {
        type: 'bar',
        toolbar: { show: false },
        fontFamily: 'Inherit',
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '55%',
            borderRadius: 4,
        },
    },
    dataLabels: { enabled: false },
    stroke: { show: true, width: 2, colors: ['transparent'] },
    xaxis: {
        categories: props.chartData.comparison.labels,
        labels: { style: { colors: '#64748b', fontSize: '12px' } }
    },
    yaxis: {
        title: { text: 'Personel / Unit' }
    },
    fill: { opacity: 1 },
    colors: ['#00549A', '#00A3E0', '#0284c7', '#F97316', '#ea580c'],
    tooltip: {
        y: { formatter: (val) => `${val}` }
    }
}));

const compositionOptions = computed(() => ({
    chart: {
        type: 'donut',
        fontFamily: 'Inherit',
    },
    labels: props.chartData.composition.labels,
    colors: ['#00549A', '#00A3E0'],
    legend: { position: 'bottom' },
    plotOptions: {
        pie: {
            donut: {
                size: '70%',
                labels: {
                    show: true,
                    total: {
                        show: true,
                        label: 'Total Rekomendasi',
                        formatter: () => `${Math.round(props.summary.techNeeded + props.summary.nonTechNeeded)} Orang`
                    }
                }
            }
        }
    }
}));

const maintenanceOptions = computed(() => ({
    chart: {
        type: 'bar',
        stacked: true,
        toolbar: { show: false },
        fontFamily: 'Inherit',
    },
    plotOptions: {
        bar: {
            horizontal: true,
            borderRadius: 4,
        },
    },
    xaxis: {
        categories: props.chartData.maintenance.labels,
        title: { text: 'Jam Kerja per Tahun' }
    },
    colors: ['#00549A', '#F97316'],
    legend: { position: 'top', horizontalAlign: 'left' },
    fill: { opacity: 1 },
}));
</script>

<template>
    <Head title="Executive Command Center" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="font-bold text-2xl text-slate-800 leading-tight">Executive Command Center</h2>
                    <p class="text-sm text-slate-500">Pantau kesiapan, efektivitas beban kerja, dan kelayakan SDM terminal pelabuhan secara live.</p>
                </div>

                <!-- Action Bar & Filter -->
                <div class="bg-white p-2.5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-wrap items-center justify-between gap-4">
                    <!-- Sisi Kiri: Filter Wilayah -->
                    <div class="flex items-center gap-2 px-2 py-1">
                        <svg class="w-4 h-4 text-pelindo-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Wilayah:</span>
                        <select v-model="selectedRegion" @change="onRegionChange" class="text-sm font-bold border-none bg-transparent focus:ring-0 text-pelindo-blue py-0 pr-8 pl-1 cursor-pointer">
                            <option value="ALL">Semua Wilayah</option>
                            <option v-for="reg in regions" :key="reg" :value="reg">Wilayah {{ reg }}</option>
                        </select>
                    </div>

                    <!-- Garis Pembatas -->
                    <div class="border-l border-slate-200 h-6 hidden sm:block"></div>

                    <!-- Sisi Kanan: Action Buttons -->
                    <div class="flex flex-wrap items-center gap-2.5">
                        <a :href="printReportUrl" target="_blank" class="h-10 px-4 inline-flex items-center gap-2 bg-gradient-to-r from-pelindo-blue to-[#003B6F] border border-pelindo-cyan/20 hover:opacity-90 text-white text-xs font-bold rounded-xl shadow-md transition uppercase tracking-wider">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Cetak Laporan Resmi (PDF)
                        </a>

                        <a :href="exportExcelUrl" target="_blank" class="h-10 px-4 inline-flex items-center gap-2 bg-white border border-slate-300 text-slate-700 hover:border-pelindo-cyan hover:bg-pelindo-cyan/5 text-xs font-bold rounded-xl shadow-sm transition uppercase tracking-wider">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Export Excel (.xlsx)
                        </a>
                    </div>
                </div>
            </div>
        </template>

        <div class="py-8 bg-slate-50 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                
                <!-- Row 1: KPI Summary Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Card 1: Total Sites -->
                    <div class="bg-white rounded-xl border border-slate-200/80 p-6 shadow-sm hover:shadow transition relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/5 rounded-bl-full transition group-hover:scale-110"></div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Terminal</span>
                            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-baseline gap-2">
                            <span class="text-3xl font-extrabold text-slate-800">{{ summary.totalSites }}</span>
                            <span class="text-xs font-medium text-slate-500">Pelabuhan Terdata</span>
                        </div>
                        <div class="mt-3 pt-3 border-t border-slate-100 flex flex-wrap gap-1">
                            <span v-for="(cnt, cls) in summary.sitesByClass" :key="cls" v-show="cnt > 0" class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-600">
                                {{ cls }}: {{ cnt }}
                            </span>
                        </div>
                    </div>

                    <!-- Card 2: Total Equipments -->
                    <div class="bg-white rounded-xl border border-slate-200/80 p-6 shadow-sm hover:shadow transition relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-500/5 rounded-bl-full transition group-hover:scale-110"></div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Aset Alat Beroperasi</span>
                            <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-baseline gap-2">
                            <span class="text-3xl font-extrabold text-slate-800">{{ summary.totalEquipments }}</span>
                            <span class="text-xs font-medium text-slate-500">Unit Alat Berat</span>
                        </div>
                        <div class="mt-3 pt-3 border-t border-slate-100">
                            <span class="text-xs text-slate-500">Diakumulasi dari seluruh terminal</span>
                        </div>
                    </div>

                    <!-- Card 3: Technical Staff -->
                    <div class="bg-white rounded-xl border border-slate-200/80 p-6 shadow-sm hover:shadow transition relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-500/5 rounded-bl-full transition group-hover:scale-110"></div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kesiapan Teknisi</span>
                            <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-baseline gap-2">
                            <span class="text-3xl font-extrabold text-slate-800">{{ summary.techExisting }}</span>
                            <span class="text-sm font-semibold text-slate-400">/ {{ summary.techNeeded }}</span>
                            <span class="text-xs font-medium text-slate-500">Orang</span>
                        </div>
                        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs text-slate-500">Status Gap:</span>
                            <span v-if="summary.techExisting < summary.techNeeded" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-rose-100 text-rose-700">
                                Kurang {{ Math.ceil(summary.techNeeded - summary.techExisting) }} Orang
                            </span>
                            <span v-else class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-emerald-100 text-emerald-700">
                                Surplus / Terpenuhi
                            </span>
                        </div>
                    </div>

                    <!-- Card 4: Non-Technical Staff -->
                    <div class="bg-white rounded-xl border border-slate-200/80 p-6 shadow-sm hover:shadow transition relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-amber-500/5 rounded-bl-full transition group-hover:scale-110"></div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Staf Non-Teknis</span>
                            <div class="p-2 bg-amber-50 text-amber-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-baseline gap-2">
                            <span class="text-3xl font-extrabold text-slate-800">{{ summary.nonTechExisting }}</span>
                            <span class="text-sm font-semibold text-slate-400">/ {{ summary.nonTechNeeded }}</span>
                            <span class="text-xs font-medium text-slate-500">Orang</span>
                        </div>
                        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs text-slate-500">Status Gap:</span>
                            <span v-if="summary.nonTechExisting < summary.nonTechNeeded" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-rose-100 text-rose-700">
                                Kurang {{ summary.nonTechNeeded - summary.nonTechExisting }} Orang
                            </span>
                            <span v-else class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-emerald-100 text-emerald-700">
                                Surplus / Terpenuhi
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Row 2: Charts -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left: Grouped Bar Chart -->
                    <div class="bg-white rounded-xl border border-slate-200/80 p-6 shadow-sm lg:col-span-2">
                        <h3 class="font-bold text-lg text-slate-800 mb-1">Komparasi Beban Alat vs Personel per Terminal</h3>
                        <p class="text-xs text-slate-500 mb-6">Perbandingan jumlah alat berat dengan angka eksisting dan kebutuhan rekomendasi SDM.</p>
                        
                        <div class="min-h-[350px]">
                            <VueApexCharts type="bar" height="350" :options="comparisonOptions" :series="props.chartData.comparison.series || []" />
                        </div>
                    </div>

                    <!-- Right: Donut Chart -->
                    <div class="bg-white rounded-xl border border-slate-200/80 p-6 shadow-sm flex flex-col justify-between">
                        <div>
                            <h3 class="font-bold text-lg text-slate-800 mb-1">Komposisi SDM Nasional</h3>
                            <p class="text-xs text-slate-500 mb-6">Proporsi kebutuhan tenaga teknis lapangan dibanding staf manajerial.</p>
                        </div>
                        
                        <div class="my-auto">
                            <VueApexCharts type="donut" height="300" :options="compositionOptions" :series="props.chartData.composition.series || []" />
                        </div>
                    </div>
                </div>

                <!-- Row 3: Stacked Bar Chart -->
                <div class="bg-white rounded-xl border border-slate-200/80 p-6 shadow-sm">
                    <h3 class="font-bold text-lg text-slate-800 mb-1">Analisis Proporsi Jam Kerja Pemeliharaan per Terminal</h3>
                    <p class="text-xs text-slate-500 mb-6">Alokasi jam kerja pemeliharaan rutin (*Preventive Maintenance*) dibanding cadangan penanganan gangguan (*Breakdown Allowance*).</p>
                    
                    <div class="min-h-[300px]">
                        <VueApexCharts type="bar" height="300" :options="maintenanceOptions" :series="props.chartData.maintenance.series || []" />
                    </div>
                </div>

                <!-- Row 4: Executive Table -->
                <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-200/80 flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-lg text-slate-800">Status Kelayakan SDM per Terminal</h3>
                            <p class="text-xs text-slate-500">Matriks cepat tanggap untuk evaluasi kesiapan SDM di lapangan.</p>
                        </div>
                        <span class="text-xs font-semibold px-3 py-1 bg-slate-100 text-slate-600 rounded-full">
                            {{ sitesTable.length }} Terminal Terpilih
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-800 text-white text-[11px] font-bold uppercase tracking-wider">
                                    <th class="py-3 px-4">Nama Terminal</th>
                                    <th class="py-3 px-4">Wilayah</th>
                                    <th class="py-3 px-4">Kelas</th>
                                    <th class="py-3 px-4 text-center">Unit Alat</th>
                                    <th class="py-3 px-4 text-center">Teknisi (Eks / Req)</th>
                                    <th class="py-3 px-4 text-center">Non-Teknis (Eks / Req)</th>
                                    <th class="py-3 px-4 text-center">Health Badge</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                <tr v-for="site in sitesTable" :key="site.id" class="hover:bg-slate-50/70 transition">
                                    <td class="py-3 px-4 font-semibold text-slate-800">{{ site.name }}</td>
                                    <td class="py-3 px-4 text-slate-600">Wilayah {{ site.region }}</td>
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200/60">
                                            {{ site.site_class }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-center font-bold text-slate-700">{{ site.equip_count }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="font-bold text-slate-800">{{ site.tech_existing }}</span>
                                        <span class="text-slate-400 mx-1">/</span>
                                        <span class="font-semibold text-blue-600">{{ Math.round(site.tech_needed * 100) / 100 }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="font-bold text-slate-800">{{ site.non_tech_existing }}</span>
                                        <span class="text-slate-400 mx-1">/</span>
                                        <span class="font-semibold text-blue-600">{{ site.non_tech_needed }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span v-if="site.health_color === 'green'" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                            {{ site.health_status }}
                                        </span>
                                        <span v-else-if="site.health_color === 'red'" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5 animate-pulse"></span>
                                            {{ site.health_status }}
                                        </span>
                                        <span v-else class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                            {{ site.health_status }}
                                        </span>
                                    </td>
                                </tr>
                                <tr v-if="sitesTable.length === 0">
                                    <td colspan="7" class="py-8 text-center text-slate-400 italic">Tidak ada data terminal pada filter wilayah ini.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
