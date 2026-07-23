<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';

const props = defineProps({
    siteClasses: Array,
    positions: Array,
    requirements: Array,
});

// -- MATRIKS LOGIC --
const formMatrix = useForm({
    requirements: []
});

const initMatrix = () => {
    const gridData = [];
    props.positions.forEach(pos => {
        props.siteClasses.forEach(sc => {
            const existing = props.requirements.find(
                r => r.site_class_id === sc.id && r.non_technical_position_id === pos.id
            );
            gridData.push({
                site_class_id: sc.id,
                non_technical_position_id: pos.id,
                quantity: existing ? existing.quantity : 0
            });
        });
    });
    formMatrix.requirements = gridData;
};

onMounted(() => {
    initMatrix();
});

// Re-init matrix when props change (after adding/deleting site class or position)
watch(() => props.siteClasses, () => initMatrix(), { deep: true });
watch(() => props.positions, () => initMatrix(), { deep: true });
watch(() => props.requirements, () => initMatrix(), { deep: true });

const getQuantityModel = (posId, classId) => {
    return formMatrix.requirements.find(r => r.non_technical_position_id === posId && r.site_class_id === classId);
};

const formatWeightRange = (sc) => {
    if (!sc.max_weight || sc.max_weight === null) {
        return `> ${sc.min_weight - 1}`;
    }
    return `${sc.min_weight} - ${sc.max_weight}`;
};

const saveMatrix = () => {
    formMatrix.post(route('non-technical-requirements.store'), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            alert('Matriks berhasil disimpan!');
            initMatrix();
        },
    });
};

// -- KELAS SITE LOGIC --
const showSiteClassListModal = ref(false);
const showSiteClassFormModal = ref(false);
const isEditingSiteClass = ref(false);

const formSiteClass = useForm({
    id: null,
    name: '',
    min_weight: 0,
    max_weight: null,
});

const openSiteClassCreate = () => {
    isEditingSiteClass.value = false;
    formSiteClass.reset();
    showSiteClassListModal.value = false;
    showSiteClassFormModal.value = true;
};

const openSiteClassEdit = (item) => {
    isEditingSiteClass.value = true;
    formSiteClass.id = item.id;
    formSiteClass.name = item.name;
    formSiteClass.min_weight = item.min_weight;
    formSiteClass.max_weight = item.max_weight;
    showSiteClassListModal.value = false;
    showSiteClassFormModal.value = true;
};

const saveSiteClass = () => {
    if (isEditingSiteClass.value) {
        formSiteClass.put(route('site-classes.update', formSiteClass.id), {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                showSiteClassFormModal.value = false;
                showSiteClassListModal.value = true;
                initMatrix();
            },
        });
    } else {
        formSiteClass.post(route('site-classes.store'), {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                showSiteClassFormModal.value = false;
                showSiteClassListModal.value = true;
                initMatrix();
            },
        });
    }
};

const deleteSiteClass = (id) => {
    if (confirm('Yakin ingin menghapus kelas site ini? Data matriks terkait juga akan terhapus.')) {
        useForm({}).delete(route('site-classes.destroy', id), {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => initMatrix(),
        });
    }
};

// -- JABATAN LOGIC --
const showPositionListModal = ref(false);
const showPositionFormModal = ref(false);
const isEditingPosition = ref(false);

const formPosition = useForm({
    id: null,
    title: '',
    category: 'non-fungsional',
});

const openPositionCreate = () => {
    isEditingPosition.value = false;
    formPosition.reset();
    showPositionListModal.value = false;
    showPositionFormModal.value = true;
};

const openPositionEdit = (item) => {
    isEditingPosition.value = true;
    formPosition.id = item.id;
    formPosition.title = item.title;
    formPosition.category = item.category;
    showPositionListModal.value = false;
    showPositionFormModal.value = true;
};

const savePosition = () => {
    if (isEditingPosition.value) {
        formPosition.put(route('non-technical-positions.update', formPosition.id), {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                showPositionFormModal.value = false;
                showPositionListModal.value = true;
                initMatrix();
            },
        });
    } else {
        formPosition.post(route('non-technical-positions.store'), {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                showPositionFormModal.value = false;
                showPositionListModal.value = true;
                initMatrix();
            },
        });
    }
};

const deletePosition = (id) => {
    if (confirm('Yakin ingin menghapus jabatan ini? Data matriks terkait juga akan terhapus.')) {
        useForm({}).delete(route('non-technical-positions.destroy', id), {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => initMatrix(),
        });
    }
};
</script>

<template>
    <Head title="Matriks Kebutuhan SDM Pendukung" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Matriks Kebutuhan SDM Pendukung</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- PANEL PENGELOLAAN MASTER -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <!-- Card Kelas Site -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6 flex justify-between items-center border border-slate-200/80">
                        <div>
                            <h3 class="font-bold text-gray-800">Kelas Site</h3>
                            <p class="text-sm text-gray-500">Kelola master kolom</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click="showSiteClassListModal = true" type="button" class="h-9 px-3.5 inline-flex items-center gap-1.5 bg-white border border-slate-300 rounded-xl font-bold text-xs text-slate-700 shadow-sm hover:bg-pelindo-cyan/5 hover:border-pelindo-cyan hover:text-pelindo-blue transition">
                                <svg class="w-3.5 h-3.5 text-pelindo-blue shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                <span>Daftar Kelas</span>
                            </button>
                            <button @click="openSiteClassCreate" type="button" class="h-9 px-3.5 inline-flex items-center gap-1.5 bg-gradient-to-r from-pelindo-blue to-[#003B6F] hover:opacity-90 text-white font-bold text-xs rounded-xl shadow-sm transition">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>Tambah Kelas</span>
                            </button>
                        </div>
                    </div>

                    <!-- Card Jabatan -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6 flex justify-between items-center border border-slate-200/80">
                        <div>
                            <h3 class="font-bold text-gray-800">Jabatan Non-Teknis</h3>
                            <p class="text-sm text-gray-500">Kelola master baris</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click="showPositionListModal = true" type="button" class="h-9 px-3.5 inline-flex items-center gap-1.5 bg-white border border-slate-300 rounded-xl font-bold text-xs text-slate-700 shadow-sm hover:bg-pelindo-cyan/5 hover:border-pelindo-cyan hover:text-pelindo-blue transition">
                                <svg class="w-3.5 h-3.5 text-pelindo-blue shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                <span>Daftar Jabatan</span>
                            </button>
                            <button @click="openPositionCreate" type="button" class="h-9 px-3.5 inline-flex items-center gap-1.5 bg-gradient-to-r from-pelindo-blue to-[#003B6F] hover:opacity-90 text-white font-bold text-xs rounded-xl shadow-sm transition">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>Tambah Jabatan</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- MATRIKS -->
                <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6 border border-slate-200/80">
                    <p class="mb-4 text-gray-600">Isi jumlah kebutuhan (kuantitas) SDM untuk setiap jabatan berdasarkan kelas site.</p>
                    
                    <form @submit.prevent="saveMatrix">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border">
                                <thead class="bg-slate-800 text-white">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider bg-slate-800 sticky left-0 z-10">
                                            Jabatan \ Kelas Site
                                        </th>
                                        <th v-for="sc in siteClasses" :key="sc.id" class="px-4 py-3 text-center tracking-wider">
                                            <div class="text-xs font-bold text-white uppercase">{{ sc.name }}</div>
                                            <div class="mt-1 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-pelindo-cyan/20 text-pelindo-cyan border border-pelindo-cyan/30 shadow-sm">
                                                ⚖️ {{ sc.max_weight !== null ? $formatNumber(sc.min_weight) + ' - ' + $formatNumber(sc.max_weight) : '> ' + $formatNumber(sc.min_weight - 1) }}
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="pos in positions" :key="pos.id" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap bg-white sticky left-0 border-r shadow-sm z-10">
                                            <div class="font-medium text-gray-900">{{ pos.title }}</div>
                                            <div class="text-xs text-gray-500 capitalize">{{ pos.category }}</div>
                                        </td>
                                        <td v-for="sc in siteClasses" :key="sc.id" class="px-4 py-4 whitespace-nowrap text-center">
                                            <template v-if="getQuantityModel(pos.id, sc.id)">
                                                <input 
                                                    type="number" 
                                                    min="0"
                                                    class="w-20 text-center border-gray-300 focus:border-pelindo-blue focus:ring-pelindo-blue rounded-md shadow-sm"
                                                    v-model="getQuantityModel(pos.id, sc.id).quantity"
                                                />
                                            </template>
                                        </td>
                                    </tr>
                                    <tr v-if="positions.length === 0 || siteClasses.length === 0">
                                        <td :colspan="siteClasses.length + 1" class="px-6 py-4 text-center text-gray-500">
                                            Tambahkan Kelas Site dan Jabatan melalui tombol di atas untuk mulai mengisi matriks.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <PrimaryButton :disabled="formMatrix.processing || positions.length === 0 || siteClasses.length === 0">
                                Simpan Perubahan Matriks
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ======================= MODALS KELAS SITE ======================= -->
        
        <!-- Modal List Kelas Site -->
        <Modal :show="showSiteClassListModal" @close="showSiteClassListModal = false" maxWidth="2xl">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-medium text-gray-900">Daftar Kelas Site</h2>
                    <button @click="openSiteClassCreate" type="button" class="h-9 px-3.5 inline-flex items-center gap-1.5 bg-gradient-to-r from-pelindo-blue to-[#003B6F] hover:opacity-90 text-white font-bold text-xs rounded-xl shadow-sm transition">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Tambah Baru</span>
                    </button>
                </div>
                
                <table class="min-w-full divide-y divide-gray-200 border mt-4">
                    <thead class="bg-slate-800 text-white">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-wider">Nama Kelas</th>
                            <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-wider">Min Bobot</th>
                            <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-wider">Max Bobot</th>
                            <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-wider w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="item in siteClasses" :key="item.id">
                            <td class="px-4 py-2 whitespace-nowrap">{{ item.name }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ $formatNumber(item.min_weight) }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ item.max_weight !== null ? $formatNumber(item.max_weight) : '∞' }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <button @click="openSiteClassEdit(item)" class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 hover:bg-pelindo-blue text-pelindo-blue hover:text-white rounded-lg text-xs font-bold transition duration-150" title="Edit Kelas">
                                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span>Edit</span>
                                    </button>
                                    <button @click="deleteSiteClass(item.id)" class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-50 hover:bg-red-600 text-red-600 hover:text-white rounded-lg text-xs font-bold transition duration-150" title="Hapus Kelas">
                                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        <span>Delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="siteClasses.length === 0">
                            <td colspan="4" class="px-4 py-2 text-center text-gray-500">Tidak ada data.</td>
                        </tr>
                    </tbody>
                </table>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="showSiteClassListModal = false">Tutup</SecondaryButton>
                </div>
            </div>
        </Modal>

        <!-- Modal Form Kelas Site -->
        <Modal :show="showSiteClassFormModal" @close="showSiteClassFormModal = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    {{ isEditingSiteClass ? 'Edit Kelas Site' : 'Tambah Kelas Site' }}
                </h2>
                <form @submit.prevent="saveSiteClass">
                    <div class="mb-4">
                        <InputLabel for="site_name" value="Nama Kelas Site" />
                        <TextInput id="site_name" type="text" class="mt-1 block w-full" v-model="formSiteClass.name" required />
                        <div v-if="formSiteClass.errors.name" class="text-red-500 text-sm mt-1">{{ formSiteClass.errors.name }}</div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <InputLabel for="min_weight" value="Min Bobot" />
                            <TextInput id="min_weight" type="number" class="mt-1 block w-full" v-model="formSiteClass.min_weight" required />
                            <div v-if="formSiteClass.errors.min_weight" class="text-red-500 text-sm mt-1">{{ formSiteClass.errors.min_weight }}</div>
                        </div>
                        <div>
                            <InputLabel for="max_weight" value="Max Bobot (Kosongkan jika tidak terbatas)" />
                            <TextInput id="max_weight" type="number" class="mt-1 block w-full" v-model="formSiteClass.max_weight" />
                            <div v-if="formSiteClass.errors.max_weight" class="text-red-500 text-sm mt-1">{{ formSiteClass.errors.max_weight }}</div>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <SecondaryButton @click="showSiteClassFormModal = false; showSiteClassListModal = true">Batal</SecondaryButton>
                        <PrimaryButton :disabled="formSiteClass.processing">Simpan</PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- ======================= MODALS JABATAN ======================= -->
        
        <!-- Modal List Jabatan -->
        <Modal :show="showPositionListModal" @close="showPositionListModal = false" maxWidth="2xl">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-medium text-gray-900">Daftar Jabatan Non-Teknis</h2>
                    <button @click="openPositionCreate" type="button" class="h-9 px-3.5 inline-flex items-center gap-1.5 bg-gradient-to-r from-pelindo-blue to-[#003B6F] hover:opacity-90 text-white font-bold text-xs rounded-xl shadow-sm transition">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Tambah Baru</span>
                    </button>
                </div>
                
                <table class="min-w-full divide-y divide-gray-200 border mt-4">
                    <thead class="bg-slate-800 text-white">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-wider">Nama Jabatan</th>
                            <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-wider">Kategori</th>
                            <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-wider w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="item in positions" :key="item.id">
                            <td class="px-4 py-2 whitespace-nowrap">{{ item.title }}</td>
                            <td class="px-4 py-2 whitespace-nowrap capitalize">{{ item.category }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <button @click="openPositionEdit(item)" class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 hover:bg-pelindo-blue text-pelindo-blue hover:text-white rounded-lg text-xs font-bold transition duration-150" title="Edit Jabatan">
                                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span>Edit</span>
                                    </button>
                                    <button @click="deletePosition(item.id)" class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-50 hover:bg-red-600 text-red-600 hover:text-white rounded-lg text-xs font-bold transition duration-150" title="Hapus Jabatan">
                                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        <span>Delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="positions.length === 0">
                            <td colspan="3" class="px-4 py-2 text-center text-gray-500">Tidak ada data.</td>
                        </tr>
                    </tbody>
                </table>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="showPositionListModal = false">Tutup</SecondaryButton>
                </div>
            </div>
        </Modal>

        <!-- Modal Form Jabatan -->
        <Modal :show="showPositionFormModal" @close="showPositionFormModal = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    {{ isEditingPosition ? 'Edit Jabatan' : 'Tambah Jabatan' }}
                </h2>
                <form @submit.prevent="savePosition">
                    <div class="mb-4">
                        <InputLabel for="pos_title" value="Nama Jabatan" />
                        <TextInput id="pos_title" type="text" class="mt-1 block w-full" v-model="formPosition.title" required />
                        <div v-if="formPosition.errors.title" class="text-red-500 text-sm mt-1">{{ formPosition.errors.title }}</div>
                    </div>
                    <div>
                        <InputLabel for="pos_category" value="Kategori" />
                        <select id="pos_category" v-model="formPosition.category" class="mt-1 block w-full border-gray-300 focus:border-pelindo-blue focus:ring-pelindo-blue rounded-md shadow-sm">
                            <option value="fungsional">Fungsional</option>
                            <option value="non-fungsional">Non-Fungsional</option>
                        </select>
                        <div v-if="formPosition.errors.category" class="text-red-500 text-sm mt-1">{{ formPosition.errors.category }}</div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <SecondaryButton @click="showPositionFormModal = false; showPositionListModal = true">Batal</SecondaryButton>
                        <PrimaryButton :disabled="formPosition.processing">Simpan</PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>
