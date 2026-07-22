<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';
import Modal from '@/Components/Modal.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';

const props = defineProps({
    sites: Array,
    equipmentTypes: Array,
});

const expandedRows = ref([]);
const viewMode = ref('list');
const expandedCards = ref([]);

const toggleRow = (id) => {
    if (expandedRows.value.includes(id)) {
        expandedRows.value = expandedRows.value.filter(rowId => rowId !== id);
    } else {
        expandedRows.value.push(id);
    }
};

const toggleCardAccordion = (id) => {
    if (expandedCards.value.includes(id)) {
        expandedCards.value = expandedCards.value.filter(cardId => cardId !== id);
    } else {
        expandedCards.value.push(id);
    }
};

onMounted(() => {
    const savedMode = localStorage.getItem('sigap_site_view_mode');
    if (savedMode === 'list' || savedMode === 'card') {
        viewMode.value = savedMode;
    }
});

watch(viewMode, (newMode) => {
    localStorage.setItem('sigap_site_view_mode', newMode);
});

const showModal = ref(false);
const showImportModal = ref(false);
const isEditing = ref(false);

const form = useForm({
    id: null,
    name: '',
    region: '',
    work_scheme: 'Non-Shift',
    existing_technical_staff: 0,
    existing_non_technical_staff: 0,
    equipments: [],
});

const importForm = useForm({
    file: null,
});

const openCreateModal = () => {
    isEditing.value = false;
    form.reset();
    form.clearErrors();
    form.work_scheme = 'Non-Shift';
    form.equipments = [];
    showModal.value = true;
};

const openEditModal = (site) => {
    isEditing.value = true;
    form.clearErrors();
    form.id = site.id;
    form.name = site.name;
    form.region = site.region;
    form.work_scheme = site.work_scheme || 'Non-Shift';
    form.existing_technical_staff = site.existing_technical_staff;
    form.existing_non_technical_staff = site.existing_non_technical_staff;
    form.equipments = site.equipments.map(eq => ({
        id: eq.id,
        equipment_type_id: eq.equipment_type_id,
        quantity: eq.quantity,
        utilization_rate: eq.utilization_rate ?? 0,
    }));
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    form.reset();
};

const addEquipmentRow = () => {
    form.equipments.push({
        id: null,
        equipment_type_id: '',
        quantity: 1,
        utilization_rate: 0,
    });
};

const removeEquipmentRow = (index) => {
    form.equipments.splice(index, 1);
};

const saveSite = () => {
    if (isEditing.value) {
        form.put(route('sites.update', form.id), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('sites.store'), {
            onSuccess: () => closeModal(),
        });
    }
};

const deleteSite = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus site ini beserta seluruh alat di dalamnya?')) {
        form.delete(route('sites.destroy', id));
    }
};

const openImportModal = () => {
    importForm.reset();
    importForm.clearErrors();
    showImportModal.value = true;
};

const closeImportModal = () => {
    showImportModal.value = false;
    importForm.reset();
};

const submitImport = () => {
    importForm.post(route('sites.import'), {
        onSuccess: () => closeImportModal(),
    });
};
</script>

<template>
    <Head title="Manajemen Site" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Site & Alat</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div v-if="$page.props.flash.success" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline" v-html="$page.props.flash.success"></span>
                </div>
                <div v-if="$page.props.flash.error" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Peringatan Import! </strong>
                    <span class="block sm:inline" v-html="$page.props.flash.error"></span>
                </div>

                <div class="bg-white p-3 rounded-2xl border border-slate-200/80 shadow-sm flex flex-wrap items-center justify-between gap-4 mb-6">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <a :href="route('sites.download-template')" class="h-10 px-4 inline-flex items-center gap-2 bg-white border border-slate-300 rounded-xl font-bold text-xs text-slate-700 uppercase tracking-wider shadow-sm hover:bg-pelindo-cyan/5 hover:border-pelindo-cyan hover:text-pelindo-blue transition duration-150">
                            <svg class="w-4 h-4 text-pelindo-blue shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>Download Template</span>
                        </a>
                        <button @click="openImportModal" type="button" class="h-10 px-4 inline-flex items-center gap-2 bg-white border border-slate-300 rounded-xl font-bold text-xs text-slate-700 uppercase tracking-wider shadow-sm hover:bg-pelindo-cyan/5 hover:border-pelindo-cyan hover:text-pelindo-blue transition duration-150">
                            <svg class="w-4 h-4 text-pelindo-cyan shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            <span>Import Excel</span>
                        </button>
                        <a :href="route('sites.export')" class="h-10 px-4 inline-flex items-center gap-2 bg-white border border-slate-300 rounded-xl font-bold text-xs text-slate-700 uppercase tracking-wider shadow-sm hover:bg-pelindo-cyan/5 hover:border-pelindo-cyan hover:text-pelindo-blue transition duration-150">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Export Excel</span>
                        </a>
                    </div>
                    <div class="flex items-center gap-3">
                        <!-- View Mode Switcher -->
                        <div class="inline-flex bg-slate-100 p-1 rounded-xl border border-slate-200 shadow-inner">
                            <button 
                                @click="viewMode = 'list'" 
                                type="button"
                                :class="viewMode === 'list' ? 'bg-white text-pelindo-blue shadow-sm font-bold' : 'text-slate-500 hover:text-slate-800 font-medium'"
                                class="px-3 py-1.5 rounded-lg text-xs flex items-center gap-1.5 transition-all"
                                title="Tampilan Tabel (List)"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                <span class="hidden sm:inline">List</span>
                            </button>
                            <button 
                                @click="viewMode = 'card'" 
                                type="button"
                                :class="viewMode === 'card' ? 'bg-white text-pelindo-blue shadow-sm font-bold' : 'text-slate-500 hover:text-slate-800 font-medium'"
                                class="px-3 py-1.5 rounded-lg text-xs flex items-center gap-1.5 transition-all"
                                title="Tampilan Kartu (Grid)"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                <span class="hidden sm:inline">Card</span>
                            </button>
                        </div>

                        <button @click="openCreateModal" type="button" class="h-10 px-5 inline-flex items-center gap-2 bg-gradient-to-r from-pelindo-blue to-[#003B6F] border border-pelindo-cyan/20 hover:opacity-90 text-white text-xs font-bold rounded-xl shadow-md transition uppercase tracking-wider">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>Tambah Site</span>
                        </button>
                    </div>
                </div>

                <div v-if="viewMode === 'list'" class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-200/80 p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-slate-800 text-white">
                                <tr>
                                    <th class="w-8 pb-4 pt-6 px-6"></th>
                                    <th class="pb-4 pt-6 px-6 text-left text-xs font-bold uppercase tracking-wider">Nama Site</th>
                                    <th class="pb-4 pt-6 px-6 text-left text-xs font-bold uppercase tracking-wider">Wilayah</th>
                                    <th class="pb-4 pt-6 px-4 text-center text-xs font-bold uppercase tracking-wider">Skema</th>
                                    <th class="pb-4 pt-6 px-6 text-center text-xs font-bold uppercase tracking-wider">Kelas</th>
                                    <th class="pb-4 pt-6 px-6 text-center text-xs font-bold uppercase tracking-wider">Total Bobot</th>
                                    <th class="pb-4 pt-6 px-6 text-center text-xs font-bold uppercase tracking-wider">Jml Alat</th>
                                    <th class="pb-4 pt-6 px-6 text-center text-xs font-bold uppercase tracking-wider">Teknisi (Eks / Butuh)</th>
                                    <th class="pb-4 pt-6 px-6 text-center text-xs font-bold uppercase tracking-wider">Non-Teknisi (Eks / Butuh)</th>
                                    <th class="pb-4 pt-6 px-6 text-center text-xs font-bold uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <template v-for="site in sites" :key="site.id">
                                    <tr class="hover:bg-slate-50/80 cursor-pointer transition">
                                        <td class="px-4 py-4 whitespace-nowrap text-center" @click="toggleRow(site.id)">
                                            <svg v-if="expandedRows.includes(site.id)" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900" @click="toggleRow(site.id)">{{ site.name }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500" @click="toggleRow(site.id)">{{ site.region }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-xs text-center" @click="toggleRow(site.id)">
                                            <span :class="site.work_scheme === 'Shift' ? 'bg-pelindo-blue/10 text-pelindo-blue border border-pelindo-blue/20' : 'bg-pelindo-cyan/10 text-[#007baf] border border-pelindo-cyan/30'" class="px-2 py-1 rounded font-bold">{{ site.work_scheme }}</span>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center font-bold text-gray-900" @click="toggleRow(site.id)">{{ site.site_class }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center font-semibold text-pelindo-blue" @click="toggleRow(site.id)">{{ site.total_weight }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center text-gray-500" @click="toggleRow(site.id)">{{ site.jumlah_alat }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center text-gray-900 font-semibold" @click="toggleRow(site.id)">{{ site.existing_technical_staff }} <span class="text-gray-400 font-normal">/</span> {{ site.technical_staff_needed }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center text-gray-900 font-semibold" @click="toggleRow(site.id)">{{ site.existing_non_technical_staff }} <span class="text-gray-400 font-normal">/</span> {{ site.non_technical_staff_needed }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-center font-medium">
                                            <div class="inline-flex items-center justify-center gap-2">
                                                <button @click.stop="openEditModal(site)" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-pelindo-blue text-pelindo-blue hover:text-white rounded-xl text-xs font-bold shadow-sm transition duration-150" title="Edit Site">
                                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    <span>Edit</span>
                                                </button>
                                                <button @click.stop="deleteSite(site.id)" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 hover:bg-red-600 text-red-600 hover:text-white rounded-xl text-xs font-bold shadow-sm transition duration-150" title="Hapus Site">
                                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    <span>Delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Child Row (Equipments) -->
                                    <tr v-if="expandedRows.includes(site.id)" class="bg-gray-50">
                                        <td colspan="10" class="px-8 py-4">
                                            <div v-if="site.equipments.length === 0" class="text-sm text-gray-500 italic">Belum ada alat.</div>
                                            <table v-else class="min-w-full divide-y divide-gray-200 border">
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kode Alat</th>
                                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jenis Alat</th>
                                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Bobot</th>
                                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Jumlah Alat</th>
                                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Utilisasi / Thn</th>
                                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Total Bobot</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    <tr v-for="eq in site.equipments" :key="eq.id">
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 font-medium">{{ eq.code }}</td>
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">{{ eq.equipment_type_name }}</td>
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-center text-gray-500">{{ eq.weight }}</td>
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-center text-gray-500">{{ eq.quantity }}</td>
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-center font-semibold text-indigo-900">{{ eq.utilization_rate || 0 }}%</td>
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-center text-gray-900 font-semibold">{{ eq.weight * eq.quantity }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </template>
                                <tr v-if="sites.length === 0">
                                    <td colspan="10" class="px-4 py-4 text-center text-sm text-gray-500">Tidak ada data site.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Card View -->
                <div v-else-if="viewMode === 'card'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-if="sites.length === 0" class="col-span-full bg-white p-8 rounded-2xl border border-slate-200/80 text-center text-gray-500">
                        Tidak ada data site.
                    </div>
                    <div v-for="site in sites" :key="site.id" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between overflow-hidden">
                        
                        <!-- Bagian Atas Kartu (Header & Body) -->
                        <div class="p-5">
                            <!-- Header Kartu -->
                            <div class="flex items-start justify-between gap-2 mb-4">
                                <div>
                                    <h3 class="font-black text-indigo-950 text-base leading-snug flex items-center gap-1.5">
                                        <span>🏗️</span>
                                        <span>{{ site.name }}</span>
                                    </h3>
                                    <p class="text-xs text-slate-500 font-medium mt-0.5">{{ site.region || 'Wilayah tidak set' }}</p>
                                </div>
                                <div class="flex flex-col items-end gap-1 shrink-0">
                                    <span class="px-2.5 py-1 bg-gradient-to-r from-pelindo-blue to-indigo-900 text-white font-black text-[11px] rounded-lg shadow-sm uppercase tracking-wider">
                                        Kelas {{ site.site_class || '-' }}
                                    </span>
                                    <span :class="site.work_scheme === 'Shift' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-slate-100 text-slate-700 border-slate-200'" class="px-2 py-0.5 rounded text-[10px] font-bold border">
                                        {{ site.work_scheme || 'Non-Shift' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Body Kartu: Mini Stat Grids (4 Kotak) -->
                            <div class="grid grid-cols-2 gap-2.5 my-4">
                                <!-- Total Bobot -->
                                <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100 flex flex-col justify-between">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Bobot</span>
                                    <span class="text-lg font-black text-pelindo-blue mt-1">{{ site.total_weight || 0 }}</span>
                                </div>
                                <!-- Jumlah Alat -->
                                <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100 flex flex-col justify-between">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Jumlah Alat</span>
                                    <span class="text-lg font-black text-indigo-950 mt-1">{{ site.jumlah_alat || 0 }} <span class="text-xs font-normal text-slate-500">Unit</span></span>
                                </div>
                                <!-- Teknisi -->
                                <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100 flex flex-col justify-between">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Teknisi (Eks/Butuh)</span>
                                    <div class="mt-1 flex items-baseline gap-1">
                                        <span :class="site.existing_technical_staff < site.technical_staff_needed ? 'text-rose-600 font-black' : 'text-slate-800 font-bold'" class="text-sm">
                                            {{ site.existing_technical_staff || 0 }}
                                        </span>
                                        <span class="text-xs text-slate-400">/</span>
                                        <span class="text-xs font-bold text-slate-600">{{ site.technical_staff_needed || 0 }}</span>
                                    </div>
                                </div>
                                <!-- Non-Teknisi -->
                                <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100 flex flex-col justify-between">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Non-Teknisi</span>
                                    <div class="mt-1 flex items-baseline gap-1">
                                        <span :class="site.existing_non_technical_staff < site.non_technical_staff_needed ? 'text-rose-600 font-black' : 'text-slate-800 font-bold'" class="text-sm">
                                            {{ site.existing_non_technical_staff || 0 }}
                                        </span>
                                        <span class="text-xs text-slate-400">/</span>
                                        <span class="text-xs font-bold text-slate-600">{{ site.non_technical_staff_needed || 0 }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Accordion Toggle Rincian Alat -->
                            <div class="mt-4 pt-3 border-t border-slate-100">
                                <button 
                                    @click="toggleCardAccordion(site.id)" 
                                    type="button" 
                                    class="w-full py-1.5 px-3 bg-indigo-50/60 hover:bg-indigo-100/80 text-indigo-900 rounded-lg text-xs font-bold flex items-center justify-between transition-colors"
                                >
                                    <span>👁️ Rincian Alat ({{ site.equipments ? site.equipments.length : 0 }} Jenis)</span>
                                    <svg class="w-4 h-4 transform transition-transform duration-200" :class="{ 'rotate-180': expandedCards.includes(site.id) }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>

                                <!-- Daftar Alat (Muncul saat accordion dibuka) -->
                                <div v-if="expandedCards.includes(site.id)" class="mt-2.5 space-y-1.5 max-h-48 overflow-y-auto pr-1">
                                    <div v-if="!site.equipments || site.equipments.length === 0" class="text-center py-2 text-xs italic text-slate-400">
                                        Tidak ada data alat.
                                    </div>
                                    <div 
                                        v-for="eq in site.equipments" 
                                        :key="eq.id" 
                                        class="flex items-center justify-between text-xs py-1.5 px-2.5 bg-slate-50 rounded border border-slate-100"
                                    >
                                        <div class="truncate pr-2">
                                            <span class="font-black text-indigo-900 mr-1">[{{ eq.code || '-' }}]</span>
                                            <span class="text-slate-700 font-medium">{{ eq.equipment_type_name || 'Alat tidak diketahui' }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5 shrink-0">
                                            <span class="font-bold bg-white px-2 py-0.5 rounded border border-slate-200 text-indigo-950 shadow-sm">{{ eq.quantity }} Unit</span>
                                            <span class="font-bold bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100 text-indigo-900 shadow-sm" title="Utilisasi per Tahun">{{ eq.utilization_rate || 0 }}%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bagian Bawah Kartu (Footer & Aksi) -->
                        <div class="px-5 py-3 bg-slate-50/80 border-t border-slate-100 flex items-center justify-end gap-2">
                            <button @click="openEditModal(site)" type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-pelindo-blue text-pelindo-blue hover:text-white rounded-xl text-xs font-bold shadow-sm transition duration-150" title="Edit Site">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                <span>Edit</span>
                            </button>
                            <button @click="deleteSite(site.id)" type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 hover:bg-red-600 text-red-600 hover:text-white rounded-xl text-xs font-bold shadow-sm transition duration-150" title="Hapus Site">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                <span>Hapus</span>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <Modal :show="showModal" @close="closeModal" maxWidth="5xl">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    {{ isEditing ? 'Edit Site' : 'Tambah Site' }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <InputLabel for="name" value="Nama Site" />
                        <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" />
                        <InputError :message="form.errors.name" class="mt-2" />
                    </div>
                    <div>
                        <InputLabel for="region" value="Wilayah" />
                        <TextInput id="region" v-model="form.region" type="text" class="mt-1 block w-full" />
                        <InputError :message="form.errors.region" class="mt-2" />
                    </div>
                    <div class="md:col-span-2">
                        <InputLabel for="work_scheme" value="Skema Kerja Teknisi Pemeliharaan" />
                        <select id="work_scheme" v-model="form.work_scheme" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full font-bold">
                            <option value="Non-Shift">Teknisi Non-Shift (Normal: ~1186 Jam Produktif / Tahun)</option>
                            <option value="Shift">Teknisi Shift (Khusus: ~1171 Jam Produktif / Tahun)</option>
                        </select>
                        <InputError :message="form.errors.work_scheme" class="mt-2" />
                    </div>
                    <div>
                        <InputLabel for="existing_technical_staff" value="Jumlah Teknisi Eksisting" />
                        <TextInput id="existing_technical_staff" v-model="form.existing_technical_staff" type="number" min="0" class="mt-1 block w-full" />
                        <InputError :message="form.errors.existing_technical_staff" class="mt-2" />
                    </div>
                    <div>
                        <InputLabel for="existing_non_technical_staff" value="Jumlah Non-Teknisi Eksisting" />
                        <TextInput id="existing_non_technical_staff" v-model="form.existing_non_technical_staff" type="number" min="0" class="mt-1 block w-full" />
                        <InputError :message="form.errors.existing_non_technical_staff" class="mt-2" />
                    </div>
                </div>

                <div class="border-t pt-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-md font-medium text-gray-900">Daftar Alat di Site</h3>
                        <PrimaryButton type="button" @click="addEquipmentRow" class="text-xs">
                            + Tambah Alat
                        </PrimaryButton>
                    </div>

                    <div v-for="(eq, index) in form.equipments" :key="index" class="bg-gray-50 p-4 mb-4 rounded border grid grid-cols-1 md:grid-cols-12 gap-4 items-start relative pr-10">
                        <button type="button" @click="removeEquipmentRow(index)" class="absolute top-2 right-2 text-red-500 hover:text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div class="md:col-span-6">
                            <InputLabel :for="'eq_type_'+index" value="Jenis Alat" />
                            <select :id="'eq_type_'+index" v-model="eq.equipment_type_id" class="mt-1 block w-full text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="" disabled>Pilih Jenis</option>
                                <option v-for="type in equipmentTypes" :key="type.id" :value="type.id">
                                    {{ type.name }} ({{ type.code }})
                                </option>
                            </select>
                            <InputError :message="form.errors[`equipments.${index}.equipment_type_id`]" class="mt-1" />
                        </div>

                        <div class="md:col-span-3">
                            <InputLabel :for="'eq_qty_'+index" value="Jumlah" />
                            <TextInput :id="'eq_qty_'+index" v-model="eq.quantity" type="number" min="1" class="mt-1 block w-full text-sm" />
                            <InputError :message="form.errors[`equipments.${index}.quantity`]" class="mt-1" />
                        </div>

                        <div class="md:col-span-3">
                            <InputLabel :for="'eq_util_'+index" value="Utilisasi / Thn (%)" />
                            <TextInput :id="'eq_util_'+index" v-model="eq.utilization_rate" type="number" min="0" max="100" step="0.01" placeholder="0" class="mt-1 block w-full text-sm" />
                            <InputError :message="form.errors[`equipments.${index}.utilization_rate`]" class="mt-1" />
                        </div>
                    </div>
                    <div v-if="form.equipments.length === 0" class="text-sm text-gray-500 italic text-center py-4">
                        Belum ada alat ditambahkan.
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">Batal</SecondaryButton>
                    <PrimaryButton @click="saveSite" class="ms-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Simpan
                    </PrimaryButton>
                </div>
            </div>
        </Modal>
        <Modal :show="showImportModal" @close="closeImportModal" maxWidth="md">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    Import Data Site (Excel)
                </h2>

                <div class="mb-4">
                    <InputLabel for="file" value="File Excel (.xlsx, .xls, .csv)" />
                    <input 
                        id="file" 
                        type="file" 
                        @change="e => importForm.file = e.target.files[0]" 
                        class="mt-1 block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-pelindo-cyan/10 file:text-pelindo-blue
                        hover:file:bg-pelindo-cyan/20"
                        accept=".xlsx,.xls,.csv"
                    />
                    <InputError :message="importForm.errors.file" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeImportModal">Batal</SecondaryButton>
                    <PrimaryButton @click="submitImport" class="ms-3" :class="{ 'opacity-25': importForm.processing }" :disabled="importForm.processing">
                        Upload & Proses
                    </PrimaryButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
