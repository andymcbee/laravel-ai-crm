<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CreateNoteForm from "@/Pages/Notes/Partials/CreateNoteForm.vue";

import { Head, Link } from '@inertiajs/vue3';
import {computed} from "vue";


const props = defineProps({
    contact: Object,
    notes: Object,
    can: Object,
});

const allNotes = computed(() => props.notes?.data)
console.log(allNotes)
const paginationLinks = computed(() => props.notes?.links || []);


</script>

<template>
    <Head title="Contact" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex   text-gray-800">
                <div class=" w-72  p-5 overflow-y-auto" >
                    <Link v-if="can.update_contact" :href="route('contacts.edit', contact.id)" class="text-blue-500 hover:underline">
                        Edit Contact
                    </Link>

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
                <div  class="bg-gray-50 flex-grow p-5 rounded-xl border border-gray-300 shadow-md">
                    <div v-if="can.create_note">
                        <CreateNoteForm/>
                    </div>


                    <div class="text-lg font-bold text-gray-700 mt-8 mb-4">Notes</div>
                    <div>
                        <ul v-if="allNotes.length" class="flex flex-col gap-6">
                            <li v-for="note in allNotes" :key="note.id" class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                                <div v-if="note.update_note" class="flex justify-between">
                                    <div class="font-semibold text-sm">{{note.created_at}} by Andrew (a@drew.com)</div>
                                    <Link :href="route('notes.edit', { note: note.id })" class="text-blue-500 hover:underline">
                                        Edit Note
                                    </Link>

                                </div>
                                <p>{{note.text}}</p>

                            </li>

                        </ul>
                        <p v-else class="text-gray-500 text-sm">No notes yet.</p>
                    </div>
                    <div class="mt-6">
                        <template v-for="(link, index) in paginationLinks" :key="index">
                            <Component
                                :is="link.url && !link.active ? 'a' : 'span'"
                                :href="link.url && !link.active ? link.url : null"
                                v-html="link.label"
                                class="px-2 py-1 border rounded"
                                :class="{
                                        'text-blue-500 hover:underline': link.url && !link.active,
                                        'text-gray-400 cursor-default': !link.url || link.active
                                    }"
                            />
                        </template>

                    </div>
                </div>

            </div>
        </template>


    </AuthenticatedLayout>
</template>


