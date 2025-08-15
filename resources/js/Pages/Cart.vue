<script setup>
import { Head, Link } from '@inertiajs/vue3';
import EcommerceLayout from '@/Layouts/EcommerceLayout.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const cartItems = ref([]);

const loadCart = async () => {
    const res = await axios.get('/cart');
    cartItems.value = res.data;
};

const removeItem = async (id) => {
    await axios.delete(`/cart/remove/${id}`);
    await loadCart();
};

onMounted(loadCart);

const total = computed(() =>
    cartItems.value.reduce((sum, item) => sum + item.quantity * item.product.net_price, 0)
);
</script>

<template>

    <Head title="Cart" />
    <EcommerceLayout>
        <div class="max-w-3xl mx-auto py-12">
            <h1 class="text-2xl font-bold mb-6">Your Cart</h1>

            <div v-if="!cartItems.length" class="text-gray-500">
                কোনো প্রোডাক্ট কার্টে নেই।
            </div>

            <div v-else>
                <div v-for="item in cartItems" :key="item.id"
                    class="flex justify-between items-center mb-4 p-4 bg-white rounded shadow">
                    <div>
                        <h3 class="font-semibold">{{ item.product.name }}</h3>
                        <p>৳{{ item.product.net_price }} x {{ item.quantity }}</p>
                    </div>
                    <button @click="removeItem(item.id)" class="bg-red-500 text-white px-3 py-1 rounded">
                        Remove
                    </button>
                </div>

                <div class="text-right mt-6 font-bold text-lg">
                    Total: ৳{{ total }}
                </div>

                <Link href="/checkout" class="mt-4 inline-block bg-green-600 text-white px-4 py-2 rounded">
                Checkout
                </Link>
            </div>
        </div>
    </EcommerceLayout>
</template>
