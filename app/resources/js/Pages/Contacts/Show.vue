<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import {Head, Link} from '@inertiajs/vue3';
import {computed, ref} from "vue";
import ContactNotes from "@/Pages/Contacts/Partials/ContactNotes.vue";


const props = defineProps({
    contact: Object,
    notes: Object,
    can: Object,
});

const allNotes = computed(() => props.notes?.data)
console.log(allNotes)
const paginationLinks = computed(() => props.notes?.links || []);

const activeTab = ref('notes');


</script>

<template>
    <Head title="Contact"/>

    <AuthenticatedLayout>
        <template #header>
            <div class="flex   text-gray-800">
                <div class=" w-72  p-5 overflow-y-auto">
                    <Link v-if="can.update_contact" :href="route('contacts.edit', contact.id)"
                          class="text-blue-500 hover:underline">
                        Edit Contact
                    </Link>

                    <div class="text-lg font-bold">{{ contact.first_name }} {{ contact.last_name }}</div>
                    <div> {{ contact.title }}</div>
                    <div> {{ contact.company }}</div>
                    <div class="mt-5">
                        <h3 class="font-semibold">Contact Details</h3>
                        <div>
                            <div>Email: {{ contact.email }}</div>
                            <div>Phone: {{ contact.phone }}</div>
                        </div>
                    </div>
                </div>
                <div class=" flex-grow">

                    <nav class="flex space-x-2 border-b border-gray-300 mb-4">
                        <button
                            :class="[
                                  'px-4 py-2 font-medium text-gray-600 rounded-t-md transition-all duration-200',
                                  activeTab === 'notes' ? 'bg-white border border-gray-300 border-b-0 text-gray-900' : 'bg-gray-100 hover:bg-gray-200'
                                ]"
                            @click="activeTab = 'notes'"
                        >
                            Notes
                        </button>
                        <button
                            :class="[
                                  'px-4 py-2 font-medium text-gray-600 rounded-t-md transition-all duration-200',
                                  activeTab === 'follow-up' ? 'bg-white border border-gray-300 border-b-0 text-gray-900' : 'bg-gray-100 hover:bg-gray-200'
                                ]"
                            @click="activeTab = 'follow-up'"
                        >
                            Follow-Up Writer
                        </button>
                    </nav>


                    <ContactNotes v-if="activeTab === 'notes'" :can="can" :notes="notes"/>
                    <div v-if="activeTab === 'follow-up'"> Follow Up Content Area ....</div>

                </div>


            </div>
        </template>


    </AuthenticatedLayout>
</template>


