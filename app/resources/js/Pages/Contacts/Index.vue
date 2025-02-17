<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { AgGridVue } from "ag-grid-vue3";
import { AllCommunityModule, ModuleRegistry } from "ag-grid-community";
import {computed,  ref} from "vue";
import PrimaryButtonLink from "@/Components/PrimaryButtonLink.vue";
import {Link} from "@inertiajs/vue3";

ModuleRegistry.registerModules([AllCommunityModule]);

const props = defineProps({
  contacts: Object
});
const rowData = computed(() => props.contacts?.data)
const paginationLinks = computed(() => props.contacts?.links || []);

const colDefs = ref([
    {
        field: "action",
        headerName: "Action",
        cellRenderer: params => {
            return `<a href="/contacts/${params.data.id}" class="text-blue-500 hover:underline">View</a>`;
        }
    },
    { field: "account_id", headerName: "Account", sortable: true, filter: true },
    { field: "first_name", headerName: "First Name", sortable: true, filter: true },
    { field: "last_name", headerName: "Last Name", sortable: true, filter: true },
    { field: "title", headerName: "Title", sortable: true, filter: true },
    { field: "company", headerName: "Company", sortable: true, filter: true },
    { field: "email", headerName: "Email", sortable: true, filter: true },
    { field: "phone", headerName: "Phone", sortable: true, filter: true },

]);

</script>

<template>
    <Head title="Contacts" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Contacts</h2>

                <PrimaryButtonLink :href="route('contacts.create')">
                    + Add Contact
                </PrimaryButtonLink>


            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <ag-grid-vue
                        :columnDefs="colDefs"
                        :rowData="rowData"
                        class="ag-theme-alpine w-full"
                        style="height: 500px"
                    ></ag-grid-vue>
                    <!-- Paginator -->
                    <div class="mt-6">
                        <template v-for="(link, index) in paginationLinks" :key="index">
                            <Component
                                :is="link.url && !link.active ? 'a' : 'span'"
                                :href="link.url && !link.active ? link.url : null"
                                v-html="link.label"
                                class="px-2 py-1 border rounded"
                                :class="{
                                        'text-blue-500 hover:underline': link.url && !link.active,
                                        'text-gray-400 cursor-default': !link.url || link.active
                                    }"
                            />
                        </template>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
@import 'ag-grid-community/styles/ag-grid.css';
@import 'ag-grid-community/styles/ag-theme-alpine.css';
</style>
