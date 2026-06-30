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

defineProps({
    baselines: Array,
});

const showModal = ref(false);
const isEditing = ref(false);

const form = useForm({
    id: null,
    category: '',
    baseline: 0,
});

const openCreateModal = () => {
    isEditing.value = false;
    form.reset();
    form.clearErrors();
    showModal.value = true;
};

const openEditModal = (baseline) => {
    isEditing.value = true;
    form.clearErrors();
    form.id = baseline.id;
    form.category = baseline.category;
    form.baseline = baseline.baseline;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    form.reset();
};

const saveBaseline = () => {
    if (isEditing.value) {
        form.put(route('equipment-category-baselines.update', form.id), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('equipment-category-baselines.store'), {
            onSuccess: () => closeModal(),
        });
    }
};

const deleteBaseline = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus baseline kategori ini?')) {
        form.delete(route('equipment-category-baselines.destroy', id));
    }
};
</script>

<template>
    <Head title="Master Baseline Kategori Alat" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Master Baseline Kategori Alat</h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                
                <div class="mb-6 flex justify-end">
                    <button @click="openCreateModal" type="button" class="h-10 px-5 inline-flex items-center gap-2 bg-gradient-to-r from-pelindo-blue to-[#003B6F] border border-pelindo-cyan/20 hover:opacity-90 text-white text-xs font-bold rounded-xl shadow-md transition uppercase tracking-wider">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Tambah Baseline</span>
                    </button>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-200/80 p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-slate-800 text-white">
                                <tr>
                                    <th class="py-3 px-6 text-left text-xs font-bold uppercase tracking-wider">Kategori Alat</th>
                                    <th class="py-3 px-6 text-center text-xs font-bold uppercase tracking-wider">Baseline Teknisi (Orang)</th>
                                    <th class="py-3 px-6 text-center text-xs font-bold uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="baseline in baselines" :key="baseline.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ baseline.category }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-bold text-pelindo-blue">{{ baseline.baseline }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center font-medium">
                                        <div class="inline-flex items-center justify-center gap-2">
                                            <button @click="openEditModal(baseline)" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-pelindo-blue text-pelindo-blue hover:text-white rounded-xl text-xs font-bold shadow-sm transition duration-150" title="Edit Baseline">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                <span>Edit</span>
                                            </button>
                                            <button @click="deleteBaseline(baseline.id)" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 hover:bg-red-600 text-red-600 hover:text-white rounded-xl text-xs font-bold shadow-sm transition duration-150" title="Hapus Baseline">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                <span>Delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="baselines.length === 0">
                                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data baseline kategori.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="showModal" @close="closeModal" maxWidth="md">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    {{ isEditing ? 'Edit Baseline Kategori' : 'Tambah Baseline Kategori' }}
                </h2>

                <div class="space-y-4">
                    <div>
                        <InputLabel for="category" value="Kategori Alat" />
                        <TextInput id="category" v-model="form.category" type="text" class="mt-1 block w-full" placeholder="Contoh: Crane, Mobile Equipment, Others" />
                        <InputError :message="form.errors.category" class="mt-2" />
                    </div>
                    <div>
                        <InputLabel for="baseline" value="Baseline Kebutuhan Teknisi" />
                        <TextInput id="baseline" v-model="form.baseline" type="number" min="0" class="mt-1 block w-full" />
                        <InputError :message="form.errors.baseline" class="mt-2" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">Batal</SecondaryButton>
                    <PrimaryButton @click="saveBaseline" class="ms-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Simpan
                    </PrimaryButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
