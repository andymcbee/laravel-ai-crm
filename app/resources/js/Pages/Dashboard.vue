<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { Chart, registerables } from 'chart.js';
import { usePage } from '@inertiajs/vue3';
import {Card, CardContent, CardDescription, CardHeader, CardTitle} from "@/Components/ui/card/index.js";
Chart.register(...registerables);
import { VisXYContainer, VisGroupedBar, VisAxis, VisBulletLegend } from '@unovis/vue'

const page = usePage();
const totalContacts = ref(page.props.total_contact_count);

const newContacts = ref(page.props.new_contact_stats.last_30_days);
const newContactsDifference = ref(page.props.new_contact_stats.percent_change);

const newNotes = ref(page.props.new_note_stats.last_30_days);
const newNotesDifference = ref(page.props.new_note_stats.percent_change);

const usersWithActivity = ref(page.props.users)

console.log(usersWithActivity)



const colors = {
    currentYear: '#f45a6d',
    prevYear: '#2780eb',
}

const monthNames = {
    10: "October",
    11: "November",
    12: "December"
};



const data = ref([
    {
        month: 10,
        currentYear: 124,
        prevYear: 32,
    },
    {
        month: 11,
        currentYear: 223,
        prevYear: 32,
    },
    {
        month: 12,
        currentYear: 245,
        prevYear: 62,
    }])

const items = Object.entries(colors).map(([n, c]) => ({
    name: n,
    color: c,
}))
const x = (d) => d.month.toString(); // Convert month number to a string
const y = [
    (d) => d.currentYear,
    (d) => d.prevYear,
]
const color = (d, i) => items[i].color


</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="grid grid-cols-3 gap-4 p-4">
            <Card >
                <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                    <CardTitle class="text-sm font-medium">
                        New Contacts (last 30 days)
                    </CardTitle>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth="2"
                        class="h-4 w-4 text-muted-foreground"
                    >
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold">
                        {{newContacts === 0 ? '0' : `+${newContacts}`}}
                    </div>
                    <p class="text-xs text-muted-foreground">
                        {{newContactsDifference > 0 ? `+${newContactsDifference}` : newContactsDifference}}% from last month
                    </p>
                </CardContent>
            </Card>
            <Card >
                <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                    <CardTitle class="text-sm font-medium">
                        New Notes (last 30 days)
                    </CardTitle>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth="2"
                        class="h-4 w-4 text-muted-foreground"
                    >
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold">
                        {{newNotes === 0 ? '0' : `+${newNotes}`}}
                    </div>
                    <p class="text-xs text-muted-foreground">
                        {{newNotesDifference > 0 ? `+${newNotesDifference}` : newNotesDifference}}% from last month
                    </p>
                </CardContent>
            </Card>
            <Card >
                <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                    <CardTitle class="text-sm font-medium">
                        Total Contacts
                    </CardTitle>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth="2"
                        class="h-4 w-4 text-muted-foreground"
                    >
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold">
                        {{totalContacts}}
                    </div>
                </CardContent>
            </Card>


            <Card class="col-span-2">
                <CardContent>
                    <h2>Contacts Added (Compared to prev. period)</h2>
                    <VisBulletLegend :items="items" />
                    <VisXYContainer :height="500">
                        <VisGroupedBar :data="data" :x="x" :y="y" :color="color" />
                        <VisAxis
                            type="x"
                            label="Month"
                            :scaleType="'band'"
                        />

                        <VisAxis type="y" :tickFormat="(value) => (value / 10 ** 6).toFixed(1)" label="Number of Votes (millions)" />
                    </VisXYContainer>
                </CardContent>
            </Card>




            <Card class="col-span-1">
                <CardHeader>
                    <CardTitle>Team Activity Overview</CardTitle>
                    <CardDescription>
                        See how many contacts each user has added recently
                    </CardDescription>
                </CardHeader>
                <CardContent>


                        <ul class="space-y-8">
                            <li v-for="user in usersWithActivity" :key="user.id">
                                <div class="flex items-center">


                                    <Avatar class="flex h-9 w-9 items-center justify-center space-y-0 border">
                                        <AvatarImage src="/avatars/01.png" alt="Avatar" />
                                        <AvatarFallback>{{ user.firstName.charAt(0) }}{{ user.lastName.charAt(0) }}</AvatarFallback>
                                    </Avatar>
                                    <div class="ml-4 space-y-1">
                                        <p class="text-sm font-medium leading-none">
                                            {{ user.firstName }} {{ user.lastName }}
                                        </p>
                                        <p class="text-sm text-muted-foreground">
                                            {{ user.email }}
                                        </p>
                                    </div>
                                    <div class="text-sm ml-auto font-medium">
                                        Contacts: {{user.contactsCount }} Notes: {{user.notesCount }}
                                    </div>
                                </div>
                            </li>
                        </ul>



                </CardContent>
            </Card>







            </div>



    </AuthenticatedLayout>
</template>
