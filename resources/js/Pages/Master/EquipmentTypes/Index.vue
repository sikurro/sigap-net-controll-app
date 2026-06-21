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
    equipmentTypes: Array,
});

const showModal = ref(false);
const isEditing = ref(false);

const form = useForm({
    id: null,
    name: '',
    code: '',
});

const openCreateModal = () => {
    isEditing.value = false;
    form.reset();
    showModal.value = true;
};

const openEditModal = (item) => {
    isEditing.value = true;
    form.id = item.id;
    form.name = item.name;
    form.code = item.code || '';
    showModal.value = true;
};

const save = () => {
    if (isEditing.value) {
        form.put(route('equipment-types.update', form.id), {
            onSuccess: () => showModal.value = false,
        });
    } else {
        form.post(route('equipment-types.store'), {
            onSuccess: () => showModal.value = false,
        });
    }
};

const deleteItem = (id) => {
    if (confirm('Yakin ingin menghapus jenis alat ini?')) {
        form.delete(route('equipment-types.destroy', id));
    }
};
</script>

<template>
    <Head title="Master Jenis Alat" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Master Jenis Alat</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-end mb-4">
                        <PrimaryButton @click="openCreateModal">Tambah Jenis Alat</PrimaryButton>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200 border">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Alat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="item in equipmentTypes" :key="item.id">
                                <td class="px-6 py-4 whitespace-nowrap">{{ item.code || '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ item.name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                    <SecondaryButton @click="openEditModal(item)">Edit</SecondaryButton>
                                    <DangerButton @click="deleteItem(item.id)">Hapus</DangerButton>
                                </td>
                            </tr>
                            <tr v-if="equipmentTypes.length === 0">
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
                    {{ isEditing ? 'Edit Jenis Alat' : 'Tambah Jenis Alat' }}
                </h2>
                
                <form @submit.prevent="save">
                    <div class="mb-4">
                        <InputLabel for="code" value="Kode Alat" />
                        <TextInput id="code" type="text" class="mt-1 block w-full" v-model="form.code" />
                        <div v-if="form.errors.code" class="text-red-500 text-sm mt-1">{{ form.errors.code }}</div>
                    </div>
                    <div>
                        <InputLabel for="name" value="Nama Alat" />
                        <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required />
                        <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</div>
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
