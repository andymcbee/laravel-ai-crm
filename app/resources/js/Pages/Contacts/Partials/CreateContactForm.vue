<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

const page = usePage();

const accountId = page.props.active_account.id

const form = useForm({
    account_id: accountId,  // Include accountId in form
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    company: '',
    title: '',
});

const createContact = () => {
    form.post(route('contacts.store'));
};

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Create Contact</h2>

            <p class="mt-1 text-sm text-gray-600">
                Create a new contact.
            </p>
        </header>

        <form @submit.prevent="createContact" class="mt-6 space-y-6">
            <!-- First Name -->
            <div>
                <InputLabel for="first_name" value="First Name" />
                <TextInput id="first_name" type="text" class="mt-1 block w-full" v-model="form.first_name" />
                <InputError class="mt-2" :message="form.errors.first_name" />
            </div>

            <!-- Last Name -->
            <div>
                <InputLabel for="last_name" value="Last Name" />
                <TextInput id="last_name" type="text" class="mt-1 block w-full" v-model="form.last_name" />
                <InputError class="mt-2" :message="form.errors.last_name" />
            </div>

            <!-- Email -->
            <div>
                <InputLabel for="email" value="Email" />
                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <!-- Phone -->
            <div>
                <InputLabel for="phone" value="Phone" />
                <TextInput id="phone" type="text" class="mt-1 block w-full" v-model="form.phone" />
                <InputError class="mt-2" :message="form.errors.phone" />
            </div>

            <!-- Company -->
            <div>
                <InputLabel for="company" value="Company" />
                <TextInput id="company" type="text" class="mt-1 block w-full" v-model="form.company" />
                <InputError class="mt-2" :message="form.errors.company" />
            </div>

            <!-- Title -->
            <div>
                <InputLabel for="title" value="Title" />
                <TextInput id="title" type="text" class="mt-1 block w-full" v-model="form.title" />
                <InputError class="mt-2" :message="form.errors.title" />
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
