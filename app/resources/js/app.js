import { createApp } from 'vue';
import { createPinia } from 'pinia';
import { createInertiaApp } from '@inertiajs/vue3';
import { createRouter, createWebHistory } from 'vue-router';

import Dashboard from './Pages/Dashboard.vue';
import Contacts from './Pages/Contacts.vue';
import ContactDetails from './Pages/ContactDetails.vue';

const routes = [
    { path: '/', component: Dashboard },
    { path: '/contacts', component: Contacts },
    { path: '/contacts/:id', component: ContactDetails }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

createInertiaApp({
    resolve: name => import(`./Pages/${name}.vue`),
    setup({ el, App, props }) {
        createApp(App)
            .use(createPinia())
            .use(router)
            .mount(el);
    },
});
