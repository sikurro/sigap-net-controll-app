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
    siteClasses: Array,
});

const showModal = ref(false);
const isEditing = ref(false);

const form = useForm({
    id: null,
    name: '',
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
    showModal.value = true;
};

const save = () => {
    if (isEditing.value) {
        form.put(route('site-classes.update', form.id), {
            onSuccess: () => showModal.value = false,
        });
    } else {
        form.post(route('site-classes.store'), {
            onSuccess: () => showModal.value = false,
        });
    }
};

const deleteItem = (id) => {
    if (confirm('Yakin ingin menghapus kelas site ini?')) {
        form.delete(route('site-classes.destroy', id));
    }
};
</script>

<template>
    <Head title="Master Kelas Site" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Master Kelas Site</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-end mb-4">
                        <PrimaryButton @click="openCreateModal">Tambah Kelas Site</PrimaryButton>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200 border">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kelas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="item in siteClasses" :key="item.id">
                                <td class="px-6 py-4 whitespace-nowrap">{{ item.name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                    <SecondaryButton @click="openEditModal(item)">Edit</SecondaryButton>
                                    <DangerButton @click="deleteItem(item.id)">Hapus</DangerButton>
                                </td>
                            </tr>
                            <tr v-if="siteClasses.length === 0">
                                <td colspan="2" class="px-6 py-4 text-center text-gray-500">Tidak ada data.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <Modal :show="showModal" @close="showModal = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    {{ isEditing ? 'Edit Kelas Site' : 'Tambah Kelas Site' }}
                </h2>
                
                <form @submit.prevent="save">
                    <div>
                        <InputLabel for="name" value="Nama Kelas Site" />
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
