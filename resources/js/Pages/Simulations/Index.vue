<script setup>
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';
import axios from 'axios';

const props = defineProps({
    equipmentTypes: Array,
    existingSites: Array,
    targetAvailability: Number,
    shiftProductiveHours: Number,
});

const form = useForm({
    name: '',
    region: '',
    equipments: [
        { equipment_type_id: '', name: '', code: '', quantity: 1, existing_quantity: 0, searchQuery: '', isOpen: false }
    ],
});

const isCalculating = ref(false);
const simulationResult = ref(null);
const existingSiteData = ref(null);
const showTechBreakdown = ref(false);
const showNonTechBreakdown = ref(false);

// Smart Site Search State
const siteSearchQuery = ref('');
const isSiteDropdownOpen = ref(false);
const selectedExistingSite = ref(null);

const addEquipmentRow = () => {
    form.equipments.push({ equipment_type_id: '', name: '', code: '', quantity: 1, existing_quantity: 0, searchQuery: '', isOpen: false });
};

const removeEquipmentRow = (index) => {
    if (form.equipments.length > 1) {
        form.equipments.splice(index, 1);
    }
};

const formatEquipmentLabel = (type) => {
    return `[${type.code || '-'}] ${type.name} (Bobot: ${type.weight} | ⏱️ ${type.annual_hours || 0} Jam/Thn)`;
};

const getSelectedType = (eq) => {
    if (!eq.equipment_type_id) return null;
    return props.equipmentTypes.find(t => t.id === eq.equipment_type_id) || null;
};

const totalSimulationHours = computed(() => {
    let total = 0;
    form.equipments.forEach(eq => {
        const type = getSelectedType(eq);
        if (type && eq.quantity > 0) {
            total += (type.annual_hours || 0) * eq.quantity;
        }
    });
    return total.toFixed(2);
});

const totalSimulationEquipmentUnits = computed(() => {
    let total = 0;
    form.equipments.forEach(eq => {
        if (eq.equipment_type_id && eq.quantity > 0) {
            total += Number(eq.quantity);
        }
    });
    return total;
});

const totalSimulationBreakdownHours = computed(() => {
    const avail = props.targetAvailability !== undefined ? props.targetAvailability : 85;
    const rate = Math.max(0, (100 - avail) / 100);
    const hoursPerUnit = 24 * 365 * rate;
    return (hoursPerUnit * totalSimulationEquipmentUnits.value).toFixed(2);
});

const totalSimulationWeight = computed(() => {
    let total = 0;
    form.equipments.forEach(eq => {
        const type = getSelectedType(eq);
        if (type && eq.quantity > 0) {
            total += (type.weight || 0) * eq.quantity;
        }
    });
    return total;
});

const isExactMatch = (eq) => {
    if (!eq.equipment_type_id) return false;
    const type = getSelectedType(eq);
    if (!type) return false;
    return eq.searchQuery === formatEquipmentLabel(type);
};

const filterEquipmentTypes = (eq) => {
    if (!eq.searchQuery || isExactMatch(eq)) {
        return props.equipmentTypes;
    }
    const q = eq.searchQuery.toLowerCase();
    return props.equipmentTypes.filter(t => 
        t.name.toLowerCase().includes(q) || 
        (t.code && t.code.toLowerCase().includes(q))
    );
};

const selectEquipmentType = (eq, type) => {
    eq.equipment_type_id = type.id;
    eq.name = type.name;
    eq.code = type.code || '-';
    eq.searchQuery = formatEquipmentLabel(type);
    eq.isOpen = false;
};

const handleSearchInput = (eq) => {
    eq.isOpen = true;
    if (!eq.searchQuery.trim()) {
        eq.equipment_type_id = '';
        eq.name = '';
        eq.code = '';
    }
};

const closeDropdown = (eq) => {
    setTimeout(() => {
        eq.isOpen = false;
        if (!eq.searchQuery.trim()) {
            eq.equipment_type_id = '';
            eq.name = '';
            eq.code = '';
        } else if (eq.equipment_type_id) {
            const type = getSelectedType(eq);
            if (type) {
                eq.searchQuery = formatEquipmentLabel(type);
            }
        } else {
            eq.searchQuery = '';
        }
    }, 200);
};

const clearEquipmentType = (eq) => {
    eq.equipment_type_id = '';
    eq.name = '';
    eq.code = '';
    eq.searchQuery = '';
    eq.isOpen = false;
};

const filteredExistingSites = computed(() => {
    if (!siteSearchQuery.value || (selectedExistingSite.value && siteSearchQuery.value === selectedExistingSite.value.name)) {
        return props.existingSites;
    }
    const q = siteSearchQuery.value.toLowerCase();
    return props.existingSites.filter(s => 
        s.name.toLowerCase().includes(q) || 
        (s.region && s.region.toLowerCase().includes(q))
    );
});

const selectExistingSite = (site) => {
    selectedExistingSite.value = site;
    siteSearchQuery.value = site.name;
    form.name = site.name;
    form.region = site.region || '';
    isSiteDropdownOpen.value = false;

    // Overwrite form.equipments with existing site equipments
    if (site.equipments && site.equipments.length > 0) {
        form.equipments = site.equipments.map(eq => {
            const type = props.equipmentTypes.find(t => t.id === eq.equipment_type_id);
            return {
                equipment_type_id: eq.equipment_type_id,
                name: type ? type.name : '',
                code: type ? (type.code || '-') : '-',
                quantity: eq.quantity,
                existing_quantity: eq.quantity,
                searchQuery: type ? formatEquipmentLabel(type) : '',
                isOpen: false
            };
        });
    } else {
        form.equipments = [
            { equipment_type_id: '', name: '', code: '', quantity: 1, existing_quantity: 0, searchQuery: '', isOpen: false }
        ];
    }
};

const handleSiteSearchInput = () => {
    isSiteDropdownOpen.value = true;
    form.name = siteSearchQuery.value;
    
    const matched = props.existingSites.find(s => s.name.toLowerCase() === siteSearchQuery.value.trim().toLowerCase());
    if (matched) {
        selectedExistingSite.value = matched;
        if (!form.region) form.region = matched.region || '';
    } else {
        if (selectedExistingSite.value !== null) {
            form.equipments = [
                { equipment_type_id: '', name: '', code: '', quantity: 1, existing_quantity: 0, searchQuery: '', isOpen: false }
            ];
            form.region = '';
        }
        selectedExistingSite.value = null;
    }
};

const closeSiteDropdown = () => {
    setTimeout(() => {
        isSiteDropdownOpen.value = false;
        const matched = props.existingSites.find(s => s.name.toLowerCase() === siteSearchQuery.value.trim().toLowerCase());
        if (matched) {
            selectedExistingSite.value = matched;
            siteSearchQuery.value = matched.name;
            form.name = matched.name;
            if (!form.region) form.region = matched.region || '';
        } else {
            if (selectedExistingSite.value !== null) {
                form.equipments = [
                    { equipment_type_id: '', name: '', code: '', quantity: 1, existing_quantity: 0, searchQuery: '', isOpen: false }
                ];
                form.region = '';
            }
            selectedExistingSite.value = null;
            form.name = siteSearchQuery.value;
        }
    }, 200);
};

const clearSiteInput = () => {
    siteSearchQuery.value = '';
    form.name = '';
    form.region = '';
    selectedExistingSite.value = null;
    isSiteDropdownOpen.value = false;
    form.equipments = [
        { equipment_type_id: '', name: '', code: '', quantity: 1, existing_quantity: 0, searchQuery: '', isOpen: false }
    ];
};

const calculateSimulation = async () => {
    // Clear previous results
    simulationResult.value = null;
    existingSiteData.value = null;
    showTechBreakdown.value = false;
    showNonTechBreakdown.value = false;
    
    // Validate minimal fields manually or let server do it
    if (!form.name || form.equipments.length === 0) {
        alert("Nama Site dan minimal satu alat harus diisi.");
        return;
    }

    isCalculating.value = true;
    try {
        const response = await axios.post(route('simulasi.calculate'), {
            name: form.name,
            region: form.region,
            equipments: form.equipments
        });
        
        simulationResult.value = response.data.simulation;
        existingSiteData.value = response.data.existing_site;
    } catch (error) {
        if (error.response && error.response.data.errors) {
            alert("Terjadi kesalahan validasi. Cek kelengkapan form.");
        } else {
            alert("Terjadi kesalahan saat menghitung simulasi.");
        }
    } finally {
        isCalculating.value = false;
    }
};

const saveSimulation = () => {
    if (!simulationResult.value) return;

    const confirmed = confirm("Apakah Anda yakin ingin menyimpan hasil simulasi ini ke database?");
    if (confirmed) {
        form.post(route('simulasi.store'), {
            onSuccess: () => {
                // redirected to sites index automatically
            }
        });
    }
};

// Computed to format numbers
const formatNum = (num) => {
    return Number.isInteger(num) ? num : parseFloat(num).toFixed(2);
};

</script>

<template>
    <Head title="Simulasi Kalkulasi" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Simulasi Kalkulasi SDM</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
                <!-- Input Form -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">Form Simulasi Site</h2>
                            <p class="mt-1 text-sm text-gray-600">
                                Masukkan nama site dan rincian alat yang akan disimulasikan. Data tidak akan tersimpan ke database sebelum Anda memilih untuk menyimpannya.
                            </p>
                        </header>

                        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="relative">
                                <InputLabel for="name" value="Nama Site (Cari / Pilih atau Ketik Baru)" />
                                <div class="relative mt-1">
                                    <input 
                                        id="name" 
                                        type="text" 
                                        v-model="siteSearchQuery" 
                                        @focus="isSiteDropdownOpen = true" 
                                        @blur="closeSiteDropdown" 
                                        @input="handleSiteSearchInput"
                                        @keydown.esc="isSiteDropdownOpen = false; closeSiteDropdown()"
                                        placeholder="🔍 Ketik nama site eksisting atau ketik nama site baru..." 
                                        class="block w-full text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm pr-14 bg-white font-semibold text-indigo-950"
                                        autocomplete="off"
                                    />
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 space-x-1">
                                        <button v-if="siteSearchQuery || selectedExistingSite" type="button" @mousedown.prevent="clearSiteInput" class="text-gray-400 hover:text-red-500 p-1 rounded focus:outline-none" title="Hapus pilihan">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <button type="button" @mousedown.prevent="isSiteDropdownOpen = !isSiteDropdownOpen" class="text-gray-400 hover:text-gray-600 p-1 rounded focus:outline-none" title="Buka/Tutup Pilihan">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform transition-transform duration-200" :class="{ 'rotate-180': isSiteDropdownOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div v-if="isSiteDropdownOpen" class="absolute z-50 mt-1 w-full bg-white shadow-xl max-h-60 rounded-xl py-2 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm border border-slate-200">
                                        <div v-if="filteredExistingSites.length === 0" class="py-3 px-4 text-gray-500 text-xs italic">
                                            Site eksisting tidak ditemukan. Akan disimulasikan sebagai <strong class="text-indigo-600 font-bold">Site Baru</strong>.
                                        </div>
                                        <div 
                                            v-for="site in filteredExistingSites" 
                                            :key="site.id" 
                                            @mousedown.prevent="selectExistingSite(site)" 
                                            class="cursor-pointer select-none relative py-2.5 pl-4 pr-9 hover:bg-indigo-50/80 flex items-center justify-between border-b border-gray-100 last:border-none transition-colors"
                                        >
                                            <div class="truncate">
                                                <span class="font-black text-indigo-950 mr-1.5">🏗️ {{ site.name }}</span>
                                            </div>
                                            <div class="flex items-center space-x-1.5 ml-2 whitespace-nowrap">
                                                <span class="text-[11px] px-2.5 py-0.5 bg-blue-50 text-blue-700 rounded-full font-bold border border-blue-200/50">
                                                    Wilayah: {{ site.region || '-' }}
                                                </span>
                                                <span class="text-[11px] px-2.5 py-0.5 bg-slate-100 text-slate-700 rounded-full font-bold border border-slate-200">
                                                    Kelas: {{ site.site_class || '-' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Badge Status Site -->
                                <div class="mt-2.5 flex items-center">
                                    <span v-if="selectedExistingSite" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 shadow-sm">
                                        ✓ Site Eksisting Terpilih — Data Alat Berhasil Dimuat
                                    </span>
                                    <span v-else-if="siteSearchQuery.trim()" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-300 shadow-sm">
                                        + Simulasi Site Baru — Tanpa Perbandingan Eksisting
                                    </span>
                                </div>
                                <InputError :message="form.errors.name" class="mt-2" />
                            </div>
                            <div>
                                <InputLabel for="region" value="Wilayah" />
                                <TextInput id="region" v-model="form.region" type="text" class="mt-1 block w-full" placeholder="Contoh: BALI NUSA TENGGARA / SULAWESI..." />
                                <InputError :message="form.errors.region" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-6 border-t pt-4">
                            <div class="mb-4">
                                <h3 class="text-md font-medium text-gray-900">Daftar Alat (Simulasi)</h3>
                                <p class="text-xs text-gray-500">Kelola spesifikasi dan jumlah alat yang akan disimulasikan</p>
                            </div>

                            <div v-for="(eq, index) in form.equipments" :key="index" class="bg-gray-50 p-4 mb-4 rounded-xl border border-gray-200 grid grid-cols-1 md:grid-cols-12 gap-4 items-center relative shadow-sm hover:border-indigo-200 transition-colors">
                                <button v-if="form.equipments.length > 1" type="button" @click="removeEquipmentRow(index)" class="absolute top-3 right-3 text-red-500 hover:text-red-700 z-10 p-1 rounded-lg hover:bg-red-50 transition-colors" title="Hapus baris alat">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <div class="md:col-span-6 relative">
                                    <InputLabel :for="'eq_type_'+index" value="Jenis Alat (Cari / Pilih)" />
                                    <div class="relative mt-1">
                                        <input 
                                            :id="'eq_type_'+index" 
                                            type="text" 
                                            v-model="eq.searchQuery" 
                                            @focus="eq.isOpen = true" 
                                            @blur="closeDropdown(eq)" 
                                            @input="handleSearchInput(eq)"
                                            @keydown.esc="eq.isOpen = false; closeDropdown(eq)"
                                            placeholder="🔍 Ketik kode atau nama jenis alat untuk mencari..." 
                                            class="block w-full text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm pr-14 bg-white font-medium"
                                            autocomplete="off"
                                        />
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 space-x-1">
                                            <button v-if="eq.searchQuery || eq.equipment_type_id" type="button" @mousedown.prevent="clearEquipmentType(eq)" class="text-gray-400 hover:text-red-500 p-1 rounded focus:outline-none" title="Hapus pilihan">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <button type="button" @mousedown.prevent="eq.isOpen = !eq.isOpen" class="text-gray-400 hover:text-gray-600 p-1 rounded focus:outline-none" title="Buka/Tutup Pilihan">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform transition-transform duration-200" :class="{ 'rotate-180': eq.isOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div v-if="eq.isOpen" class="absolute z-50 mt-1 w-full bg-white shadow-xl max-h-60 rounded-xl py-2 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm border border-slate-200">
                                            <div v-if="filterEquipmentTypes(eq).length === 0" class="py-2 px-3 text-gray-500 text-xs italic">
                                                Jenis alat tidak ditemukan.
                                            </div>
                                            <div 
                                                v-for="type in filterEquipmentTypes(eq)" 
                                                :key="type.id" 
                                                @mousedown.prevent="selectEquipmentType(eq, type)" 
                                                class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-indigo-50 flex items-center justify-between border-b border-gray-50 last:border-none transition-colors"
                                            >
                                                <div class="truncate">
                                                    <span class="font-black text-indigo-900 mr-1.5">[{{ type.code || '-' }}]</span>
                                                    <span class="text-gray-800 font-medium">{{ type.name }}</span>
                                                </div>
                                                <div class="flex items-center space-x-1.5 ml-2 whitespace-nowrap">
                                                    <span class="text-xs px-2 py-0.5 bg-blue-50 text-blue-700 rounded-full font-medium">
                                                        ⏱️ {{ type.annual_hours || 0 }} Jam/Thn
                                                    </span>
                                                    <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full font-medium">
                                                        ⚖️ Bobot: {{ type.weight }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="md:col-span-3">
                                    <InputLabel value="Jml Alat Eksisting" />
                                    <div class="mt-1 flex items-center justify-between bg-slate-200/70 border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-700 shadow-inner font-bold select-none">
                                        <span>{{ (eq.existing_quantity !== undefined && eq.existing_quantity !== null && eq.existing_quantity > 0) ? eq.existing_quantity + ' Unit' : '-' }}</span>
                                        <span class="text-[10px] uppercase font-black bg-slate-300/80 text-slate-700 px-1.5 py-0.5 rounded">Eksisting</span>
                                    </div>
                                </div>

                                <div class="md:col-span-3 pr-8">
                                    <div class="flex items-center justify-between">
                                        <InputLabel :for="'eq_qty_'+index" value="Jml Alat Simulasi" />
                                        <span v-if="eq.existing_quantity !== undefined && eq.existing_quantity !== null && eq.existing_quantity > 0" class="text-[11px] font-black tracking-tight">
                                            <span v-if="eq.quantity > eq.existing_quantity" class="text-emerald-700 bg-emerald-100 border border-emerald-300 px-1.5 py-0.5 rounded shadow-sm">+{{ eq.quantity - eq.existing_quantity }} Unit</span>
                                            <span v-else-if="eq.quantity < eq.existing_quantity" class="text-rose-700 bg-rose-100 border border-rose-300 px-1.5 py-0.5 rounded shadow-sm">-{{ eq.existing_quantity - eq.quantity }} Unit</span>
                                            <span v-else class="text-slate-600 bg-slate-200 px-1.5 py-0.5 rounded">Tetap</span>
                                        </span>
                                    </div>
                                    <TextInput :id="'eq_qty_'+index" v-model="eq.quantity" type="number" min="1" class="mt-1 block w-full text-sm font-black text-indigo-950 border-indigo-300 focus:border-indigo-600 focus:ring-indigo-600 shadow-sm" />
                                </div>

                                <div v-if="eq.equipment_type_id && getSelectedType(eq)" class="md:col-span-12 mt-1 pt-2 border-t border-gray-200 flex flex-wrap items-center justify-between text-xs text-indigo-950 bg-indigo-50/80 px-3.5 py-2 rounded-lg border border-indigo-100">
                                    <span class="font-bold">⚡ Subtotal Baris ini ({{ eq.quantity || 0 }} Unit):</span>
                                    <div class="flex items-center space-x-4">
                                        <span>⏱️ Total Jam: <strong class="text-indigo-700 font-black">{{ ((getSelectedType(eq).annual_hours || 0) * (eq.quantity || 0)).toFixed(2) }} Jam/Tahun</strong></span>
                                        <span>⚖️ Total Bobot: <strong class="text-indigo-700 font-black">{{ (getSelectedType(eq).weight || 0) * (eq.quantity || 0) }}</strong></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Tambah Alat (Dipindah ke bawah agar tidak perlu scroll ke atas) -->
                            <div class="flex justify-end mb-6">
                                <PrimaryButton type="button" @click="addEquipmentRow" class="text-xs sm:text-sm px-4 py-2.5 shadow-md hover:shadow-lg transition-all flex items-center space-x-1.5 font-bold">
                                    <span>+ Tambah Alat</span>
                                </PrimaryButton>
                            </div>

                            <!-- Banner Summary Grand Total -->
                            <div class="bg-gradient-to-r from-pelindo-blue to-slate-900 text-white p-4 rounded-2xl shadow-xl mb-6 flex flex-col md:flex-row items-center justify-between border border-pelindo-cyan/20">
                                <div class="flex items-center space-x-3 mb-3 md:mb-0">
                                    <div class="p-2 bg-white/10 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pelindo-cyan" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-sm md:text-base">Akumulasi Sementara ({{ form.equipments.length }} Baris Alat)</h4>
                                        <p class="text-xs text-slate-200">Estimasi total kebutuhan pemeliharaan dan bobot site sebelum kalkulasi</p>
                                    </div>
                                </div>
                                <div class="flex flex-wrap items-center gap-4 bg-black/20 px-4 py-2 rounded-xl border border-white/10 w-full md:w-auto justify-around md:justify-end">
                                    <div class="text-center">
                                        <span class="block text-[10px] uppercase tracking-wider text-slate-300 font-bold">Jam Preventive (Job Plan)</span>
                                        <span class="font-black text-base md:text-lg text-pelindo-cyan">⏱️ {{ totalSimulationHours }} Jam</span>
                                    </div>
                                    <div class="h-8 w-px bg-white/20 hidden sm:block"></div>
                                    <div class="text-center" :title="'Alokasi breakdown ' + (100 - (props.targetAvailability || 85)) + '% dari target availability'">
                                        <span class="block text-[10px] uppercase tracking-wider text-rose-300 font-bold">Jam Breakdown ({{ 100 - (props.targetAvailability || 85) }}%)</span>
                                        <span class="font-black text-base md:text-lg text-pelindo-cyan">🚨 {{ totalSimulationBreakdownHours }} Jam</span>
                                    </div>
                                    <div class="h-8 w-px bg-white/20 hidden sm:block"></div>
                                    <div class="text-center">
                                        <span class="block text-[10px] uppercase tracking-wider text-slate-300 font-bold">Total Bobot Site</span>
                                        <span class="font-black text-base md:text-lg text-pelindo-cyan">⚖️ {{ totalSimulationWeight }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end">
                            <PrimaryButton @click="calculateSimulation" :class="{ 'opacity-25': isCalculating }" :disabled="isCalculating">
                                Jalankan Kalkulasi Simulasi
                            </PrimaryButton>
                        </div>
                    </section>
                </div>

                <!-- Hasil Simulasi -->
                <div v-if="simulationResult" id="simulation-result" class="mt-12">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-black text-pelindo-blue tracking-tight">Hasil Kalkulasi Simulasi</h2>
                        <PrimaryButton @click="saveSimulation" :class="{ 'opacity-25': isSaving }" :disabled="isSaving" class="!bg-pelindo-blue hover:!bg-[#003B6F]">
                            Simpan ke Database
                        </PrimaryButton>
                    </div>

                    <div v-if="existingSiteData" class="mb-4 bg-yellow-100 text-yellow-800 p-3 rounded text-sm border border-yellow-200">
                        <strong>Perhatian:</strong> Site dengan nama <strong>{{ form.name }}</strong> sudah ada di database. 
                        Tabel di bawah akan membandingkan kondisi saat ini (Eksisting) dengan hasil jika skenario form di atas direalisasikan. 
                        Menekan tombol "Simpan" akan me-replace data alat di site eksisting tersebut.
                    </div>

                    <table class="min-w-full divide-y divide-gray-200 bg-white rounded-xl overflow-hidden shadow-sm border border-slate-200">
                        <thead class="bg-slate-800 text-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Parameter</th>
                                <th v-if="existingSiteData" class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wider">Kondisi Eksisting</th>
                                <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wider">Hasil Simulasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Kelas Site</td>
                                <td v-if="existingSiteData" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ existingSiteData.site_class }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-bold text-pelindo-blue">{{ simulationResult.site_class }}</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Total Bobot</td>
                                <td v-if="existingSiteData" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ formatNum(existingSiteData.total_weight || 0) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-bold text-pelindo-blue">{{ formatNum(simulationResult.total_weight) }}</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Total Jam Pemeliharaan (Tahun)</td>
                                <td v-if="existingSiteData" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ formatNum(existingSiteData.total_maintenance_hours || 0) }} Jam</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-bold text-pelindo-blue">{{ formatNum(simulationResult.total_maintenance_hours) }} Jam</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 flex items-center justify-between">
                                    <span>Kebutuhan Teknisi</span>
                                    <button @click="showTechBreakdown = !showTechBreakdown" type="button" class="ml-2 text-xs bg-indigo-100 text-indigo-700 px-2.5 py-1 rounded-full hover:bg-indigo-200 font-semibold transition focus:outline-none">
                                        {{ showTechBreakdown ? '❌ Tutup' : '🔍 Bedah Formula' }}
                                    </button>
                                </td>
                                <td v-if="existingSiteData" class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="text-sm font-bold text-gray-900">{{ existingSiteData.existing_technical_staff || 0 }} Orang <span class="text-[11px] font-normal text-gray-500">(Aktual)</span></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <div class="font-bold text-pelindo-blue text-base">{{ formatNum(simulationResult.technical_staff_needed) }} Orang</div>
                                    <div v-if="existingSiteData" class="text-xs mt-1">
                                        <span class="text-gray-500">vs Aktual: </span>
                                        <span v-if="simulationResult.technical_staff_needed > (existingSiteData.existing_technical_staff || 0)" class="text-amber-600 font-semibold">Kurang {{ formatNum(simulationResult.technical_staff_needed - (existingSiteData.existing_technical_staff || 0)) }}</span>
                                        <span v-else class="text-emerald-600 font-semibold">Surplus {{ formatNum((existingSiteData.existing_technical_staff || 0) - simulationResult.technical_staff_needed) }}</span>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="showTechBreakdown && simulationResult.breakdown" class="bg-indigo-50/50">
                                <td :colspan="existingSiteData ? 3 : 2" class="px-6 py-4">
                                    <div class="bg-white p-4 rounded-lg border border-indigo-200 shadow-sm text-xs text-gray-700">
                                        <h4 class="font-bold text-indigo-900 text-sm mb-3 flex items-center gap-1.5">
                                            <span>📊 Transparansi Perhitungan Standar Teknisi</span>
                                        </h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="bg-indigo-50/50 p-3 rounded border border-indigo-100">
                                                <span class="font-bold text-indigo-800 block mb-2 border-b border-indigo-200 pb-1">💡 Parameter Acuan Sistem</span>
                                                <ul class="space-y-1.5">
                                                    <li class="flex justify-between"><span>Skema Kerja:</span> <strong class="text-gray-900">{{ simulationResult.breakdown.work_scheme }}</strong></li>
                                                    <li class="flex justify-between"><span>Kapasitas Pembagi (Man Hours):</span> <strong class="text-indigo-600">{{ formatNum(simulationResult.breakdown.productive_hours) }} Jam/Thn <span class="text-[10px] font-normal text-gray-500 block sm:inline">(Global Settings)</span></strong></li>
                                                    <li class="flex justify-between"><span>Patokan Baseline Tertinggi:</span> <strong class="text-gray-900">{{ formatNum(simulationResult.breakdown.highest_baseline) }} Orang <span class="text-[10px] font-normal text-gray-500 block sm:inline">({{ simulationResult.breakdown.highest_baseline_category }})</span></strong></li>
                                                    <li class="flex justify-between"><span>Total Jam Pemeliharaan:</span> <strong class="text-gray-900">{{ formatNum(simulationResult.breakdown.total_maintenance_hours) }} Jam/Thn</strong></li>
                                                </ul>
                                            </div>
                                            <div class="bg-indigo-50/50 p-3 rounded border border-indigo-100 flex flex-col justify-between">
                                                <div>
                                                    <span class="font-bold text-indigo-800 block mb-2 border-b border-indigo-200 pb-1">🔢 Rincian 3 Pilar Kebutuhan Teknisi</span>
                                                    <div class="space-y-2 text-gray-600">
                                                        <div>
                                                            <span class="block font-semibold text-gray-800">1. Pilar Baseline Kategori Tertinggi:</span>
                                                            <span><strong>{{ formatNum(simulationResult.breakdown.highest_baseline) }} Orang</strong> <span class="text-[10px]">({{ simulationResult.breakdown.highest_baseline_category }})</span></span>
                                                        </div>
                                                        <div>
                                                            <span class="block font-semibold text-gray-800">2. Pilar Preventive Tambahan (Job Plan):</span>
                                                            <span>{{ formatNum(simulationResult.breakdown.additional_hours) }} Jam ÷ {{ formatNum(simulationResult.breakdown.productive_hours) }} Jam/Thn = <strong>{{ formatNum(simulationResult.breakdown.additional_tech) }} Orang</strong></span>
                                                        </div>
                                                        <div v-if="simulationResult.breakdown.breakdown_tech !== undefined">
                                                            <span class="block font-semibold text-rose-700">3. Pilar Breakdown Shift ({{ simulationResult.breakdown.breakdown_rate_percent }}% Avail):</span>
                                                            <span>{{ formatNum(simulationResult.breakdown.total_breakdown_hours) }} Jam ÷ {{ formatNum(simulationResult.breakdown.shift_productive_hours) }} Jam/Thn = <strong class="text-rose-600">{{ formatNum(simulationResult.breakdown.breakdown_tech) }} Orang</strong></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-3 pt-2 border-t border-indigo-200 flex justify-between items-center text-sm font-bold text-indigo-900">
                                                    <span>Total Standar Teknisi:</span>
                                                    <span>{{ formatNum(simulationResult.breakdown.highest_baseline) }} + {{ formatNum(simulationResult.breakdown.additional_tech) }} <span v-if="simulationResult.breakdown.breakdown_tech !== undefined">+ {{ formatNum(simulationResult.breakdown.breakdown_tech) }}</span> = <strong class="text-indigo-700 text-base">{{ formatNum(simulationResult.technical_staff_needed) }} Orang</strong></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 flex items-center justify-between">
                                    <span>Kebutuhan Non-Teknisi</span>
                                    <button @click="showNonTechBreakdown = !showNonTechBreakdown" type="button" class="ml-2 text-xs bg-indigo-100 text-indigo-700 px-2.5 py-1 rounded-full hover:bg-indigo-200 font-semibold transition focus:outline-none">
                                        {{ showNonTechBreakdown ? '❌ Tutup' : '👥 Rincian Formasi' }}
                                    </button>
                                </td>
                                <td v-if="existingSiteData" class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="text-sm font-bold text-gray-900">{{ existingSiteData.existing_non_technical_staff || 0 }} Orang <span class="text-[11px] font-normal text-gray-500">(Aktual)</span></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <div class="font-bold text-pelindo-blue text-base">{{ simulationResult.non_technical_staff_needed }} Orang</div>
                                    <div v-if="existingSiteData" class="text-xs mt-1">
                                        <span class="text-gray-500">vs Aktual: </span>
                                        <span v-if="simulationResult.non_technical_staff_needed > (existingSiteData.existing_non_technical_staff || 0)" class="text-amber-600 font-semibold">Kurang {{ simulationResult.non_technical_staff_needed - (existingSiteData.existing_non_technical_staff || 0) }}</span>
                                        <span v-else class="text-emerald-600 font-semibold">Surplus {{ (existingSiteData.existing_non_technical_staff || 0) - simulationResult.non_technical_staff_needed }}</span>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="showNonTechBreakdown && simulationResult.breakdown" class="bg-indigo-50/50">
                                <td :colspan="existingSiteData ? 3 : 2" class="px-6 py-4">
                                    <div class="bg-white p-4 rounded-lg border border-indigo-200 shadow-sm text-xs text-gray-700">
                                        <h4 class="font-bold text-indigo-900 text-sm mb-3 flex items-center gap-1.5">
                                            <span>👥 Rincian Formasi Staf Non-Teknis (Kelas Site: {{ simulationResult.site_class }})</span>
                                        </h4>
                                        <div v-if="simulationResult.breakdown.non_technical_positions && simulationResult.breakdown.non_technical_positions.length > 0" class="flex flex-wrap gap-2">
                                            <div v-for="(pos, idx) in simulationResult.breakdown.non_technical_positions" :key="idx" class="bg-indigo-50 border border-indigo-200 rounded-md px-3 py-2 flex items-center gap-2">
                                                <span class="font-semibold text-indigo-900">{{ pos.title }}</span>
                                                <span class="bg-indigo-600 text-white font-bold px-2 py-0.5 rounded text-[11px]">{{ pos.quantity }} Orang</span>
                                                <span class="text-[10px] text-gray-500 uppercase">({{ pos.category }})</span>
                                            </div>
                                        </div>
                                        <div v-else class="text-gray-500 italic">
                                            Tidak ada kebutuhan staf non-teknis standar untuk kelas site ini.
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
