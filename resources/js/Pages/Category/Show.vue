<script setup>
import { Head, Link } from '@inertiajs/vue3';
import EcommerceLayout from '@/Layouts/EcommerceLayout.vue';
import { defineProps } from 'vue';

defineProps({
    category: Object,
    products: Array
});

const addToCart = (product) => {
    console.log('Added to cart:', product);
};
</script>

<template>

    <Head :title="category.name" />

    <EcommerceLayout>
        <div class="bg-gray-50 min-h-screen py-12">

            <!-- Category Title -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
                <h1 class="text-3xl font-bold text-gray-800">{{ category.name }}</h1>
            </div>

            <!-- Product Grid -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div v-if="products.length" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <div v-for="product in products" :key="product.id"
                        class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">

                        <!-- Product Image -->
                        <Link :href="`/products/${product.slug}`" class="block">
                        <div class="h-48 bg-gray-100 flex items-center justify-center overflow-hidden">
                            <img :src="`/storage/${product.main_image}`" :alt="product.name"
                                class="h-full w-full object-cover hover:scale-105 transition-transform duration-300">
                        </div>
                        </Link>

                        <!-- Product Details -->
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-1 truncate">{{ product.name }}</h3>
                            <p class="text-gray-600 text-sm mb-2 line-clamp-2">{{ product.short_description }}</p>

                            <!-- Price -->
                            <div class="flex items-center mt-3">
                                <span class="text-lg font-bold text-blue-600">৳{{ product.net_price }}</span>
                                <span v-if="product.discount > 0" class="ml-2 text-sm text-gray-500 line-through">
                                    ৳{{ product.price }}
                                </span>
                                <span v-if="product.discount > 0"
                                    class="ml-2 text-xs bg-green-100 text-green-800 px-2 py-1 rounded">
                                    {{ product.discount }}% ছাড়
                                </span>
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-4 flex justify-between">
                                <button @click="addToCart(product)"
                                    class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition">
                                    কার্টে যোগ করুন
                                </button>
                                <Link :href="`/products/${product.slug}`"
                                    class="text-blue-600 px-3 py-1 rounded text-sm hover:bg-blue-50 transition">
                                বিস্তারিত
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center text-gray-500 py-20">
                    কোনো প্রোডাক্ট পাওয়া যায়নি।
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
