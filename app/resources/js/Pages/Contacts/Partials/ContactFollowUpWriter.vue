<script setup>
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextAreaInput from "@/Components/TextAreaInput.vue";
import {usePage} from '@inertiajs/vue3';
import {onMounted, ref} from "vue";
import axios from "axios";

const page = usePage();
const contactId = page.props.contact.id;
const accountId = page.props.activeAccount.id;

// ✅ Accept `followUps` from the parent (preloaded)
const followUps = ref(page.props.followUps || []);

// User input form data
const userMessage = ref("");

// Loading state
const isGenerating = ref(false);

// ✅ Ensure follow-ups are also fetched if they are not preloaded (edge case)
const fetchFollowUps = async () => {
    try {
        const response = await axios.get(route('follow-up.index', contactId), {
            headers: {'X-Inertia': true}
        });
        followUps.value = response.data.suggestions;
    } catch (error) {
        console.error("Failed to fetch follow-ups:", error);
    }
};

// ✅ Generate new follow-up and update UI
const generateFollowUp = async () => {
    if (!userMessage.value.trim()) return; // Prevent empty messages

    isGenerating.value = true;

    try {
        const response = await axios.post(route('follow-up.generate'), {
            contact_id: contactId,
            user_message: userMessage.value,
            account_id: accountId
        });

        followUps.value.unshift(response.data.suggestion); // Add the new one to the top
        userMessage.value = ""; // Reset input
    } catch (error) {
        console.error("Error generating follow-up:", error);
    } finally {
        isGenerating.value = false;
    }
};

// ✅ Ensure follow-ups load when the component mounts (only if they are not preloaded)
onMounted(() => {
    if (!followUps.value.length) {
        fetchFollowUps();
    }
});
</script>

<template>
    <section class="bg-gray-50 p-5 rounded-xl border border-gray-300 shadow-md">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Generate Follow-Up</h2>
        </header>

        <form class="mt-6 space-y-6" @submit.prevent="generateFollowUp">
            <div>
                <TextAreaInput
                    id="user_message"
                    v-model="userMessage"
                    class="mt-1 block w-full"
                    placeholder="Enter key details or context for follow-up..."
                    type="text"
                />
                <InputError class="mt-2" message=""/>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="isGenerating">
                    {{ isGenerating ? 'Generating...' : '+ Generate' }}
                </PrimaryButton>
            </div>
        </form>

        <!-- Show all follow-ups -->
        <div v-if="followUps.length" class="mt-8">
            <h3 class="text-lg font-bold text-gray-700 mb-4">Generated Follow-Ups</h3>
            <ul class="flex flex-col gap-4">
                <li v-for="(followUp, index) in followUps" :key="index"
                    class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <p>{{ followUp.generated_text }}</p>
                </li>
            </ul>
        </div>
        <p v-else class="text-gray-500 text-sm mt-4">No follow-ups yet.</p>
    </section>
</template>
