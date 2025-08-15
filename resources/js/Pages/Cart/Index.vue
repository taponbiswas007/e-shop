<template>
    <div class="max-w-4xl mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Your Cart</h1>
        <table class="w-full text-left border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2">Product</th>
                    <th class="p-2">Qty</th>
                    <th class="p-2">Price</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in cartItems" :key="item.id" class="border-t">
                    <td class="p-2">{{ item.product.name }}</td>
                    <td class="p-2">{{ item.quantity }}</td>
                    <td class="p-2">৳{{ item.product.net_price * item.quantity }}</td>
                </tr>
            </tbody>
        </table>
        <div class="mt-4 flex justify-end">
            <Link href="/checkout" class="bg-amber-400 px-4 py-2 text-white rounded hover:bg-amber-500">
            Checkout
            </Link>
        </div>
    </div>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const cartItems = page.props.cartItems ?? [];
</script>
