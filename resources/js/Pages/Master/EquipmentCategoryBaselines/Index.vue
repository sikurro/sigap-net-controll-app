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
                
                <div class="mb-4 flex justify-end">
                    <PrimaryButton @click="openCreateModal">
                        Tambah Baseline
                    </PrimaryButton>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="pb-4 pt-6 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori Alat</th>
                                    <th class="pb-4 pt-6 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Baseline Teknisi (Orang)</th>
                                    <th class="pb-4 pt-6 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="baseline in baselines" :key="baseline.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ baseline.category }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-semibold text-indigo-600">{{ baseline.baseline }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium">
                                        <button @click="openEditModal(baseline)" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                        <button @click="deleteBaseline(baseline.id)" class="text-red-600 hover:text-red-900">Delete</button>
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
