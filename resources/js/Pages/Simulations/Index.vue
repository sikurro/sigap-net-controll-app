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
});

const form = useForm({
    name: '',
    region: '',
    equipments: [
        { equipment_type_id: '', name: '', code: '', quantity: 1 }
    ],
});

const isCalculating = ref(false);
const simulationResult = ref(null);
const existingSiteData = ref(null);

const addEquipmentRow = () => {
    form.equipments.push({ equipment_type_id: '', name: '', code: '', quantity: 1 });
};

const removeEquipmentRow = (index) => {
    if (form.equipments.length > 1) {
        form.equipments.splice(index, 1);
    }
};

const fillRegionFromExisting = () => {
    const matchedSite = props.existingSites.find(s => s.name.toLowerCase() === form.name.toLowerCase());
    if (matchedSite && !form.region) {
        form.region = matchedSite.region;
    }
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
                            <div>
                                <InputLabel for="name" value="Nama Site" />
                                <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" list="existing-sites-list" @blur="fillRegionFromExisting" />
                                <datalist id="existing-sites-list">
                                    <option v-for="site in existingSites" :key="site.id" :value="site.name"></option>
                                </datalist>
                                <InputError :message="form.errors.name" class="mt-2" />
                            </div>
                            <div>
                                <InputLabel for="region" value="Wilayah" />
                                <TextInput id="region" v-model="form.region" type="text" class="mt-1 block w-full" />
                                <InputError :message="form.errors.region" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-6 border-t pt-4">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-md font-medium text-gray-900">Daftar Alat (Simulasi)</h3>
                                <PrimaryButton type="button" @click="addEquipmentRow" class="text-xs">
                                    + Tambah Alat
                                </PrimaryButton>
                            </div>

                            <div v-for="(eq, index) in form.equipments" :key="index" class="bg-gray-50 p-4 mb-4 rounded border grid grid-cols-1 md:grid-cols-6 gap-4 items-start relative">
                                <button v-if="form.equipments.length > 1" type="button" @click="removeEquipmentRow(index)" class="absolute top-2 right-2 text-red-500 hover:text-red-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <div class="md:col-span-2">
                                    <InputLabel :for="'eq_name_'+index" value="Nama Alat (Opsional)" />
                                    <TextInput :id="'eq_name_'+index" v-model="eq.name" type="text" class="mt-1 block w-full text-sm" />
                                </div>

                                <div>
                                    <InputLabel :for="'eq_code_'+index" value="Kode Alat (Opsional)" />
                                    <TextInput :id="'eq_code_'+index" v-model="eq.code" type="text" class="mt-1 block w-full text-sm" />
                                </div>

                                <div class="md:col-span-2">
                                    <InputLabel :for="'eq_type_'+index" value="Jenis Alat" />
                                    <select :id="'eq_type_'+index" v-model="eq.equipment_type_id" class="mt-1 block w-full text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="" disabled>Pilih Jenis</option>
                                        <option v-for="type in equipmentTypes" :key="type.id" :value="type.id">
                                            {{ type.name }}
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <InputLabel :for="'eq_qty_'+index" value="Jml Alat" />
                                    <TextInput :id="'eq_qty_'+index" v-model="eq.quantity" type="number" min="1" class="mt-1 block w-full text-sm" />
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
                <div v-if="simulationResult" class="p-4 sm:p-8 bg-indigo-50 border-indigo-200 border shadow sm:rounded-lg">
                    <header class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-indigo-900">Hasil Kalkulasi Simulasi</h2>
                        <PrimaryButton @click="saveSimulation">Simpan ke Database</PrimaryButton>
                    </header>

                    <div v-if="existingSiteData" class="mb-4 bg-yellow-100 text-yellow-800 p-3 rounded text-sm border border-yellow-200">
                        <strong>Perhatian:</strong> Site dengan nama <strong>{{ form.name }}</strong> sudah ada di database. 
                        Tabel di bawah akan membandingkan kondisi saat ini (Eksisting) dengan hasil jika skenario form di atas direalisasikan. 
                        Menekan tombol "Simpan" akan me-replace data alat di site eksisting tersebut.
                    </div>

                    <table class="min-w-full divide-y divide-indigo-200 bg-white rounded overflow-hidden shadow-sm">
                        <thead class="bg-indigo-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Parameter</th>
                                <th v-if="existingSiteData" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Kondisi Eksisting</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-indigo-700 uppercase tracking-wider">Hasil Simulasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-indigo-100">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Kelas Site</td>
                                <td v-if="existingSiteData" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ existingSiteData.site_class }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-bold text-indigo-600">{{ simulationResult.site_class }}</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Total Jam Pemeliharaan (Tahun)</td>
                                <td v-if="existingSiteData" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">-</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-bold text-indigo-600">{{ formatNum(simulationResult.total_maintenance_hours) }} Jam</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Standar Teknisi</td>
                                <td v-if="existingSiteData" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ formatNum(existingSiteData.technical_staff_needed) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-bold text-indigo-600">
                                    {{ formatNum(simulationResult.technical_staff_needed) }}
                                    <span v-if="existingSiteData && simulationResult.technical_staff_needed > existingSiteData.technical_staff_needed" class="text-xs text-red-500 ml-1">(+{{ formatNum(simulationResult.technical_staff_needed - existingSiteData.technical_staff_needed) }})</span>
                                    <span v-if="existingSiteData && simulationResult.technical_staff_needed < existingSiteData.technical_staff_needed" class="text-xs text-green-500 ml-1">({{ formatNum(simulationResult.technical_staff_needed - existingSiteData.technical_staff_needed) }})</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Standar Non-Teknisi</td>
                                <td v-if="existingSiteData" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ existingSiteData.non_technical_staff_needed }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-bold text-indigo-600">
                                    {{ simulationResult.non_technical_staff_needed }}
                                    <span v-if="existingSiteData && simulationResult.non_technical_staff_needed > existingSiteData.non_technical_staff_needed" class="text-xs text-red-500 ml-1">(+{{ simulationResult.non_technical_staff_needed - existingSiteData.non_technical_staff_needed }})</span>
                                    <span v-if="existingSiteData && simulationResult.non_technical_staff_needed < existingSiteData.non_technical_staff_needed" class="text-xs text-green-500 ml-1">({{ simulationResult.non_technical_staff_needed - existingSiteData.non_technical_staff_needed }})</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
