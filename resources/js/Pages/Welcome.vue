<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import EcommerceLayout from '@/Layouts/EcommerceLayout.vue';
import { defineProps } from 'vue';
import axios from 'axios';

defineProps({
    categories: Array,
    canLogin: Boolean,
    canRegister: Boolean,
});

// user info নেয়ার জন্য
const page = usePage();
const user = page.props.auth?.user ?? null;

const addToCart = async (product) => {
    if (!user) {
        // যদি user login না করে থাকে তাহলে login page এ নিয়ে যাবে
        router.visit('/login');
        return;
    }

    try {
        await axios.post('/cart/add', { product_id: product.id, quantity: 1 });
        alert(`${product.name} কার্টে যোগ করা হয়েছে!`);
    } catch (error) {
        console.error(error);
        alert('কার্টে যোগ করতে ব্যর্থ হয়েছে।');
    }
};
</script>


<template>

    <Head title="Welcome" />
    <EcommerceLayout>
        <div class="bg-gray-50 min-h-screen">

            <!-- Hero Section -->
            <div class="bg-blue-600 text-white py-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h1 class="text-4xl font-bold mb-4">আমাদের প্রোডাক্ট এক্সপ্লোর করুন</h1>
                    <p class="text-xl">ক্যাটাগরি অনুযায়ী সেরা প্রোডাক্টসমূহ</p>
                </div>
            </div>

            <!-- Categories & Products -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div v-for="category in categories" :key="category.id" class="mb-16">

                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">{{ category.name }}</h2>
                        <Link :href="`/category/${category.name}`" class="text-blue-600 hover:text-blue-800">
                        সব দেখুন →
                        </Link>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        <div v-for="product in category.products" :key="product.id"
                            class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">

                            <Link :href="`/products/${product.name}`" class="block">
                            <div class="h-48 bg-gray-100 flex items-center justify-center overflow-hidden">
                                <img :src="`/storage/${product.main_image}`" :alt="product.name"
                                    class="h-full w-full object-cover hover:scale-105 transition-transform duration-300">
                            </div>
                            </Link>

                            <div class="p-4">
                                <h3 class="font-semibold text-lg mb-1 truncate">{{ product.name }}</h3>
                                <p class="text-gray-600 text-sm mb-2 line-clamp-2">{{ product.short_description }}</p>

                                <div class="flex items-center mt-3">
                                    <span class="text-lg font-bold text-blue-600">৳{{ product.net_price }}</span>
                                </div>

                                <div class="mt-4 flex justify-between">
                                    <button @click="addToCart(product)"
                                        class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition">
                                        কার্টে যোগ করুন
                                    </button>
                                    <Link :href="`/products/${product.name}`"
                                        class="text-blue-600 px-3 py-1 rounded text-sm hover:bg-blue-50 transition">
                                    বিস্তারিত
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="!category.products.length" class="text-center text-gray-500 py-10">
                        কোনো প্রোডাক্ট পাওয়া যায়নি।
                    </div>

                </div>
            </div>
        </div>
    </EcommerceLayout>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
