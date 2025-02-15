<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';



const props = defineProps(['contact'])

const form = useForm({
    first_name: props.contact.first_name,
    last_name: props.contact.last_name,
    email: props.contact.email,
    phone: props.contact.phone,
    company: props.contact.company,
    title: props.contact.title,
});

const updateContact = () => {
    form.put(route('contacts.update', props.contact.id));
};

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Profile Information</h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your account's profile information and email address.
            </p>
        </header>

        <form @submit.prevent="updateContact" class="mt-6 space-y-6">
            <!-- First Name -->
            <div>
                <InputLabel for="first_name" value="First Name" />
                <TextInput id="first_name" type="text" class="mt-1 block w-full" v-model="form.first_name" required />
                <InputError class="mt-2" :message="form.errors.first_name" />
            </div>

            <!-- Last Name -->
            <div>
                <InputLabel for="last_name" value="Last Name" />
                <TextInput id="last_name" type="text" class="mt-1 block w-full" v-model="form.last_name" required />
                <InputError class="mt-2" :message="form.errors.last_name" />
            </div>

            <!-- Email -->
            <div>
                <InputLabel for="email" value="Email" />
                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required />
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
