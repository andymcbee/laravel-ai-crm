// FollowUps/Index.vue
<template>
    <div>
        <h2>Email Follow-Ups for {{ contact.name }}</h2>

        <form @submit.prevent="generateFollowUp">
            <textarea v-model="userMessage" placeholder="Add context (optional)"></textarea>
            <button type="submit">Generate Follow-Up</button>
        </form>

        <div v-if="suggestions.length">
            <h3>Generated Follow-Ups</h3>
            <ul>
                <li v-for="suggestion in suggestions" :key="suggestion.id">
                    <h4>Generated Email:</h4>
                    <p>{{ suggestion.generated_text }}</p>
                    <h4>Context Used:</h4>
                    <pre>{{ JSON.stringify(suggestion.context_data, null, 2) }}</pre>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    contact: Object,
    suggestions: Array
});

const userMessage = ref('');
const form = useForm({
    contact_id: props.contact.id,
    user_message: ''
});

const generateFollowUp = () => {
    form.post(route('follow-up.generate'), {
        onSuccess: () => {
            userMessage.value = '';
        }
    });
};
</script>
