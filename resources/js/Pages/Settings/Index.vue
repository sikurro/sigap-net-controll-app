<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import { ref, watch, onMounted } from 'vue';

const props = defineProps({
    settings: Array
});

const form = useForm({
    settings: props.settings.map(s => ({
        id: s.id,
        key: s.key,
        value: s.value,
        type: s.type,
        description: s.description
    }))
});

// Find man_hours_matrix setting
const manHoursItem = form.settings.find(s => s.key === 'man_hours_matrix');

const defaultMatrix = {
    calendar_mode: 'annual',
    shift: {
        hours_per_day: 7,
        days_per_week: 6,
        weeks_per_year: 48,
        active_days_per_year: 288,
        annual_leave_hours: 84,
        sick_leave_hours: 24,
        daily_deductions: {
            meal_rest: 1.0,
            meeting_report_travel: 1.0,
            training_doc: 0.06,
            standby: 0.5,
            skill_factor: 0
        }
    },
    non_shift: {
        hours_per_day: 8,
        days_per_week: 5,
        weeks_per_year: 48,
        active_days_per_year: 240,
        annual_leave_hours: 96,
        sick_leave_hours: 24,
        daily_deductions: {
            meal_rest: 1.0,
            meeting_report_travel: 1.0,
            training_doc: 0.06,
            standby: 0.5,
            skill_factor: 0
        }
    }
};

const matrixForm = ref(defaultMatrix);

if (manHoursItem && manHoursItem.value) {
    try {
        matrixForm.value = JSON.parse(manHoursItem.value);
    } catch (e) {
        console.error('Invalid JSON for man_hours_matrix', e);
    }
}

// Watch changes in matrixForm and update form.settings JSON string
watch(matrixForm, (newVal) => {
    if (manHoursItem) {
        manHoursItem.value = JSON.stringify(newVal);
    }
}, { deep: true });

// Live calculation helpers
const getActiveDays = (data) => {
    if (matrixForm.value.calendar_mode === 'weekly') {
        return Number(data.days_per_week || 0) * Number(data.weeks_per_year || 0);
    }
    return Number(data.active_days_per_year || 0);
};

const getBaseHours = (data) => Number(data.hours_per_day || 0) * getActiveDays(data);

const getAvailableHours = (data) => {
    const leave = Number(data.annual_leave_hours || 0) + Number(data.sick_leave_hours || 0);
    return Math.max(0, getBaseHours(data) - leave);
};

const getDailyLeak = (data) => {
    const d = data.daily_deductions || {};
    return Number(d.meal_rest || 0) + Number(d.meeting_report_travel || 0) + Number(d.training_doc || 0) + Number(d.standby || 0) + Number(d.skill_factor || 0);
};

const getAnnualLeak = (data) => Number((getDailyLeak(data) * getActiveDays(data)).toFixed(1));

const getProductiveHours = (data) => Math.round(Math.max(1, getAvailableHours(data) - getAnnualLeak(data)));

const submit = () => {
    form.put(route('settings.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Global Settings" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Global Settings (Parameter Kalkulasi SDM)</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
                
                <!-- Section 1: Dynamic Man Hours Matrix Table -->
                <div v-if="manHoursItem" class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">Matriks Perhitungan Jam Kerja Produktif (Man Hours)</h3>
                                <p class="text-sm text-gray-500 mt-1">Sesuaikan rincian kalender formal dan kegiatan harian untuk menentukan kapasitas jam produktif teknisi.</p>
                            </div>
                        </div>

                        <!-- Mode Switcher -->
                        <div class="flex flex-wrap items-center gap-6 mb-6 bg-indigo-50/60 p-4 rounded-xl border border-indigo-100">
                            <span class="text-sm font-bold text-indigo-900">Metode Kalender Kerja:</span>
                            <label class="flex items-center gap-2 cursor-pointer text-sm font-medium text-gray-700 select-none">
                                <input type="radio" v-model="matrixForm.calendar_mode" value="annual" class="text-indigo-600 focus:ring-indigo-500 w-4 h-4" />
                                <span>Mode Langsung Tahunan <span class="text-xs text-gray-500">(Input Total Hari Kerja Aktif setahun)</span></span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer text-sm font-medium text-gray-700 select-none">
                                <input type="radio" v-model="matrixForm.calendar_mode" value="weekly" class="text-indigo-600 focus:ring-indigo-500 w-4 h-4" />
                                <span>Mode Mingguan <span class="text-xs text-gray-500">(Hari per Minggu × Minggu per Tahun)</span></span>
                            </label>
                        </div>

                        <!-- Matrix Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left border-collapse border border-gray-200">
                                <thead>
                                    <tr class="bg-gray-100 font-bold text-gray-700 text-center uppercase tracking-wider text-xs">
                                        <th class="p-3 border border-gray-200 text-left">Komponen Parameter</th>
                                        <th class="p-3 border border-gray-200 w-36 bg-blue-50/50 text-blue-900">Teknisi Shift</th>
                                        <th class="p-3 border border-gray-200 w-36 bg-amber-50/50 text-amber-900">Teknisi Non-Shift</th>
                                        <th class="p-3 border border-gray-200 w-24">Satuan</th>
                                        <th class="p-3 border border-gray-200 text-left">Keterangan / Formula</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    
                                    <!-- A. WAKTU KERJA FORMAL -->
                                    <tr class="bg-gray-50 font-bold text-gray-600">
                                        <td colspan="5" class="p-2.5 px-3 uppercase tracking-wide text-xs">A. Waktu Kerja Formal (Dasar)</td>
                                    </tr>
                                    <tr>
                                        <td class="p-2.5 px-3 font-medium text-gray-700">1. Jam Kerja per Hari (1 Shift)</td>
                                        <td class="p-1.5 px-3 border border-gray-200 bg-blue-50/20"><input type="number" step="0.5" v-model="matrixForm.shift.hours_per_day" class="w-full text-center py-1 border-gray-300 rounded text-sm font-bold focus:ring-indigo-500" /></td>
                                        <td class="p-1.5 px-3 border border-gray-200 bg-amber-50/20"><input type="number" step="0.5" v-model="matrixForm.non_shift.hours_per_day" class="w-full text-center py-1 border-gray-300 rounded text-sm font-bold focus:ring-indigo-500" /></td>
                                        <td class="p-2.5 text-center text-gray-500">Jam</td>
                                        <td class="p-2.5 text-gray-500 text-xs">Inputan jam formal per hari</td>
                                    </tr>

                                    <template v-if="matrixForm.calendar_mode === 'weekly'">
                                        <tr>
                                            <td class="p-2.5 px-3 font-medium text-gray-700">2a. Hari Kerja per Minggu</td>
                                            <td class="p-1.5 px-3 border border-gray-200 bg-blue-50/20"><input type="number" v-model="matrixForm.shift.days_per_week" class="w-full text-center py-1 border-gray-300 rounded text-sm font-bold" /></td>
                                            <td class="p-1.5 px-3 border border-gray-200 bg-amber-50/20"><input type="number" v-model="matrixForm.non_shift.days_per_week" class="w-full text-center py-1 border-gray-300 rounded text-sm font-bold" /></td>
                                            <td class="p-2.5 text-center text-gray-500">Hari</td>
                                            <td class="p-2.5 text-gray-500 text-xs">Jumlah hari aktif seminggu</td>
                                        </tr>
                                        <tr>
                                            <td class="p-2.5 px-3 font-medium text-gray-700">2b. Minggu Aktif dalam 1 Tahun</td>
                                            <td class="p-1.5 px-3 border border-gray-200 bg-blue-50/20"><input type="number" v-model="matrixForm.shift.weeks_per_year" class="w-full text-center py-1 border-gray-300 rounded text-sm font-bold" /></td>
                                            <td class="p-1.5 px-3 border border-gray-200 bg-amber-50/20"><input type="number" v-model="matrixForm.non_shift.weeks_per_year" class="w-full text-center py-1 border-gray-300 rounded text-sm font-bold" /></td>
                                            <td class="p-2.5 text-center text-gray-500">Minggu</td>
                                            <td class="p-2.5 text-gray-500 text-xs">Standby 48 atau 52 minggu</td>
                                        </tr>
                                    </template>
                                    <template v-else>
                                        <tr>
                                            <td class="p-2.5 px-3 font-medium text-gray-700">2. Hari Kerja Aktif per Tahun</td>
                                            <td class="p-1.5 px-3 border border-gray-200 bg-blue-50/20"><input type="number" v-model="matrixForm.shift.active_days_per_year" class="w-full text-center py-1 border-gray-300 rounded text-sm font-bold" /></td>
                                            <td class="p-1.5 px-3 border border-gray-200 bg-amber-50/20"><input type="number" v-model="matrixForm.non_shift.active_days_per_year" class="w-full text-center py-1 border-gray-300 rounded text-sm font-bold" /></td>
                                            <td class="p-2.5 text-center text-gray-500">Hari</td>
                                            <td class="p-2.5 text-gray-500 text-xs">Input total hari operasional aktif dalam setahun</td>
                                        </tr>
                                    </template>

                                    <tr class="bg-indigo-50/30 font-semibold text-gray-700">
                                        <td class="p-2 px-3 text-right text-xs">Total Hari Kerja Setahun:</td>
                                        <td class="p-2 px-3 text-center text-blue-900 border border-gray-200 font-mono">{{ getActiveDays(matrixForm.shift) }}</td>
                                        <td class="p-2 px-3 text-center text-amber-900 border border-gray-200 font-mono">{{ getActiveDays(matrixForm.non_shift) }}</td>
                                        <td class="p-2 text-center text-xs text-gray-500">Hari</td>
                                        <td class="p-2 text-xs text-gray-500">Akumulasi hari aktif setahun</td>
                                    </tr>
                                    <tr class="bg-indigo-50/60 font-bold text-gray-800">
                                        <td class="p-2.5 px-3 text-right">Total Jam Dasar Setahun:</td>
                                        <td class="p-2.5 px-3 text-center text-blue-950 border border-gray-200 text-base font-mono">{{ getBaseHours(matrixForm.shift) }}</td>
                                        <td class="p-2.5 px-3 text-center text-amber-950 border border-gray-200 text-base font-mono">{{ getBaseHours(matrixForm.non_shift) }}</td>
                                        <td class="p-2.5 text-center text-xs text-gray-500">Jam</td>
                                        <td class="p-2.5 text-xs text-gray-500">Jam per Hari × Total Hari Kerja</td>
                                    </tr>

                                    <!-- B. PENGURANGAN HAK LIBUR -->
                                    <tr class="bg-gray-50 font-bold text-gray-600">
                                        <td colspan="5" class="p-2.5 px-3 uppercase tracking-wide text-xs">B. Pengurangan Hak Libur Resmi</td>
                                    </tr>
                                    <tr>
                                        <td class="p-2.5 px-3 font-medium text-gray-700">3. Hak Cuti Tahunan</td>
                                        <td class="p-1.5 px-3 border border-gray-200 bg-blue-50/20"><input type="number" v-model="matrixForm.shift.annual_leave_hours" class="w-full text-center py-1 border-gray-300 rounded text-sm font-bold" /></td>
                                        <td class="p-1.5 px-3 border border-gray-200 bg-amber-50/20"><input type="number" v-model="matrixForm.non_shift.annual_leave_hours" class="w-full text-center py-1 border-gray-300 rounded text-sm font-bold" /></td>
                                        <td class="p-2.5 text-center text-gray-500">Jam</td>
                                        <td class="p-2.5 text-gray-500 text-xs">Total jatah cuti dalam jam</td>
                                    </tr>
                                    <tr>
                                        <td class="p-2.5 px-3 font-medium text-gray-700">4. Izin Sakit</td>
                                        <td class="p-1.5 px-3 border border-gray-200 bg-blue-50/20"><input type="number" v-model="matrixForm.shift.sick_leave_hours" class="w-full text-center py-1 border-gray-300 rounded text-sm font-bold" /></td>
                                        <td class="p-1.5 px-3 border border-gray-200 bg-amber-50/20"><input type="number" v-model="matrixForm.non_shift.sick_leave_hours" class="w-full text-center py-1 border-gray-300 rounded text-sm font-bold" /></td>
                                        <td class="p-2.5 text-center text-gray-500">Jam</td>
                                        <td class="p-2.5 text-gray-500 text-xs">Alokasi izin sakit setahun</td>
                                    </tr>
                                    <tr class="bg-yellow-100/80 font-bold text-yellow-950 border-t-2 border-yellow-300">
                                        <td class="p-3 px-3 uppercase tracking-wider">JAM TERSEDIA / TAHUN</td>
                                        <td class="p-3 px-3 text-center border border-yellow-200 text-base font-mono">{{ getAvailableHours(matrixForm.shift) }}</td>
                                        <td class="p-3 px-3 text-center border border-yellow-200 text-base font-mono">{{ getAvailableHours(matrixForm.non_shift) }}</td>
                                        <td class="p-3 text-center text-xs">Jam</td>
                                        <td class="p-3 text-xs text-yellow-800">Total Jam Dasar - (Cuti + Sakit)</td>
                                    </tr>

                                    <!-- C. KEBOCORAN WAKTU HARIAN -->
                                    <tr class="bg-gray-50 font-bold text-gray-600">
                                        <td colspan="5" class="p-2.5 px-3 uppercase tracking-wide text-xs">C. Kebocoran Waktu Harian (Non-Productive per Shift)</td>
                                    </tr>
                                    <tr>
                                        <td class="p-2.5 px-3 font-medium text-gray-700">5. Istirahat, Makan, Shalat, Toilet</td>
                                        <td class="p-1.5 px-3 border border-gray-200 bg-blue-50/20"><input type="number" step="0.1" v-model="matrixForm.shift.daily_deductions.meal_rest" class="w-full text-center py-1 border-gray-300 rounded text-sm font-bold" /></td>
                                        <td class="p-1.5 px-3 border border-gray-200 bg-amber-50/20"><input type="number" step="0.1" v-model="matrixForm.non_shift.daily_deductions.meal_rest" class="w-full text-center py-1 border-gray-300 rounded text-sm font-bold" /></td>
                                        <td class="p-2.5 text-center text-gray-500">Jam/Hari</td>
                                        <td class="p-2.5 text-gray-500 text-xs">Waktu istirahat harian</td>
                                    </tr>
                                    <tr>
                                        <td class="p-2.5 px-3 font-medium text-gray-700">6. Meeting, Briefing, Laporan, Jalan</td>
                                        <td class="p-1.5 px-3 border border-gray-200 bg-blue-50/20"><input type="number" step="0.1" v-model="matrixForm.shift.daily_deductions.meeting_report_travel" class="w-full text-center py-1 border-gray-300 rounded text-sm font-bold" /></td>
                                        <td class="p-1.5 px-3 border border-gray-200 bg-amber-50/20"><input type="number" step="0.1" v-model="matrixForm.non_shift.daily_deductions.meeting_report_travel" class="w-full text-center py-1 border-gray-300 rounded text-sm font-bold" /></td>
                                        <td class="p-2.5 text-center text-gray-500">Jam/Hari</td>
                                        <td class="p-2.5 text-gray-500 text-xs">Briefing dan perjalanan ke alat</td>
                                    </tr>
                                    <tr>
                                        <td class="p-2.5 px-3 font-medium text-gray-700">7. Training & Baca Dokumentasi</td>
                                        <td class="p-1.5 px-3 border border-gray-200 bg-blue-50/20"><input type="number" step="0.01" v-model="matrixForm.shift.daily_deductions.training_doc" class="w-full text-center py-1 border-gray-300 rounded text-sm font-bold" /></td>
                                        <td class="p-1.5 px-3 border border-gray-200 bg-amber-50/20"><input type="number" step="0.01" v-model="matrixForm.non_shift.daily_deductions.training_doc" class="w-full text-center py-1 border-gray-300 rounded text-sm font-bold" /></td>
                                        <td class="p-2.5 text-center text-gray-500">Jam/Hari</td>
                                        <td class="p-2.5 text-gray-500 text-xs">Peningkatan skill & baca SOP</td>
                                    </tr>
                                    <tr>
                                        <td class="p-2.5 px-3 font-medium text-gray-700">8. Waktu Standby (Menunggu instruksi)</td>
                                        <td class="p-1.5 px-3 border border-gray-200 bg-blue-50/20"><input type="number" step="0.1" v-model="matrixForm.shift.daily_deductions.standby" class="w-full text-center py-1 border-gray-300 rounded text-sm font-bold" /></td>
                                        <td class="p-1.5 px-3 border border-gray-200 bg-amber-50/20"><input type="number" step="0.1" v-model="matrixForm.non_shift.daily_deductions.standby" class="w-full text-center py-1 border-gray-300 rounded text-sm font-bold" /></td>
                                        <td class="p-2.5 text-center text-gray-500">Jam/Hari</td>
                                        <td class="p-2.5 text-gray-500 text-xs">Standby menunggu instruksi</td>
                                    </tr>
                                    <tr>
                                        <td class="p-2.5 px-3 font-medium text-gray-700">9. Skill Factor</td>
                                        <td class="p-1.5 px-3 border border-gray-200 bg-blue-50/20"><input type="number" step="0.1" v-model="matrixForm.shift.daily_deductions.skill_factor" class="w-full text-center py-1 border-gray-300 rounded text-sm font-bold" /></td>
                                        <td class="p-1.5 px-3 border border-gray-200 bg-amber-50/20"><input type="number" step="0.1" v-model="matrixForm.non_shift.daily_deductions.skill_factor" class="w-full text-center py-1 border-gray-300 rounded text-sm font-bold" /></td>
                                        <td class="p-2.5 text-center text-gray-500">Jam/Hari</td>
                                        <td class="p-2.5 text-gray-500 text-xs">Faktor keahlian teknisi</td>
                                    </tr>
                                    <tr class="bg-red-50/40 font-semibold text-red-900">
                                        <td class="p-2 px-3 text-right text-xs">Total Kebocoran Harian:</td>
                                        <td class="p-2 px-3 text-center border border-gray-200 font-mono">{{ getDailyLeak(matrixForm.shift).toFixed(2) }}</td>
                                        <td class="p-2 px-3 text-center border border-gray-200 font-mono">{{ getDailyLeak(matrixForm.non_shift).toFixed(2) }}</td>
                                        <td class="p-2 text-center text-xs">Jam/Hari</td>
                                        <td class="p-2 text-xs text-gray-500">Sum parameter 5 s.d 9</td>
                                    </tr>
                                    <tr class="bg-red-100/60 font-bold text-red-950">
                                        <td class="p-2.5 px-3 text-right">Total Kebocoran Setahun:</td>
                                        <td class="p-2.5 px-3 text-center border border-gray-200 font-mono">{{ getAnnualLeak(matrixForm.shift) }}</td>
                                        <td class="p-2.5 px-3 text-center border border-gray-200 font-mono">{{ getAnnualLeak(matrixForm.non_shift) }}</td>
                                        <td class="p-2.5 text-center text-xs">Jam</td>
                                        <td class="p-2.5 text-xs text-red-800">Kebocoran Harian × Hari Kerja Setahun</td>
                                    </tr>

                                    <!-- D. HASIL AKHIR -->
                                    <tr class="bg-emerald-100 font-extrabold text-emerald-950 border-t-4 border-emerald-400 text-lg">
                                        <td class="p-4 px-3 uppercase tracking-wider">JAM PRODUKTIF / TAHUN</td>
                                        <td class="p-4 px-3 text-center bg-emerald-200/80 border border-emerald-300 font-mono text-xl text-blue-950">{{ getProductiveHours(matrixForm.shift) }}</td>
                                        <td class="p-4 px-3 text-center bg-emerald-200/80 border border-emerald-300 font-mono text-xl text-amber-950">{{ getProductiveHours(matrixForm.non_shift) }}</td>
                                        <td class="p-4 text-center text-xs text-emerald-900 font-bold">Jam/Thn</td>
                                        <td class="p-4 text-xs text-emerald-800 font-medium">Jam Tersedia - Total Kebocoran Setahun <br/><span class="text-[10px] text-emerald-700">(Dipakai sebagai pembagi otomatis kebutuhan teknisi)</span></td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Other Settings (e.g. Thresholds) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-xl font-bold text-gray-800 mb-6">Pengaturan Tambahan Lainnya</h3>
                        
                        <form @submit.prevent="submit">
                            <div v-for="setting in form.settings.filter(s => s.key !== 'man_hours_matrix')" :key="setting.id" class="mb-6 pb-6 border-b border-gray-200 last:border-0 last:pb-0 last:mb-0">
                                <InputLabel :for="'setting_' + setting.id" class="text-lg font-bold text-gray-800">
                                    {{ setting.key }}
                                </InputLabel>
                                <p class="text-sm text-gray-500 mb-2 mt-1">{{ setting.description }}</p>
                                
                                <div v-if="setting.type === 'json'">
                                    <textarea
                                        :id="'setting_' + setting.id"
                                        v-model="setting.value"
                                        rows="6"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full font-mono text-sm"
                                    ></textarea>
                                </div>
                                <div v-else>
                                    <TextInput
                                        :id="'setting_' + setting.id"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="setting.value"
                                        required
                                    />
                                </div>
                            </div>

                            <div class="flex items-center gap-4 mt-8 pt-4 border-t">
                                <PrimaryButton :disabled="form.processing" class="px-6 py-3 text-base">Simpan Seluruh Pengaturan</PrimaryButton>

                                <Transition
                                    enter-active-class="transition ease-in-out"
                                    enter-from-class="opacity-0"
                                    leave-active-class="transition ease-in-out"
                                    leave-to-class="opacity-0"
                                >
                                    <p v-if="form.recentlySuccessful" class="text-sm font-bold text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded border border-emerald-200">✓ Berhasil disimpan & dikalkulasi ulang.</p>
                                </Transition>
                                <InputError :message="form.errors.settings" class="mt-2" />
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
