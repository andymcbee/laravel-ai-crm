<template>
    <div>
        <h1>Contacts</h1>
        <ag-grid-vue :columnDefs="columns" :rowData="contacts"></ag-grid-vue>
    </div>
</template>

<script>
import { defineComponent, ref } from 'vue';
import { AgGridVue } from 'ag-grid-vue3';

export default defineComponent({
    components: { AgGridVue },
    setup() {
        const contacts = ref([]);
        const columns = ref([
            { field: 'name' },
            { field: 'email' },
            { field: 'phone' }
        ]);

        return { contacts, columns };
    }
});
</script>
