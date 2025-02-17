<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import TextAreaInput from "@/Components/TextAreaInput.vue";

const page = usePage();

const contactId = page.props.contact.id
const accountId = page.props.activeAccount.id
console.log(accountId)


const form = useForm({
    contact_id: contactId,
    text: '',
    account_id: accountId
});

const createNote = () => {
    form.post(route('notes.store'), {
        onSuccess: () => {
            form.reset();
        }
    });
};

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Add Note</h2>
        </header>

        <form @submit.prevent="createNote" class="mt-6 space-y-6">
            <div>
                <TextAreaInput id="text" type="text" class="mt-1 block w-full" v-model="form.text" />
                <InputError class="mt-2" :message="form.errors.text" />
            </div>
            <TextInput id="contact_id" v-model="form.contact_id" hidden/>
            <TextInput id="account_id" v-model="form.account_id" hidden/>



            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">+ Add</PrimaryButton>

                <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0" leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Added.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
