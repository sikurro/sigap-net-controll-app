<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
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

const toggleRow = (id) => {
    if (expandedRows.value.includes(id)) {
        expandedRows.value = expandedRows.value.filter(rowId => rowId !== id);
    } else {
        expandedRows.value.push(id);
    }
};

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

                <div class="mb-4 flex justify-between items-center">
                    <div>
                        <a :href="route('sites.download-template')" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-xl font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-pelindo-cyan/5 hover:border-pelindo-cyan focus:outline-none transition ease-in-out duration-150 mr-2">
                            Download Template
                        </a>
                        <SecondaryButton @click="openImportModal" class="mr-2">
                            Import Excel
                        </SecondaryButton>
                        <a :href="route('sites.export')" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-xl font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-pelindo-cyan/5 hover:border-pelindo-cyan focus:outline-none transition ease-in-out duration-150">
                            Export Excel
                        </a>
                    </div>
                    <PrimaryButton @click="openCreateModal">
                        Tambah Site
                    </PrimaryButton>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-200/80 p-6">
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
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center font-medium">
                                            <button @click.stop="openEditModal(site)" class="text-pelindo-blue hover:text-[#003B6F] font-bold mr-3">Edit</button>
                                            <button @click.stop="deleteSite(site.id)" class="text-red-600 hover:text-red-900">Delete</button>
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
                                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Total Bobot</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    <tr v-for="eq in site.equipments" :key="eq.id">
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 font-medium">{{ eq.code }}</td>
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">{{ eq.equipment_type_name }}</td>
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-center text-gray-500">{{ eq.weight }}</td>
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-center text-gray-500">{{ eq.quantity }}</td>
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

                    <div v-for="(eq, index) in form.equipments" :key="index" class="bg-gray-50 p-4 mb-4 rounded border grid grid-cols-1 md:grid-cols-3 gap-4 items-start relative pr-10">
                        <button type="button" @click="removeEquipmentRow(index)" class="absolute top-2 right-2 text-red-500 hover:text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div class="md:col-span-2">
                            <InputLabel :for="'eq_type_'+index" value="Jenis Alat" />
                            <select :id="'eq_type_'+index" v-model="eq.equipment_type_id" class="mt-1 block w-full text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="" disabled>Pilih Jenis</option>
                                <option v-for="type in equipmentTypes" :key="type.id" :value="type.id">
                                    {{ type.name }} ({{ type.code }})
                                </option>
                            </select>
                            <InputError :message="form.errors[`equipments.${index}.equipment_type_id`]" class="mt-1" />
                        </div>

                        <div>
                            <InputLabel :for="'eq_qty_'+index" value="Jumlah" />
                            <TextInput :id="'eq_qty_'+index" v-model="eq.quantity" type="number" min="1" class="mt-1 block w-full text-sm" />
                            <InputError :message="form.errors[`equipments.${index}.quantity`]" class="mt-1" />
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
