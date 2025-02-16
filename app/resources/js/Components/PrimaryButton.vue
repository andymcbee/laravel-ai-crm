<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    to: String, // If provided, renders as a link
    type: { type: String, default: undefined }, // Button type (only relevant for buttons)
});

// Auto-detect if inside a form (only for non-links)
const buttonType = props.to
    ? undefined // If it's a link, remove type
    : props.type || "button"; // Default to button if no type is provided
</script>

<template>
    <component
        :is="to ? Link : 'button'"
        :href="to"
        :type="buttonType"
        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
    >
        <slot />
    </component>
</template>
