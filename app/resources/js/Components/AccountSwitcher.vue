<template>
    <div class="p-4">
        <select v-model="selectedAccount" @change="switchAccount"
                class="p-2 border rounded w-full text-sm focus:outline-none">

        <option v-for="account in userAccounts" :key="account.id" :value="account.id">
                {{ account.name }}
            </option>
        </select>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useAccountStore } from '@/stores/accountStore';
import {router, usePage} from '@inertiajs/vue3';
import axios from 'axios';

// Get Inertia page props and store
const page = usePage();
const accountStore = useAccountStore();

// Reactive refs
const userAccounts = ref(page.props.auth.user.accounts);
const selectedAccount = ref(page.props.active_account?.id || '');

const switchAccount = async () => {
    await axios.post('/account/switch', { account_id: selectedAccount.value });
    router.visit("/contacts")
};

// Watch for account changes
watch(() => page.props.active_account, (newAccount) => {
    accountStore.setActiveAccount(newAccount);
});
</script>
