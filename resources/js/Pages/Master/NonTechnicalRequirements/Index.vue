<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    siteClasses: Array,
    positions: Array,
    requirements: Array,
});

const form = useForm({
    requirements: []
});

onMounted(() => {
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
    
    form.requirements = gridData;
});

const getQuantityModel = (posId, classId) => {
    return form.requirements.find(r => r.non_technical_position_id === posId && r.site_class_id === classId);
};

const save = () => {
    form.post(route('non-technical-requirements.store'), {
        preserveScroll: true,
        onSuccess: () => alert('Matriks berhasil disimpan!'),
    });
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
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="mb-4 text-gray-600">Isi jumlah kebutuhan (kuantitas) SDM untuk setiap jabatan berdasarkan kelas site.</p>
                    
                    <form @submit.prevent="save">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100 sticky left-0 z-10">
                                            Jabatan \ Kelas Site
                                        </th>
                                        <th v-for="sc in siteClasses" :key="sc.id" class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            {{ sc.name }}
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
                                                    class="w-20 text-center border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                                    v-model="getQuantityModel(pos.id, sc.id).quantity"
                                                />
                                            </template>
                                        </td>
                                    </tr>
                                    <tr v-if="positions.length === 0 || siteClasses.length === 0">
                                        <td :colspan="siteClasses.length + 1" class="px-6 py-4 text-center text-gray-500">
                                            Pastikan Master Kelas Site dan Master Jabatan Non-Teknis sudah diisi terlebih dahulu.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <PrimaryButton :disabled="form.processing || positions.length === 0 || siteClasses.length === 0">
                                Simpan Perubahan Matriks
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
