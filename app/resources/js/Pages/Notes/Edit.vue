<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import UpdateNoteInformationForm from './Partials/UpdateNoteInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import DeleteUserForm from "@/Pages/Profile/Partials/DeleteUserForm.vue";

const props = defineProps(["note"]);
</script>

<template>
    <Head title="Note" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Note</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdateNoteInformationForm
                        :note="note"
                        class="max-w-xl"
                    />
                </div>



            </div>
        </div>
    </AuthenticatedLayout>
</template>
