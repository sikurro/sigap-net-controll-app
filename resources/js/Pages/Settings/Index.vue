<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Global Settings (Parameter Kalkulasi)</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div v-for="(setting, index) in form.settings" :key="setting.id" class="mb-6 pb-6 border-b border-gray-200 last:border-0 last:pb-0 last:mb-0">
                                
                                <InputLabel :for="'setting_' + setting.id" class="text-lg font-bold">
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

                            <div class="flex items-center gap-4 mt-6">
                                <PrimaryButton :disabled="form.processing">Simpan Pengaturan</PrimaryButton>

                                <Transition
                                    enter-active-class="transition ease-in-out"
                                    enter-from-class="opacity-0"
                                    leave-active-class="transition ease-in-out"
                                    leave-to-class="opacity-0"
                                >
                                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Berhasil disimpan.</p>
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
