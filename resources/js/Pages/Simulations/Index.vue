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
const isSaving = ref(false);
const isFormVisible = ref(true);
const simulationResult = ref(null);
const existingSiteData = ref(null);

// Smart Site Search State
const siteSearchQuery = ref('');
const isSiteDropdownOpen = ref(false);
const selectedExistingSite = ref(null);

const addEquipmentRow = () => {
    form.equipments.push({ equipment_type_id: '', name: '', code: '', quantity: 1, utilization_rate: 0, existing_quantity: 0, searchQuery: '', isOpen: false });
};

const removeEquipmentRow = (index) => {
    if (form.equipments.length > 1) {
        form.equipments.splice(index, 1);
    }
};

const formatEquipmentLabel = (type) => {
    const formatNum = (val) => new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 2 }).format(val);
    const hasMB = type.job_plans && type.job_plans.some(jp => jp.type === 'MB' && jp.interval_meter > 0);
    const jamLabel = hasMB ? 'Est. ' + formatNum(type.annual_hours || 0) : formatNum(type.annual_hours || 0);
    return `[${type.code || '-'}] ${type.name} (Bobot: ${formatNum(type.weight)} | ⏱️ ${jamLabel} Jam/Thn)`;
};

const getSelectedType = (eq) => {
    if (!eq.equipment_type_id) return null;
    return props.equipmentTypes.find(t => t.id === eq.equipment_type_id) || null;
};

const calculateDynamicHours = (type, utilizationRate) => {
    if (!type || !type.job_plans) return type?.annual_hours || 0;
    
    // If utilization <= 0 or empty, fallback to static baseline
    if (!utilizationRate || parseFloat(utilizationRate) <= 0) {
        return type.annual_hours || 0;
    }
    
    let staticHours = 0;
    let mbPlans = [];
    
    type.job_plans.forEach(jp => {
        const jpType = jp.type || 'MB';
        if (jpType === 'MB' && parseFloat(jp.interval_meter) > 0) {
            mbPlans.push(jp);
        } else {
            const hoursPerOcc = (parseFloat(jp.duration_minutes) || 0) / 60;
            const freq = parseFloat(jp.frequency_per_year) || 0;
            staticHours += hoursPerOcc * freq;
        }
    });
    
    let mbHours = 0;
    if (mbPlans.length > 0) {
        // Sort descending by interval
        mbPlans.sort((a, b) => parseFloat(b.interval_meter) - parseFloat(a.interval_meter));
        
        const jamOperasiAktual = (parseFloat(utilizationRate) / 100) * 8760;
        let frequencies = {};
        
        mbPlans.forEach(jp => {
            const interval = parseFloat(jp.interval_meter);
            let rawFreq = Math.floor(jamOperasiAktual / interval);
            
            let penguguran = 0;
            for (const higherInterval in frequencies) {
                if (parseFloat(higherInterval) > interval && parseFloat(higherInterval) % interval === 0) {
                    penguguran += frequencies[higherInterval];
                }
            }
            
            let actualFreq = Math.max(0, rawFreq - penguguran);
            frequencies[interval] = actualFreq;
            
            const hoursPerOcc = (parseFloat(jp.duration_minutes) || 0) / 60;
            mbHours += hoursPerOcc * actualFreq;
        });
    }
    
    return staticHours + mbHours;
};

const totalSimulationHours = computed(() => {
    let total = 0;
    form.equipments.forEach(eq => {
        const type = getSelectedType(eq);
        if (type && eq.quantity > 0) {
            const perUnitHours = calculateDynamicHours(type, eq.utilization_rate);
            total += perUnitHours * eq.quantity;
        }
    });
    return total.toFixed(2);
});

const getEquipmentRowTotalHours = (eq) => {
    const type = getSelectedType(eq);
    if (type && eq.quantity > 0) {
        return calculateDynamicHours(type, eq.utilization_rate) * eq.quantity;
    }
    return 0;
};

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
                utilization_rate: eq.utilization_rate || 0,
                existing_quantity: eq.quantity,
                searchQuery: type ? formatEquipmentLabel(type) : '',
                isOpen: false
            };
        });
    } else {
        form.equipments = [
            { equipment_type_id: '', name: '', code: '', quantity: 1, utilization_rate: 0, existing_quantity: 0, searchQuery: '', isOpen: false }
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
                { equipment_type_id: '', name: '', code: '', quantity: 1, utilization_rate: 0, existing_quantity: 0, searchQuery: '', isOpen: false }
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
                    { equipment_type_id: '', name: '', code: '', quantity: 1, utilization_rate: 0, existing_quantity: 0, searchQuery: '', isOpen: false }
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
        { equipment_type_id: '', name: '', code: '', quantity: 1, utilization_rate: 0, existing_quantity: 0, searchQuery: '', isOpen: false }
    ];
};

const calculateSimulation = async () => {
    // Clear previous results
    simulationResult.value = null;
    existingSiteData.value = null;
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
        isFormVisible.value = false;
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
        isSaving.value = true;
        form.post(route('simulasi.store'), {
            onSuccess: () => {
                // redirected to sites index automatically
            },
            onFinish: () => {
                isSaving.value = false;
            }
        });
    }
};

// Computed to format numbers
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
                        <header class="flex justify-between items-start sm:items-center">
                            <div>
                                <h2 class="text-lg font-medium text-gray-900">Form Simulasi Site</h2>
                                <p class="mt-1 text-sm text-gray-600">
                                    Masukkan nama site dan rincian alat yang akan disimulasikan. Data tidak akan tersimpan ke database sebelum Anda memilih untuk menyimpannya.
                                </p>
                            </div>
                            <button @click="isFormVisible = !isFormVisible" type="button" class="mt-2 sm:mt-0 flex items-center justify-center text-pelindo-blue hover:text-[#003B6F] transition-colors bg-blue-50 p-2 rounded-full border border-blue-100 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pelindo-blue" :title="isFormVisible ? 'Sembunyikan Form' : 'Tampilkan Form'">
                                <svg v-if="isFormVisible" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                        </header>

                        <transition name="slide">
                            <div v-show="isFormVisible">

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

                                <div class="md:col-span-5 relative">
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
                                                        ⏱️ {{ $formatNumber(type.annual_hours || 0) }} Jam/Thn
                                                    </span>
                                                    <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full font-medium">
                                                        ⚖️ Bobot: {{ $formatNumber(type.weight) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="md:col-span-2">
                                    <InputLabel value="Jml Alat Eksisting" />
                                    <div class="mt-1 flex items-center justify-between bg-slate-200/70 border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-700 shadow-inner font-bold select-none">
                                        <span>{{ (eq.existing_quantity !== undefined && eq.existing_quantity !== null && eq.existing_quantity > 0) ? eq.existing_quantity + ' Unit' : '-' }}</span>
                                        <span class="text-[10px] uppercase font-black bg-slate-300/80 text-slate-700 px-1.5 py-0.5 rounded">Eksisting</span>
                                    </div>
                                </div>

                                <div class="md:col-span-2">
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
                                
                                <div class="md:col-span-3 pr-8">
                                    <InputLabel :for="'eq_util_'+index" value="Utilisasi (%)" />
                                    <div class="relative mt-1">
                                        <TextInput :id="'eq_util_'+index" v-model="eq.utilization_rate" type="number" step="0.01" min="0" max="100" class="block w-full text-sm font-black text-indigo-950 border-indigo-300 focus:border-indigo-600 focus:ring-indigo-600 shadow-sm pr-7" />
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">%</span>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="eq.equipment_type_id && getSelectedType(eq)" class="md:col-span-12 mt-1 pt-2 border-t border-gray-200 flex flex-wrap items-center justify-between text-xs text-indigo-950 bg-indigo-50/80 px-3.5 py-2 rounded-lg border border-indigo-100">
                                    <span class="font-bold">⚡ Subtotal Baris ini ({{ eq.quantity || 0 }} Unit):</span>
                                    <div class="flex items-center space-x-4">
                                        <span v-if="parseFloat(eq.utilization_rate) > 0">⏱️ Jam Aktual (Simulasi): <strong class="text-indigo-700 font-black">{{ $formatNumber(getEquipmentRowTotalHours(eq)) }} Jam/Tahun</strong></span>
                                        <span v-else>⏱️ Est. Jam (Baseline): <strong class="text-indigo-700 font-black">{{ $formatNumber(getEquipmentRowTotalHours(eq)) }} Jam/Tahun</strong></span>
                                        <span>⚖️ Total Bobot: <strong class="text-indigo-700 font-black">{{ $formatNumber((getSelectedType(eq).weight || 0) * (eq.quantity || 0)) }}</strong></span>
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
                                        <span class="font-black text-base md:text-lg text-pelindo-cyan">⏱️ {{ $formatNumber(totalSimulationHours) }} Jam</span>
                                    </div>
                                    <div class="h-8 w-px bg-white/20 hidden sm:block"></div>
                                    <div class="text-center" :title="'Alokasi breakdown ' + (100 - (props.targetAvailability || 85)) + '% dari target availability'">
                                        <span class="block text-[10px] uppercase tracking-wider text-rose-300 font-bold">Jam Breakdown ({{ $formatNumber(100 - (props.targetAvailability || 85)) }}%)</span>
                                        <span class="font-black text-base md:text-lg text-pelindo-cyan">🚨 {{ $formatNumber(totalSimulationBreakdownHours) }} Jam</span>
                                    </div>
                                    <div class="h-8 w-px bg-white/20 hidden sm:block"></div>
                                    <div class="text-center">
                                        <span class="block text-[10px] uppercase tracking-wider text-slate-300 font-bold">Total Bobot Site</span>
                                        <span class="font-black text-base md:text-lg text-pelindo-cyan">⚖️ {{ $formatNumber(totalSimulationWeight) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end">
                            <PrimaryButton @click="calculateSimulation" :class="{ 'opacity-75 cursor-wait': isCalculating }" :disabled="isCalculating">
                                <div class="flex items-center justify-center space-x-2">
                                    <svg v-if="isCalculating" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>{{ isCalculating ? 'Sedang Mengkalkulasi...' : 'Jalankan Kalkulasi Simulasi' }}</span>
                                </div>
                            </PrimaryButton>
                        </div>
                        </div>
                        </transition>
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

                    <div v-if="existingSiteData" class="mb-6 bg-amber-50 rounded-xl p-4 border border-amber-200/60 shadow-sm flex items-start space-x-3">
                        <div class="flex-shrink-0 mt-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500" viewBox="0 0 20 20" fill="currentColor">
                              <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-amber-800">Perhatian: Site Eksisting Ditemukan</h3>
                            <p class="mt-1 text-sm text-amber-700 leading-relaxed">
                                Site dengan nama <strong class="text-amber-900">{{ form.name }}</strong> sudah ada di database. 
                                Tabel di bawah ini membandingkan kondisi eksisting dengan hasil skenario simulasi Anda. 
                                Menekan tombol <strong class="text-amber-900">"Simpan ke Database"</strong> akan <span class="underline decoration-amber-300">mengganti (replace)</span> seluruh data alat di site tersebut.
                            </p>
                        </div>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200 bg-white rounded-xl overflow-hidden shadow-sm border border-slate-200">
                        <thead class="bg-slate-800 text-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-bold uppercase tracking-wider">Parameter</th>
                                <th v-if="existingSiteData" class="px-6 py-3 text-center text-sm font-bold uppercase tracking-wider">Kondisi Eksisting</th>
                                <th class="px-6 py-3 text-center text-sm font-bold uppercase tracking-wider">Hasil Simulasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-gray-900">Kelas Site</td>
                                <td v-if="existingSiteData" class="px-6 py-4 whitespace-nowrap text-base text-center text-gray-500">{{ existingSiteData.site_class }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-base text-center font-bold text-pelindo-blue">{{ simulationResult.site_class }}</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-gray-900">Total Bobot</td>
                                <td v-if="existingSiteData" class="px-6 py-4 whitespace-nowrap text-base text-center text-gray-500">{{ $formatNumber(existingSiteData.total_weight || 0) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-base text-center font-bold text-pelindo-blue">{{ $formatNumber(simulationResult.total_weight) }}</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-gray-900">Total Jam Pemeliharaan (Tahun)</td>
                                <td v-if="existingSiteData" class="px-6 py-4 whitespace-nowrap text-base text-center text-gray-500">{{ $formatNumber(existingSiteData.total_maintenance_hours || 0) }} Jam</td>
                                <td class="px-6 py-4 whitespace-nowrap text-base text-center font-bold text-pelindo-blue">{{ $formatNumber(simulationResult.total_maintenance_hours) }} Jam</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-gray-900">
                                    Kebutuhan Teknisi
                                </td>
                                <td v-if="existingSiteData" class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="text-base font-bold text-gray-900">{{ $formatNumber(existingSiteData.existing_technical_staff || 0) }} Orang <span class="text-xs font-normal text-gray-500">(Aktual)</span></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <div class="font-bold text-pelindo-blue text-lg">{{ $formatNumber(simulationResult.technical_staff_needed) }} Orang</div>
                                    <div v-if="existingSiteData" class="text-xs mt-1">
                                        <span class="text-gray-500">vs Aktual: </span>
                                        <span v-if="simulationResult.technical_staff_needed > (existingSiteData.existing_technical_staff || 0)" class="text-amber-600 font-semibold">Kurang {{ $formatNumber(simulationResult.technical_staff_needed - (existingSiteData.existing_technical_staff || 0)) }}</span>
                                        <span v-else class="text-emerald-600 font-semibold">Surplus {{ $formatNumber((existingSiteData.existing_technical_staff || 0) - simulationResult.technical_staff_needed) }}</span>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="simulationResult.breakdown" class="bg-slate-50 border-t-0">
                                <td :colspan="existingSiteData ? 3 : 2" class="px-0 py-0 pb-6 border-b border-gray-200">
                                    <!-- Rincian Formula Kartu (Executive Dashboard Style) -->
                                    <div class="mx-6 mt-4 p-5 bg-white rounded-xl border border-slate-200 shadow-sm relative">
                                        <!-- Header Badges -->
                                        <div class="flex flex-wrap items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                                            <div class="flex items-center space-x-2 text-xs font-bold text-slate-800">
                                                <svg class="w-4 h-4 text-pelindo-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                                                <span>PARAMETER ACUAN SISTEM:</span>
                                            </div>
                                            <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-md text-[11px] font-bold border border-slate-200">Skema: {{ simulationResult.breakdown.work_scheme }}</span>
                                            <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-md text-[11px] font-bold border border-slate-200">Kapasitas Pembagi: {{ $formatNumber(simulationResult.breakdown.productive_hours) }} Jam/Thn</span>
                                            <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-md text-[11px] font-bold border border-slate-200">Patokan Tertinggi: {{ simulationResult.breakdown.highest_baseline_category }}</span>
                                        </div>

                                        <!-- 3 Pillars Formula Grid -->
                                        <div class="flex flex-col lg:flex-row items-center gap-4 lg:gap-2 justify-center mb-6">
                                            
                                            <!-- Card 1: Baseline -->
                                            <div class="flex-1 w-full bg-blue-50/50 rounded-xl p-4 border border-blue-100 shadow-sm relative group transition-all hover:bg-blue-50 hover:border-blue-200 hover:shadow-md">
                                                <div class="text-[10px] font-black uppercase tracking-wider text-blue-500 mb-1">Pilar 1: Baseline Kategori Tertinggi</div>
                                                <div class="flex items-end gap-2 mb-1">
                                                    <span class="text-3xl font-black text-blue-900 leading-none">{{ $formatNumber(simulationResult.breakdown.highest_baseline) }}</span>
                                                    <span class="text-sm font-bold text-blue-700 mb-0.5">Orang</span>
                                                </div>
                                                <div class="text-xs text-blue-600/80 font-medium line-clamp-1" :title="'Berdasarkan patokan ' + simulationResult.breakdown.highest_baseline_category">
                                                    (Patokan {{ simulationResult.breakdown.highest_baseline_category }})
                                                </div>
                                            </div>

                                            <div class="text-xl font-black text-slate-300 mx-1 hidden lg:block">+</div>

                                            <!-- Card 2: Preventive -->
                                            <div class="flex-1 w-full bg-emerald-50/50 rounded-xl p-4 border border-emerald-100 shadow-sm relative group transition-all hover:bg-emerald-50 hover:border-emerald-200 hover:shadow-md cursor-help">
                                                <div class="text-[10px] font-black uppercase tracking-wider text-emerald-500 mb-1">Pilar 2: Preventive Tambahan</div>
                                                <div class="flex items-end gap-2 mb-1">
                                                    <span class="text-3xl font-black text-emerald-900 leading-none">{{ $formatNumber(simulationResult.breakdown.additional_tech) }}</span>
                                                    <span class="text-sm font-bold text-emerald-700 mb-0.5">Orang</span>
                                                </div>
                                                <div class="text-xs text-emerald-600/80 font-medium line-clamp-1 border-b border-dashed border-emerald-300 inline-block">
                                                    (Rincian jam diabaikan/potongan)
                                                </div>
                                                
                                                <!-- Tooltip on hover -->
                                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-64 bg-slate-800 text-white text-xs rounded-lg p-3 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none shadow-xl z-50">
                                                    <div class="font-bold mb-1 border-b border-slate-600 pb-1">Detail Kalkulasi Preventive:</div>
                                                    <div class="flex justify-between mb-0.5"><span>Total Jam Site:</span> <span class="font-bold">{{ $formatNumber(simulationResult.breakdown.total_maintenance_hours) }} Jam</span></div>
                                                    <div class="flex justify-between mb-0.5 text-rose-300"><span>Dikurangi Jam Pilar 1:</span> <span class="font-bold">-{{ $formatNumber(simulationResult.breakdown.highest_weight_single_unit_hours) }} Jam</span></div>
                                                    <div class="flex justify-between mb-0.5 text-emerald-300"><span>Sisa Jam Netto:</span> <span class="font-bold">{{ $formatNumber(simulationResult.breakdown.additional_hours) }} Jam</span></div>
                                                    <div class="mt-1 pt-1 border-t border-slate-600 text-[10px] text-slate-300 text-center">
                                                        {{ $formatNumber(simulationResult.breakdown.additional_hours) }} Jam ÷ Kapasitas ({{ $formatNumber(simulationResult.breakdown.productive_hours) }})
                                                    </div>
                                                    <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-slate-800 rotate-45"></div>
                                                </div>
                                            </div>

                                            <!-- Conditional Pilar 3 if exists (Shift) -->
                                            <template v-if="simulationResult.breakdown.breakdown_tech !== undefined">
                                                <div class="text-xl font-black text-slate-300 mx-1 hidden lg:block">+</div>
                                                <!-- Card 3: Breakdown -->
                                                <div class="flex-1 w-full bg-rose-50/50 rounded-xl p-4 border border-rose-100 shadow-sm relative group transition-all hover:bg-rose-50 hover:border-rose-200 hover:shadow-md">
                                                    <div class="text-[10px] font-black uppercase tracking-wider text-rose-500 mb-1">Pilar 3: Breakdown Shift ({{ simulationResult.breakdown.breakdown_rate_percent }}% Avail)</div>
                                                    <div class="flex items-end gap-2 mb-1">
                                                        <span class="text-3xl font-black text-rose-900 leading-none">{{ $formatNumber(simulationResult.breakdown.breakdown_tech) }}</span>
                                                        <span class="text-sm font-bold text-rose-700 mb-0.5">Orang</span>
                                                    </div>
                                                    <div class="text-[10px] text-rose-600/80 font-medium line-clamp-1 mt-1">
                                                        ({{ $formatNumber(simulationResult.breakdown.total_breakdown_hours) }} Jam ÷ {{ $formatNumber(simulationResult.breakdown.shift_productive_hours) }} Kapasitas Shift)
                                                    </div>
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Grand Total Footer -->
                                        <div class="bg-gradient-to-r from-pelindo-blue to-[#003B6F] text-white rounded-xl px-5 py-4 flex flex-col sm:flex-row justify-between items-center shadow-md">
                                            <span class="font-bold tracking-wide text-sm text-pelindo-cyan mb-2 sm:mb-0">TOTAL STANDAR TEKNISI =</span>
                                            <div class="flex items-center gap-3">
                                                <span class="text-xs text-slate-300 opacity-80 mt-1">
                                                    {{ $formatNumber(simulationResult.breakdown.highest_baseline) }} 
                                                    + {{ $formatNumber(simulationResult.breakdown.additional_tech) }}
                                                    <span v-if="simulationResult.breakdown.breakdown_tech !== undefined"> + {{ $formatNumber(simulationResult.breakdown.breakdown_tech) }}</span>
                                                    =
                                                </span>
                                                <span class="text-2xl font-black">{{ $formatNumber(simulationResult.technical_staff_needed) }} Orang</span>
                                            </div>
                                        </div>

                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-gray-900">
                                    Kebutuhan Non-Teknisi
                                </td>
                                <td v-if="existingSiteData" class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="text-base font-bold text-gray-900">{{ $formatNumber(existingSiteData.existing_non_technical_staff || 0) }} Orang <span class="text-xs font-normal text-gray-500">(Aktual)</span></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <div class="font-bold text-pelindo-blue text-lg">{{ $formatNumber(simulationResult.non_technical_staff_needed) }} Orang</div>
                                    <div v-if="existingSiteData" class="text-xs mt-1">
                                        <span class="text-gray-500">vs Aktual: </span>
                                        <span v-if="simulationResult.non_technical_staff_needed > (existingSiteData.existing_non_technical_staff || 0)" class="text-amber-600 font-semibold">Kurang {{ $formatNumber(simulationResult.non_technical_staff_needed - (existingSiteData.existing_non_technical_staff || 0)) }}</span>
                                        <span v-else class="text-emerald-600 font-semibold">Surplus {{ $formatNumber((existingSiteData.existing_non_technical_staff || 0) - simulationResult.non_technical_staff_needed) }}</span>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="simulationResult.breakdown" class="bg-slate-50 border-t-0">
                                <td :colspan="existingSiteData ? 3 : 2" class="px-0 py-0 pb-6">
                                    <div class="mx-6 mt-4 p-5 bg-white rounded-xl border border-slate-200 shadow-sm relative overflow-hidden">
                                        <div class="flex items-center space-x-2 text-sm font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">
                                            <span>👥 FORMASI STAF NON-TEKNIS (Kelas Site: {{ simulationResult.site_class }})</span>
                                        </div>
                                        <div v-if="simulationResult.breakdown.non_technical_positions && simulationResult.breakdown.non_technical_positions.length > 0" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                            <div v-for="(pos, idx) in simulationResult.breakdown.non_technical_positions" :key="idx" 
                                                class="bg-white border rounded-lg p-3 flex flex-col justify-between transition-shadow hover:shadow-md"
                                                :class="pos.category.toLowerCase().includes('non-fungsional') ? 'border-slate-200' : 'border-indigo-200 bg-indigo-50/30'"
                                            >
                                                <div class="flex items-start justify-between mb-2 gap-2">
                                                    <span class="text-xs font-bold leading-tight" :class="pos.category.toLowerCase().includes('non-fungsional') ? 'text-slate-700' : 'text-indigo-900'">{{ pos.title }}</span>
                                                    <span class="text-lg opacity-60">{{ pos.category.toLowerCase().includes('non-fungsional') ? '👤' : '👔' }}</span>
                                                </div>
                                                <div class="flex items-center justify-between mt-auto">
                                                    <span class="text-[9px] font-black uppercase tracking-widest px-1.5 py-0.5 rounded" :class="pos.category.toLowerCase().includes('non-fungsional') ? 'bg-slate-100 text-slate-500' : 'bg-indigo-100 text-indigo-600'">{{ pos.category }}</span>
                                                    <div class="flex items-end gap-1">
                                                        <span class="text-xl font-black leading-none" :class="pos.category.toLowerCase().includes('non-fungsional') ? 'text-slate-800' : 'text-indigo-700'">{{ $formatNumber(pos.quantity) }}</span>
                                                        <span class="text-[10px] font-bold text-slate-400 mb-0.5">Org</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-else class="text-gray-500 italic text-sm text-center py-4">
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

<style scoped>
.slide-enter-active,
.slide-leave-active {
  transition: all 0.4s ease-in-out;
  overflow: hidden;
}

.slide-enter-from,
.slide-leave-to {
  opacity: 0;
  max-height: 0;
  transform: translateY(-10px);
}

.slide-enter-to,
.slide-leave-from {
  opacity: 1;
  max-height: 2500px;
  transform: translateY(0);
}
</style>
