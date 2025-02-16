<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { Chart, registerables } from 'chart.js';
import { usePage } from '@inertiajs/vue3';

Chart.register(...registerables);

const page = usePage();
const totalContacts = ref(page.props.total_contacts);
const newContacts = ref(page.props.new_contacts);
const contactsByCompany = ref(page.props.contacts_by_company);
const recentContacts = ref(page.props.recent_contacts);
const contactsByMonth = ref(page.props.contacts_by_month);

onMounted(() => {
    new Chart(document.getElementById('contactsChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(contactsByMonth.value),
            datasets: [{
                label: 'Contacts Added',
                data: Object.values(contactsByMonth.value),
                backgroundColor: 'rgba(54, 162, 235, 0.6)'
            }]
        }
    });
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>


        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6">
            <div class="bg-white shadow-md p-6 rounded-lg text-center">
                <h3 class="text-lg font-semibold text-gray-600">Total Contacts</h3>
                <p class="text-3xl font-bold text-blue-600">{{ totalContacts }}</p>
            </div>

            <div class="bg-white shadow-md p-6 rounded-lg text-center">
                <h3 class="text-lg font-semibold text-gray-600">New Contacts This Month</h3>
                <p class="text-3xl font-bold text-green-600">{{ newContacts }}</p>
            </div>

            <div class="bg-white shadow-md p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-600">Contacts by Company</h3>
                <ul class="mt-4">
                    <li v-for="(count, company) in contactsByCompany" :key="company" class="text-gray-800">
                        {{ company }}: {{ count }}
                    </li>
                </ul>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
            <div class="bg-white shadow-md p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-600">Recently Added Contacts</h3>
                <ul class="mt-4">
                    <li v-for="contact in recentContacts" :key="contact.id" class="text-gray-800">
                        {{ contact.first_name }} {{ contact.last_name }} - {{ contact.created_at }}
                    </li>
                </ul>
            </div>

            <div class="bg-white shadow-md p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-600">Contacts Added Per Month</h3>
                <canvas id="contactsChart"></canvas>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
