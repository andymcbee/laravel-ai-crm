<script setup>
import {computed} from "vue";
import CreateNoteForm from "@/Pages/Notes/Partials/CreateNoteForm.vue";
import {Link} from "@inertiajs/vue3";

const props = defineProps({
    notes: Object,
    can: {
        type: Object,
        default: () => ({create_note: false}) // Ensure default structure
    },
});

const allNotes = computed(() => props.notes?.data || []);
</script>

<template>
    <div class="bg-gray-50 p-5 rounded-xl border border-gray-300 shadow-md">
        <!-- Note Creation -->
        <div v-if="can.create_note">
            <CreateNoteForm/>
        </div>

        <!-- Notes List -->
        <div class="text-lg font-bold text-gray-700 mt-8 mb-4">Notes</div>
        <div>
            <ul v-if="allNotes.length" class="flex flex-col gap-6">
                <li v-for="note in allNotes" :key="note.id"
                    class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div v-if="note.update_note" class="flex justify-between">
                        <div class="font-semibold text-sm">{{ note.created_at }} by Andrew
                            (a@drew.com)
                        </div>
                        <Link :href="route('notes.edit', { note: note.id })"
                              class="text-blue-500 hover:underline">
                            Edit Note
                        </Link>
                    </div>
                    <p>{{ note.text }}</p>
                </li>
            </ul>
            <p v-else class="text-gray-500 text-sm">No notes yet.</p>
        </div>
    </div>
</template>
