<script setup>
import { Head } from '@inertiajs/vue3';
import EcommerceLayout from '@/Layouts/EcommerceLayout.vue';
import axios from 'axios';
import { ref, onMounted } from 'vue';

const message = ref('');

const checkout = async () => {
    await axios.delete('/cart/clear');
    message.value = 'Checkout completed! Cart cleared.';
};

onMounted(checkout);
</script>

<template>

    <Head title="Checkout" />
    <EcommerceLayout>
        <div class="max-w-3xl mx-auto py-12 text-center">
            <h1 class="text-2xl font-bold mb-6">Checkout</h1>
            <p class="text-green-600 text-lg">{{ message }}</p>
            <router-link to="/" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded">
                Back to Home
            </router-link>
        </div>
    </EcommerceLayout>
</template>
