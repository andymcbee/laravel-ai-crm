<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';



const props = defineProps(['note'])

const form = useForm({
    text: props.note.text,
});

const updateNote = () => {
    form.put(route('notes.update', props.note.id));
};

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Update Note</h2>

            <p class="mt-1 text-sm text-gray-600">
                Update the note.
            </p>
        </header>

        <form @submit.prevent="updateNote" class="mt-6 space-y-6">
            <!-- First Name -->
            <div>
                <InputLabel for="text" value="Text" />
                <TextInput id="text" type="text" class="mt-1 block w-full" v-model="form.text" required />
                <InputError class="mt-2" :message="form.errors.text" />
            </div>







            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0" leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
