<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import UpdateContactInformationForm from './Partials/UpdateContactInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import DeleteUserForm from "@/Pages/Profile/Partials/DeleteUserForm.vue";
import DeleteContactForm from "@/Pages/Contacts/Partials/DeleteContactForm.vue";


const props = defineProps({
    contact: Object,
    can: Object, // Contains permissions from Laravel
});
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profile</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdateContactInformationForm
                        :contact="contact"
                        class="max-w-xl"
                    />
                </div>

                <div v-if="can.delete"  class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <DeleteContactForm class="max-w-xl" :contact="contact" />
                </div>


            </div>
        </div>
    </AuthenticatedLayout>
</template>
