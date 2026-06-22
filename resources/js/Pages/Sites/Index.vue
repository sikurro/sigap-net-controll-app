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
    status: true,
    equipments: [],
});

const importForm = useForm({
    file: null,
});

const openCreateModal = () => {
    isEditing.value = false;
    form.reset();
    form.clearErrors();
    form.equipments = [];
    showModal.value = true;
};

const openEditModal = (site) => {
    isEditing.value = true;
    form.clearErrors();
    form.id = site.id;
    form.name = site.name;
    form.region = site.region;
    form.status = site.status;
    form.equipments = site.equipments.map(eq => ({
        id: eq.id,
        name: eq.name,
        code: eq.code,
        equipment_type_id: eq.equipment_type_id,
        status: eq.status,
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
        name: '',
        code: '',
        equipment_type_id: '',
        status: true,
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
                
                <div class="mb-4 flex justify-between items-center">
                    <div>
                        <a :href="route('sites.download-template')" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                            Download Template
                        </a>
                        <SecondaryButton @click="openImportModal" class="mr-2">
                            Import Excel
                        </SecondaryButton>
                        <a :href="route('sites.export')" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            Export Excel
                        </a>
                    </div>
                    <PrimaryButton @click="openCreateModal">
                        Tambah Site
                    </PrimaryButton>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="w-8 pb-4 pt-6 px-6"></th>
                                    <th class="pb-4 pt-6 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Site</th>
                                    <th class="pb-4 pt-6 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wilayah</th>
                                    <th class="pb-4 pt-6 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                    <th class="pb-4 pt-6 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jml Alat</th>
                                    <th class="pb-4 pt-6 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Std Teknisi</th>
                                    <th class="pb-4 pt-6 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Std Non-Teknisi</th>
                                    <th class="pb-4 pt-6 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="pb-4 pt-6 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <template v-for="site in sites" :key="site.id">
                                    <tr class="hover:bg-gray-50 cursor-pointer">
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
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center font-bold text-gray-900" @click="toggleRow(site.id)">{{ site.site_class }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center text-gray-500" @click="toggleRow(site.id)">{{ site.jumlah_alat }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center text-gray-500" @click="toggleRow(site.id)">{{ site.technical_staff_needed }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center text-gray-500" @click="toggleRow(site.id)">{{ site.non_technical_staff_needed }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center">
                                            <span v-if="site.status" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                            <span v-else class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Non Aktif</span>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center font-medium">
                                            <button @click.stop="openEditModal(site)" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                            <button @click.stop="deleteSite(site.id)" class="text-red-600 hover:text-red-900">Delete</button>
                                        </td>
                                    </tr>
                                    <!-- Child Row (Equipments) -->
                                    <tr v-if="expandedRows.includes(site.id)" class="bg-gray-50">
                                        <td colspan="8" class="px-8 py-4">
                                            <div v-if="site.equipments.length === 0" class="text-sm text-gray-500 italic">Belum ada alat.</div>
                                            <table v-else class="min-w-full divide-y divide-gray-200 border">
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Alat</th>
                                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kode Alat</th>
                                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jenis Alat</th>
                                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Jumlah Alat</th>
                                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    <tr v-for="eq in site.equipments" :key="eq.id">
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ eq.name }}</td>
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">{{ eq.code }}</td>
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">{{ eq.equipment_type_name }}</td>
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-center">
                                                            <span v-if="eq.status" class="text-green-600 font-semibold text-xs">Aktif</span>
                                                            <span v-else class="text-red-600 font-semibold text-xs">Non Aktif</span>
                                                        </td>
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-center text-gray-500">{{ eq.quantity }}</td>
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-center font-medium">
                                                            <button @click="openEditModal(site)" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </template>
                                <tr v-if="sites.length === 0">
                                    <td colspan="8" class="px-4 py-4 text-center text-sm text-gray-500">Tidak ada data site.</td>
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
                        <InputLabel for="status" value="Status" />
                        <select id="status" v-model="form.status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option :value="true">Aktif</option>
                            <option :value="false">Non Aktif</option>
                        </select>
                        <InputError :message="form.errors.status" class="mt-2" />
                    </div>
                </div>

                <div class="border-t pt-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-md font-medium text-gray-900">Daftar Alat di Site</h3>
                        <PrimaryButton type="button" @click="addEquipmentRow" class="text-xs">
                            + Tambah Alat
                        </PrimaryButton>
                    </div>

                    <div v-for="(eq, index) in form.equipments" :key="index" class="bg-gray-50 p-4 mb-4 rounded border grid grid-cols-1 md:grid-cols-6 gap-4 items-start relative">
                        <button type="button" @click="removeEquipmentRow(index)" class="absolute top-2 right-2 text-red-500 hover:text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div class="md:col-span-2">
                            <InputLabel :for="'eq_name_'+index" value="Nama Alat" />
                            <TextInput :id="'eq_name_'+index" v-model="eq.name" type="text" class="mt-1 block w-full text-sm" />
                            <InputError :message="form.errors[`equipments.${index}.name`]" class="mt-1" />
                        </div>

                        <div>
                            <InputLabel :for="'eq_code_'+index" value="Kode Alat" />
                            <TextInput :id="'eq_code_'+index" v-model="eq.code" type="text" class="mt-1 block w-full text-sm" />
                            <InputError :message="form.errors[`equipments.${index}.code`]" class="mt-1" />
                        </div>

                        <div class="md:col-span-1">
                            <InputLabel :for="'eq_type_'+index" value="Jenis Alat" />
                            <select :id="'eq_type_'+index" v-model="eq.equipment_type_id" class="mt-1 block w-full text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="" disabled>Pilih Jenis</option>
                                <option v-for="type in equipmentTypes" :key="type.id" :value="type.id">
                                    {{ type.name }}
                                </option>
                            </select>
                            <InputError :message="form.errors[`equipments.${index}.equipment_type_id`]" class="mt-1" />
                        </div>

                        <div>
                            <InputLabel :for="'eq_status_'+index" value="Status" />
                            <select :id="'eq_status_'+index" v-model="eq.status" class="mt-1 block w-full text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option :value="true">Aktif</option>
                                <option :value="false">Non Aktif</option>
                            </select>
                            <InputError :message="form.errors[`equipments.${index}.status`]" class="mt-1" />
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
                        file:bg-indigo-50 file:text-indigo-700
                        hover:file:bg-indigo-100"
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
