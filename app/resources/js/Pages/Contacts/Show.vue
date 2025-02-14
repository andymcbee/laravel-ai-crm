<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';


const props = defineProps({
    contact: Object,
    notes: Array
});


</script>

<template>
    <Head title="Contact" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex h-screen  text-gray-800">
                <div class=" w-72  p-5 overflow-y-auto" >
                    <div class="text-lg font-bold">{{contact.first_name}} {{contact.last_name}}</div>
                    <div> {{ contact.title }}</div>
                    <div> {{contact.company}}</div>
                    <div class="mt-5">
                        <h3 class="font-semibold">Contact Details</h3>
                        <div>
                            <div>Email: {{contact.email}}</div>
                            <div>Phone: {{contact.phone}}</div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 flex-grow p-5 rounded-xl border border-gray-300 shadow-md">
                    <div class="text-lg font-bold text-gray-700">Notes</div>
                    <div>
                        <ul v-if="notes.length">
                            <li v-for="note in notes" :key="note.id">
                                <p>{{note.text}}</p>

                            </li>

                        </ul>
                        <p v-else class="text-gray-500 text-sm">No notes yet.</p>
                    </div>
                </div>

            </div>
        </template>


    </AuthenticatedLayout>
</template>


