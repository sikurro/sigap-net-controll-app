<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';

const props = defineProps({
    equipmentTypes: Array,
});

// -- SEARCH & EXPAND LOGIC --
const searchQuery = ref('');
const expandedRows = ref(new Set());

const filteredEquipmentTypes = computed(() => {
    if (!searchQuery.value) return props.equipmentTypes;
    return props.equipmentTypes.filter(eq => 
        eq.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        (eq.code && eq.code.toLowerCase().includes(searchQuery.value.toLowerCase()))
    );
});

const toggleExpand = (id) => {
    if (expandedRows.value.has(id)) {
        expandedRows.value.delete(id);
    } else {
        expandedRows.value.add(id);
    }
};

const isExpanded = (id) => expandedRows.value.has(id);

// Total calculators
const getTotalHours = (jobPlans) => {
    if (!jobPlans) return 0;
    return jobPlans.reduce((sum, jp) => sum + parseFloat(jp.total_hours_per_year || 0), 0);
};

// -- EQUIPMENT TYPE LOGIC --
const showEquipmentModal = ref(false);
const isEditingEquipment = ref(false);

const formEquipment = useForm({
    id: null,
    name: '',
    code: '',
});

const openEquipmentCreate = () => {
    isEditingEquipment.value = false;
    formEquipment.reset();
    showEquipmentModal.value = true;
};

const openEquipmentEdit = (item) => {
    isEditingEquipment.value = true;
    formEquipment.id = item.id;
    formEquipment.name = item.name;
    formEquipment.code = item.code;
    showEquipmentModal.value = true;
};

const saveEquipment = () => {
    if (isEditingEquipment.value) {
        formEquipment.put(route('equipment-types.update', formEquipment.id), {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => showEquipmentModal.value = false,
        });
    } else {
        formEquipment.post(route('equipment-types.store'), {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                showEquipmentModal.value = false;
                formEquipment.reset();
            },
        });
    }
};

const deleteEquipment = (id) => {
    if (confirm('Yakin ingin menghapus jenis alat ini? Semua Job Plan terkait juga akan terhapus.')) {
        useForm({}).delete(route('equipment-types.destroy', id), {
            preserveScroll: true,
            preserveState: true,
        });
    }
};

// -- JOB PLAN LOGIC --
const showJobPlanModal = ref(false);
const isEditingJobPlan = ref(false);

const formJobPlan = useForm({
    id: null,
    equipment_type_id: null,
    activity_name: '',
    duration_hours: '',
    frequency_per_year: '',
});

const totalHoursCalculated = computed(() => {
    const dur = parseFloat(formJobPlan.duration_hours) || 0;
    const freq = parseFloat(formJobPlan.frequency_per_year) || 0;
    return dur * freq;
});

const openJobPlanCreate = (equipmentId) => {
    isEditingJobPlan.value = false;
    formJobPlan.reset();
    formJobPlan.equipment_type_id = equipmentId;
    showJobPlanModal.value = true;
};

const openJobPlanEdit = (item) => {
    isEditingJobPlan.value = true;
    formJobPlan.id = item.id;
    formJobPlan.equipment_type_id = item.equipment_type_id;
    formJobPlan.activity_name = item.activity_name;
    formJobPlan.duration_hours = item.duration_hours;
    formJobPlan.frequency_per_year = item.frequency_per_year;
    showJobPlanModal.value = true;
};

const saveJobPlan = () => {
    const payload = formJobPlan.transform((data) => ({
        ...data,
        total_hours_per_year: totalHoursCalculated.value
    }));

    if (isEditingJobPlan.value) {
        payload.put(route('job-plans.update', formJobPlan.id), {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                showJobPlanModal.value = false;
                formJobPlan.reset();
            },
        });
    } else {
        payload.post(route('job-plans.store'), {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                showJobPlanModal.value = false;
                formJobPlan.reset();
            },
        });
    }
};

const deleteJobPlan = (id) => {
    if (confirm('Yakin ingin menghapus job plan ini?')) {
        useForm({}).delete(route('job-plans.destroy', id), {
            preserveScroll: true,
            preserveState: true,
        });
    }
};
</script>

<template>
    <Head title="Alat & Job Plan" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Master Alat & Job Plan</h2>
                <PrimaryButton @click="openEquipmentCreate">➕ Tambah Jenis Alat</PrimaryButton>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- PANEL PENCARIAN -->
                <div class="mb-4 bg-white p-4 shadow-sm rounded-lg flex justify-between items-center">
                    <div class="w-1/3">
                        <TextInput 
                            type="text" 
                            class="w-full text-sm" 
                            placeholder="🔍 Cari jenis alat atau kode..." 
                            v-model="searchQuery"
                        />
                    </div>
                    <div class="text-sm text-gray-500">
                        Menampilkan {{ filteredEquipmentTypes.length }} jenis alat.
                    </div>
                </div>

                <!-- TABEL MASTER-DETAIL -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="w-10 px-6 py-3"></th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Jenis Alat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ringkasan</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <template v-for="eq in filteredEquipmentTypes" :key="eq.id">
                                    <!-- Baris Induk (Jenis Alat) -->
                                    <tr class="hover:bg-gray-50 cursor-pointer transition" @click="toggleExpand(eq.id)">
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                            <span class="transform transition-transform inline-block" :class="{'rotate-90': isExpanded(eq.id)}">
                                                ▶️
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-600">{{ eq.code || '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">{{ eq.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex space-x-2">
                                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-semibold">
                                                    {{ eq.job_plans ? eq.job_plans.length : 0 }} Job Plan
                                                </span>
                                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-semibold">
                                                    {{ getTotalHours(eq.job_plans) }} Jam/Thn
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right space-x-2" @click.stop>
                                            <SecondaryButton class="text-xs px-2 py-1" @click="openEquipmentEdit(eq)">Edit</SecondaryButton>
                                            <DangerButton class="text-xs px-2 py-1" @click="deleteEquipment(eq.id)">Hapus</DangerButton>
                                        </td>
                                    </tr>

                                    <!-- Baris Anak (Job Plan Nested) -->
                                    <tr v-show="isExpanded(eq.id)" class="bg-slate-50">
                                        <td colspan="5" class="p-0 border-l-4 border-indigo-300">
                                            <div class="p-6">
                                                <div class="flex justify-between items-center mb-3">
                                                    <h4 class="font-bold text-gray-700">Daftar Job Plan - {{ eq.name }}</h4>
                                                    <PrimaryButton class="text-xs py-1" @click="openJobPlanCreate(eq.id)">
                                                        ➕ Tambah Job Plan
                                                    </PrimaryButton>
                                                </div>

                                                <!-- Tabel Job Plan -->
                                                <div v-if="eq.job_plans && eq.job_plans.length > 0" class="border rounded-lg bg-white shadow-sm overflow-hidden">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead class="bg-gray-100">
                                                            <tr>
                                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aktivitas</th>
                                                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Durasi (Jam)</th>
                                                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Frekuensi/Thn</th>
                                                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Total Jam/Thn</th>
                                                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase w-32">Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="divide-y divide-gray-100">
                                                            <tr v-for="jp in eq.job_plans" :key="jp.id" class="hover:bg-gray-50">
                                                                <td class="px-4 py-2 text-sm font-medium text-gray-900">{{ jp.activity_name }}</td>
                                                                <td class="px-4 py-2 text-sm text-center text-gray-600">{{ jp.duration_hours }}</td>
                                                                <td class="px-4 py-2 text-sm text-center text-gray-600">{{ jp.frequency_per_year }} x</td>
                                                                <td class="px-4 py-2 text-sm text-center font-bold text-indigo-600">{{ jp.total_hours_per_year }}</td>
                                                                <td class="px-4 py-2 text-right space-x-3">
                                                                    <button @click="openJobPlanEdit(jp)" class="text-blue-600 hover:text-blue-900 text-xs font-medium">Edit</button>
                                                                    <button @click="deleteJobPlan(jp.id)" class="text-red-600 hover:text-red-900 text-xs font-medium">Hapus</button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                
                                                <!-- Empty State Job Plan -->
                                                <div v-else class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-md flex items-center">
                                                    <div class="flex-shrink-0 text-2xl mr-3">⚠️</div>
                                                    <div>
                                                        <p class="text-sm text-yellow-800 font-semibold">Alat ini belum memiliki Job Plan!</p>
                                                        <p class="text-xs text-yellow-700">Alat tanpa Job Plan tidak akan diproses dalam perencanaan penjadwalan. Silakan tambah Job Plan sekarang.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                
                                <tr v-if="filteredEquipmentTypes.length === 0">
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        Tidak ada data Jenis Alat yang ditemukan.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ======================= MODAL JENIS ALAT ======================= -->
        <Modal :show="showEquipmentModal" @close="showEquipmentModal = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    {{ isEditingEquipment ? 'Edit Jenis Alat' : 'Tambah Jenis Alat' }}
                </h2>
                <form @submit.prevent="saveEquipment">
                    <div class="mb-4">
                        <InputLabel for="eq_code" value="Kode Alat (Opsional)" />
                        <TextInput id="eq_code" type="text" class="mt-1 block w-full" v-model="formEquipment.code" />
                        <div v-if="formEquipment.errors.code" class="text-red-500 text-sm mt-1">{{ formEquipment.errors.code }}</div>
                    </div>
                    <div>
                        <InputLabel for="eq_name" value="Nama Jenis Alat" />
                        <TextInput id="eq_name" type="text" class="mt-1 block w-full" v-model="formEquipment.name" required />
                        <div v-if="formEquipment.errors.name" class="text-red-500 text-sm mt-1">{{ formEquipment.errors.name }}</div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <SecondaryButton @click="showEquipmentModal = false">Batal</SecondaryButton>
                        <PrimaryButton :disabled="formEquipment.processing">Simpan</PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- ======================= MODAL JOB PLAN ======================= -->
        <Modal :show="showJobPlanModal" @close="showJobPlanModal = false" maxWidth="md">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    {{ isEditingJobPlan ? 'Edit Job Plan' : 'Tambah Job Plan' }}
                </h2>
                <form @submit.prevent="saveJobPlan">
                    <div class="mb-4">
                        <InputLabel for="jp_activity" value="Nama Aktivitas (Service / Inspeksi)" />
                        <TextInput id="jp_activity" type="text" class="mt-1 block w-full" v-model="formJobPlan.activity_name" required />
                        <div v-if="formJobPlan.errors.activity_name" class="text-red-500 text-sm mt-1">{{ formJobPlan.errors.activity_name }}</div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <InputLabel for="jp_duration" value="Durasi Pekerjaan (Jam)" />
                            <TextInput id="jp_duration" type="number" step="0.1" class="mt-1 block w-full" v-model="formJobPlan.duration_hours" required />
                            <div v-if="formJobPlan.errors.duration_hours" class="text-red-500 text-sm mt-1">{{ formJobPlan.errors.duration_hours }}</div>
                        </div>
                        <div>
                            <InputLabel for="jp_frequency" value="Frekuensi per Tahun" />
                            <TextInput id="jp_frequency" type="number" step="0.1" class="mt-1 block w-full" v-model="formJobPlan.frequency_per_year" required />
                            <div v-if="formJobPlan.errors.frequency_per_year" class="text-red-500 text-sm mt-1">{{ formJobPlan.errors.frequency_per_year }}</div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-md mb-6 flex justify-between items-center border border-gray-200">
                        <span class="text-sm text-gray-600">Total Jam per Tahun:</span>
                        <span class="font-bold text-lg text-indigo-600">{{ totalHoursCalculated }} Jam</span>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <SecondaryButton @click="showJobPlanModal = false">Batal</SecondaryButton>
                        <PrimaryButton :disabled="formJobPlan.processing">Simpan Job Plan</PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>
