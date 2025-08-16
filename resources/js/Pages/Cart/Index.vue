<template>
    <EcommerceLayout>
        <div class="max-w-6xl mx-auto p-4 md:p-6">
            <h1 class="text-2xl md:text-3xl font-bold mb-6 text-gray-800">Your Shopping Cart</h1>

            <div v-if="carts.length === 0" class="bg-white rounded-lg shadow p-8 text-center">
                <p class="text-gray-600 mb-4">Your cart is empty</p>
                <Link href="/"
                    class="inline-block bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">
                Continue Shopping
                </Link>
            </div>

            <div v-else class="bg-white rounded-lg shadow overflow-hidden">
                <!-- Cart Items Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3 text-left text-gray-700 font-medium">Product</th>
                                <th class="p-3 text-center text-gray-700 font-medium">Unit</th>
                                <th class="p-3 text-center text-gray-700 font-medium">Price</th>
                                <th class="p-3 text-center text-gray-700 font-medium">Quantity</th>
                                <th class="p-3 text-center text-gray-700 font-medium">Total</th>
                                <th class="p-3 text-right text-gray-700 font-medium">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="item in carts" :key="item.id">
                                <td class="p-3">
                                    <div class="flex items-center">
                                        <img :src="`/storage/${item.product.main_image}`" :alt="item.product.name"
                                            class="w-16 h-16 object-cover rounded mr-4">
                                        <div>
                                            <h3 class="font-medium text-gray-800">{{ item.product.name }}</h3>
                                            <p class="text-sm text-gray-500 line-clamp-1">{{
                                                item.product.short_description
                                                }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-3 text-center text-gray-600">
                                    {{ item.product.unit || 'piece' }}
                                </td>
                                <td class="p-3 text-center text-gray-600">
                                    ৳{{ item.product.net_price }}
                                </td>
                                <td class="p-3 text-center">
                                    <div class="flex items-center justify-center">
                                        <button @click="decreaseQuantity(item)"
                                            class="w-8 h-8 flex items-center justify-center border rounded-l-md hover:bg-gray-100">
                                            −
                                        </button>
                                        <input type="number" v-model="item.quantity" @change="updateQuantity(item)"
                                            :step="item.product.unit === 'kg' ? 0.1 : 1"
                                            :min="item.product.unit === 'kg' ? 0.1 : 1"
                                            class="w-16 h-8 border-t border-b text-center focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        <button @click="increaseQuantity(item)"
                                            class="w-8 h-8 flex items-center justify-center border rounded-r-md hover:bg-gray-100">
                                            +
                                        </button>
                                    </div>
                                </td>
                                <td class="p-3 text-center font-medium text-gray-800">
                                    ৳{{ (item.product.net_price * item.quantity).toFixed(2) }}
                                </td>
                                <td class="p-3 text-right">
                                    <button @click="removeItem(item)" class="text-red-500 hover:text-red-700 p-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Cart Summary -->
                <div class="p-4 border-t bg-gray-50">
                    <div class="flex justify-end">
                        <div class="w-full md:w-1/3">
                            <div class="flex justify-between py-2">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="font-medium">৳{{ subtotal.toFixed(2) }}</span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-gray-600">Delivery Charge:</span>
                                <span class="font-medium">৳{{ deliveryCharge.toFixed(2) }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-t border-gray-200">
                                <span class="text-gray-800 font-bold">Total:</span>
                                <span class="text-blue-600 font-bold">৳{{ total.toFixed(2) }}</span>
                            </div>
                            <div class="mt-4">
                                <Link href="/checkout"
                                    class="block w-full bg-amber-500 hover:bg-amber-600 text-white text-center py-2 px-4 rounded-md transition">
                                Proceed to Checkout
                                </Link>
                            </div>
                            <div class="mt-2">
                                <Link href="/"
                                    class="block w-full text-center text-blue-600 hover:text-blue-800 py-2 px-4 rounded-md transition">
                                Continue Shopping
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </EcommerceLayout>

</template>

<script setup>
import EcommerceLayout from "@/Layouts/EcommerceLayout.vue";
import { usePage, Link, router } from "@inertiajs/vue3";
import { computed } from 'vue';

const page = usePage();
const carts = page.props.carts ?? [];

// Calculate cart totals
const subtotal = computed(() => {
    return carts.reduce((sum, item) => sum + (item.product.net_price * item.quantity), 0);
});

const deliveryCharge = computed(() => {
    // You can implement your delivery charge logic here
    return subtotal.value > 1000 ? 0 : 60; // Example: Free delivery for orders over 1000
});

const total = computed(() => subtotal.value + deliveryCharge.value);

// Quantity handlers
const increaseQuantity = async (item) => {
    const step = item.product.unit === 'kg' ? 0.1 : 1;
    item.quantity = parseFloat((item.quantity + step).toFixed(2));
    await updateCart(item);
};

const decreaseQuantity = async (item) => {
    const step = item.product.unit === 'kg' ? 0.1 : 1;
    const newQuantity = parseFloat((item.quantity - step).toFixed(2));
    const minQuantity = item.product.unit === 'kg' ? 0.1 : 1;

    if (newQuantity >= minQuantity) {
        item.quantity = newQuantity;
        await updateCart(item);
    }
};

const updateQuantity = async (item) => {
    const minQuantity = item.product.unit === 'kg' ? 0.1 : 1;
    if (item.quantity < minQuantity) {
        item.quantity = minQuantity;
    }
    await updateCart(item);
};

// Update cart in backend
const updateCart = async (item) => {
    try {
        await axios.put(`/cart/${item.id}`, {
            quantity: item.quantity
        }, {
            headers: {
                'Content-Type': 'application/json'
            }
        });
    } catch (error) {
        console.error('Error updating cart:', error);
        alert('Failed to update cart. Please try again.');
    }
};

const removeItem = async (item) => {
    if (confirm('Are you sure you want to remove this item from your cart?')) {
        try {
            await axios.delete(`/cart/${item.id}`);
            // Refresh the cart data after deletion
            router.reload();
        } catch (error) {
            console.error('Error removing item:', error);
            alert('Failed to remove item. Please try again.');
        }
    }
};
</script>

<style scoped>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type=number] {
    -moz-appearance: textfield;
}
</style>
