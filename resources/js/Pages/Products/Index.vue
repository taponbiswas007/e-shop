<template>
    <AuthenticatedLayout>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold">Products</h1>
                <Link href="/products/create"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Add Product
                </Link>
            </div>

            <!-- Products Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border rounded-lg shadow">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border">ID</th>
                            <th class="px-4 py-2 border">Name</th>
                            <th class="px-4 py-2 border">Category</th>
                            <th class="px-4 py-2 border">Price</th>
                            <th class="px-4 py-2 border">Discount</th>
                            <th class="px-4 py-2 border">Net Price</th>
                            <th class="px-4 py-2 border">Unit</th>
                            <th class="px-4 py-2 border">Status</th>
                            <th class="px-4 py-2 border">Main Image</th>
                            <th class="px-4 py-2 border">Other Images</th>
                            <th class="px-4 py-2 border">Video</th>
                            <th class="px-4 py-2 border">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="product in products" :key="product.id" class="hover:bg-gray-50">
                            <td class="px-4 py-2 border text-center">{{ product.id }}</td>
                            <td class="px-4 py-2 border">{{ product.name }}</td>
                            <td class="px-4 py-2 border">{{ product.category_name || product.category_id }}</td>
                            <td class="px-4 py-2 border">{{ product.price }}</td>
                            <td class="px-4 py-2 border">{{ product.discount }}</td>
                            <td class="px-4 py-2 border">{{ product.net_price }}</td>
                            <td class="px-4 py-2 border">{{ product.unit }}</td>
                            <td class="px-4 py-2 border">
                                <span v-if="product.status == 1" class="text-green-600 font-medium">Active</span>
                                <span v-else class="text-red-600 font-medium">Inactive</span>
                            </td>
                            <!-- Main Image -->
                            <td class="px-4 py-2 border">
                                <img v-if="product.main_image" :src="`/storage/${product.main_image}`"
                                    class="w-16 h-16 object-cover rounded" />
                            </td>
                            <!-- Other Images -->
                            <td class="px-4 py-2 border flex flex-wrap gap-2">
                                <img v-for="img in getProductImages(product)" :key="img" :src="`/storage/${img}`"
                                    class="w-12 h-12 object-cover rounded" />
                            </td>
                            <!-- Video -->
                            <td class="px-4 py-2 border">
                                <a v-if="product.video_url" :href="product.video_url" target="_blank"
                                    class="text-blue-600 underline">Watch</a>
                            </td>
                            <!-- Actions -->
                            <td class="px-4 py-2 border space-x-2">
                                <Link :href="`/products/${product.id}/edit`"
                                    class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600 transition">
                                Edit
                                </Link>
                                <button @click="deleteProduct(product.id)"
                                    class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700 transition">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    products: Array
});

function deleteProduct(id) {
    if (confirm('Are you sure?')) {
        Inertia.delete(`/products/${id}`);
    }
}

// Function to get all existing images for a product
function getProductImages(product) {
    const images = [];
    if (product.main_image) images.push(product.main_image);
    for (let i = 1; i <= 5; i++) {
        const key = `image_${i}`;
        if (product[key]) images.push(product[key]);
    }
    return images;
}
</script>

<style scoped>
table {
    border-collapse: collapse;
}

th,
td {
    text-align: left;
}
</style>
