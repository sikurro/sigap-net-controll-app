<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';

const props = defineProps({
    positions: Array,
});

const showModal = ref(false);
const isEditing = ref(false);

const form = useForm({
    id: null,
    title: '',
    category: 'non-fungsional',
});

const openCreateModal = () => {
    isEditing.value = false;
    form.reset();
    showModal.value = true;
};

const openEditModal = (item) => {
    isEditing.value = true;
    form.id = item.id;
    form.title = item.title;
    form.category = item.category;
    showModal.value = true;
};

const save = () => {
    if (isEditing.value) {
        form.put(route('non-technical-positions.update', form.id), {
            onSuccess: () => showModal.value = false,
        });
    } else {
        form.post(route('non-technical-positions.store'), {
            onSuccess: () => showModal.value = false,
        });
    }
};

const deleteItem = (id) => {
    if (confirm('Yakin ingin menghapus jabatan ini?')) {
        form.delete(route('non-technical-positions.destroy', id));
    }
};
</script>

<template>
    <Head title="Master Jabatan Non-Teknis" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Master Jabatan Non-Teknis</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-end mb-4">
                        <PrimaryButton @click="openCreateModal">Tambah Jabatan</PrimaryButton>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200 border">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Jabatan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="item in positions" :key="item.id">
                                <td class="px-6 py-4 whitespace-nowrap">{{ item.title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap capitalize">{{ item.category }}</td>
                                <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                    <SecondaryButton @click="openEditModal(item)">Edit</SecondaryButton>
                                    <DangerButton @click="deleteItem(item.id)">Hapus</DangerButton>
                                </td>
                            </tr>
                            <tr v-if="positions.length === 0">
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">Tidak ada data.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <Modal :show="showModal" @close="showModal = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    {{ isEditing ? 'Edit Jabatan' : 'Tambah Jabatan' }}
                </h2>
                
                <form @submit.prevent="save">
                    <div class="mb-4">
                        <InputLabel for="title" value="Nama Jabatan" />
                        <TextInput id="title" type="text" class="mt-1 block w-full" v-model="form.title" required />
                        <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">{{ form.errors.title }}</div>
                    </div>
                    <div>
                        <InputLabel for="category" value="Kategori" />
                        <select id="category" v-model="form.category" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="fungsional">Fungsional</option>
                            <option value="non-fungsional">Non-Fungsional</option>
                        </select>
                        <div v-if="form.errors.category" class="text-red-500 text-sm mt-1">{{ form.errors.category }}</div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <SecondaryButton @click="showModal = false">Batal</SecondaryButton>
                        <PrimaryButton :disabled="form.processing">Simpan</PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
