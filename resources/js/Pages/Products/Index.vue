<template>
    <AuthenticatedLayout>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Product Management</h1>
                    <p class="mt-1 text-sm text-gray-500">Manage all your products in one place</p>
                </div>
                <Link href="/products/create"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add New Product
                </Link>
            </div>

            <!-- Success Message -->
            <div v-if="$page.props.flash.success" class="mb-6 p-4 bg-green-50 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            {{ $page.props.flash.success }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Product List</h3>
                        <div class="mt-3 sm:mt-0">
                            <input type="text" v-model="search" placeholder="Search products..."
                                class="block w-full sm:w-64 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Product
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Category
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pricing
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Media
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="product in filteredProducts" :key="product.id" class="hover:bg-gray-50">
                                <!-- Product Info Column -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-16 w-16">
                                            <img :src="product.main_image_url" @error="handleImageError"
                                                class="h-16 w-16 rounded-md object-cover">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ product.name }}</div>
                                            <div class="text-sm text-gray-500 mt-1 line-clamp-2">{{
                                                product.short_description }}</div>
                                            <div class="text-xs text-gray-400 mt-1">Unit: {{ product.unit }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Category Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ product.category }}</div>
                                </td>

                                <!-- Pricing Column -->
                                <td class="px-6 py-4">
                                    <div class="text-sm">
                                        <div class="font-medium">${{ product.net_price }}</div>
                                        <div v-if="product.discount > 0" class="text-xs text-red-500 line-through">
                                            Original: ${{ product.price }}</div>
                                        <div v-if="product.discount > 0" class="text-xs text-green-600">Discount: {{
                                            product.discount }}%</div>
                                    </div>
                                </td>

                                <!-- Media Column -->
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1 max-w-xs">
                                        <!-- Main Image -->
                                        <div class="relative h-12 w-12">
                                            <img class="h-full w-full rounded object-cover"
                                                :src="product.main_image_url" alt="Main image"
                                                @error="handleImageError">
                                            <span
                                                class="absolute bottom-0 left-0 bg-black bg-opacity-50 text-white text-xs px-1 rounded">Main</span>
                                        </div>

                                        <!-- Additional Images -->
                                        <template v-for="i in 5">
                                            <div v-if="product[`image_${i}_url`]" :key="i" class="relative h-12 w-12">
                                                <img class="h-full w-full rounded object-cover"
                                                    :src="product[`image_${i}_url`]" :alt="`Image ${i}`"
                                                    @error="handleImageError">
                                                <span
                                                    class="absolute bottom-0 left-0 bg-black bg-opacity-50 text-white text-xs px-1 rounded">{{
                                                        i }}</span>
                                            </div>
                                        </template>

                                        <!-- Video Thumbnail -->
                                        <div v-if="product.video_url" class="relative h-12 w-12">
                                            <div
                                                class="h-full w-full rounded bg-gray-200 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500"
                                                    fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z" />
                                                </svg>
                                            </div>
                                            <span
                                                class="absolute bottom-0 left-0 bg-black bg-opacity-50 text-white text-xs px-1 rounded">Video</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Status Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        :class="product.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                        {{ product.status ? 'Active' : 'Inactive' }}
                                    </span>
                                    <div class="text-xs text-gray-500 mt-1">{{ formatDate(product.created_at) }}</div>
                                </td>

                                <!-- Actions Column -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <Link :href="`/products/${product.id}/edit`"
                                            class="text-indigo-600 hover:text-indigo-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        </Link>
                                        <button @click="confirmDelete(product)" class="text-red-600 hover:text-red-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="filteredProducts.length === 0">
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No products found
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing
                                <span class="font-medium">{{ products.from }}</span>
                                to
                                <span class="font-medium">{{ products.to }}</span>
                                of
                                <span class="font-medium">{{ products.total }}</span>
                                results
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                aria-label="Pagination">
                                <Link v-for="(link, index) in products.links" :key="index" :href="link.url || '#'"
                                    :class="{
                                        'bg-indigo-50 border-indigo-500 text-indigo-600 z-10': link.active,
                                        'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': !link.active && link.url,
                                        'bg-white border-gray-300 text-gray-300 cursor-not-allowed': !link.url
                                    }" class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                    preserve-scroll v-html="link.label" />
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <ConfirmationModal :show="showDeleteModal" @close="showDeleteModal = false">
                <template #title>
                    Delete Product
                </template>
                <template #content>
                    Are you sure you want to delete <span class="font-semibold">{{ selectedProduct?.name }}</span>? This
                    action cannot be undone.
                </template>
                <template #footer>
                    <button @click="showDeleteModal = false" type="button"
                        class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                    <button @click="deleteProduct" type="button"
                        class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Delete
                    </button>
                </template>
            </ConfirmationModal>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';

const props = defineProps({
    products: Object,
});

const search = ref('');
const showDeleteModal = ref(false);
const selectedProduct = ref(null);

const filteredProducts = computed(() => {
    if (!search.value) return props.products.data;

    return props.products.data.filter(product =>
        product.name.toLowerCase().includes(search.value.toLowerCase()) ||
        product.category.toLowerCase().includes(search.value.toLowerCase()) ||
        product.short_description.toLowerCase().includes(search.value.toLowerCase())
    );
});

function confirmDelete(product) {
    selectedProduct.value = product;
    showDeleteModal.value = true;
}

function deleteProduct() {
    router.delete(`/products/${selectedProduct.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteModal.value = false;
        },
    });
}
const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'short', day: 'numeric' }
    return new Date(dateString).toLocaleDateString(undefined, options)
}
const handleImageError = (event) => {
    event.target.src = '/placeholder-image.png'; // Fallback image path
    event.target.classList.add('object-contain', 'p-2');
    event.target.classList.remove('object-cover');
};
</script>
