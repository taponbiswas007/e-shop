<script setup>
import { Head, Link } from '@inertiajs/vue3';
import EcommerceLayout from '@/Layouts/EcommerceLayout.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

</script>

<template>

    <Head title="Welcome" />
    <EcommerceLayout>
        <h1>Home Page</h1>
    </EcommerceLayout>
</template>
