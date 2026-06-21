<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';

const props = defineProps({
    jobPlans: Array,
    equipmentTypes: Array,
});

const showModal = ref(false);
const isEditing = ref(false);

const form = useForm({
    id: null,
    equipment_type_id: '',
    activity_name: '',
    duration_hours: 0,
    frequency_per_year: 0,
});

const totalHoursComputed = computed(() => {
    return (parseFloat(form.duration_hours) || 0) * (parseInt(form.frequency_per_year) || 0);
});

const openCreateModal = () => {
    isEditing.value = false;
    form.reset();
    showModal.value = true;
};

const openEditModal = (item) => {
    isEditing.value = true;
    form.id = item.id;
    form.equipment_type_id = item.equipment_type_id;
    form.activity_name = item.activity_name;
    form.duration_hours = item.duration_hours;
    form.frequency_per_year = item.frequency_per_year;
    showModal.value = true;
};

const save = () => {
    if (isEditing.value) {
        form.put(route('job-plans.update', form.id), {
            onSuccess: () => showModal.value = false,
        });
    } else {
        form.post(route('job-plans.store'), {
            onSuccess: () => showModal.value = false,
        });
    }
};

const deleteItem = (id) => {
    if (confirm('Yakin ingin menghapus Job Plan ini?')) {
        form.delete(route('job-plans.destroy', id));
    }
};
</script>

<template>
    <Head title="Master Job Plan" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Master Job Plan</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-end mb-4">
                        <PrimaryButton @click="openCreateModal">Tambah Job Plan</PrimaryButton>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200 border">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Alat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aktivitas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durasi (Jam)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Frekuensi/Thn</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total/Thn</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="item in jobPlans" :key="item.id">
                                <td class="px-6 py-4 whitespace-nowrap">{{ item.equipment_type?.name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ item.activity_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ item.duration_hours }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ item.frequency_per_year }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold">{{ item.total_hours_per_year }}</td>
                                <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                    <SecondaryButton @click="openEditModal(item)">Edit</SecondaryButton>
                                    <DangerButton @click="deleteItem(item.id)">Hapus</DangerButton>
                                </td>
                            </tr>
                            <tr v-if="jobPlans.length === 0">
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <Modal :show="showModal" @close="showModal = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    {{ isEditing ? 'Edit Job Plan' : 'Tambah Job Plan' }}
                </h2>
                
                <form @submit.prevent="save">
                    <div class="mb-4">
                        <InputLabel for="equipment_type" value="Jenis Alat" />
                        <select id="equipment_type" v-model="form.equipment_type_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            <option value="">-- Pilih Jenis Alat --</option>
                            <option v-for="eq in equipmentTypes" :key="eq.id" :value="eq.id">{{ eq.name }}</option>
                        </select>
                        <div v-if="form.errors.equipment_type_id" class="text-red-500 text-sm mt-1">{{ form.errors.equipment_type_id }}</div>
                    </div>
                    
                    <div class="mb-4">
                        <InputLabel for="activity_name" value="Nama Aktivitas" />
                        <TextInput id="activity_name" type="text" class="mt-1 block w-full" v-model="form.activity_name" required />
                        <div v-if="form.errors.activity_name" class="text-red-500 text-sm mt-1">{{ form.errors.activity_name }}</div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <InputLabel for="duration_hours" value="Durasi (Jam)" />
                            <TextInput id="duration_hours" type="number" step="0.01" class="mt-1 block w-full" v-model="form.duration_hours" required />
                            <div v-if="form.errors.duration_hours" class="text-red-500 text-sm mt-1">{{ form.errors.duration_hours }}</div>
                        </div>
                        <div>
                            <InputLabel for="frequency_per_year" value="Frekuensi / Tahun" />
                            <TextInput id="frequency_per_year" type="number" class="mt-1 block w-full" v-model="form.frequency_per_year" required />
                            <div v-if="form.errors.frequency_per_year" class="text-red-500 text-sm mt-1">{{ form.errors.frequency_per_year }}</div>
                        </div>
                    </div>

                    <div class="mb-4 p-4 bg-gray-50 rounded-md flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Jam / Tahun:</span>
                        <span class="font-bold text-lg text-indigo-600">{{ totalHoursComputed }} Jam</span>
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
