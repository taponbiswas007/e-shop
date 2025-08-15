<template>
    <AuthenticatedLayout>
        <div class=" mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-8">
            <!-- Success Message -->
            <div v-if="$page.props.flash.success"
                class="mb-4 md:mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-3 md:p-4 rounded">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm md:text-base">{{ $page.props.flash.success }}</p>
                </div>
            </div>

            <!-- Header and Search - Responsive Layout -->
            <div
                class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 md:mb-6 gap-3 md:gap-4">
                <h1 class="text-xl md:text-2xl font-semibold text-gray-800">Products</h1>

                <div class="w-full md:w-auto flex  gap-3 md:gap-4">
                    <!-- Search Input -->
                    <div class="relative flex-grow">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 md:h-5 w-4 md:w-5 text-gray-400"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" v-model="search" @keyup.enter="handleSearch" placeholder="Search..."
                            class="pl-9 md:pl-10 pr-3 md:pr-4 py-1 md:py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-sm md:text-base">
                    </div>

                    <Link href="/products/create"
                        class="bg-blue-600 text-white px-3 md:px-4 py-1 shrink-0  md:py-2 rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-1 md:gap-2 text-sm md:text-base">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 md:h-5 w-4 md:w-5" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class=" hidden sm:inline">Add Product</span>
                    </Link>
                </div>
            </div>

            <!-- Products Table - Responsive Container -->
            <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-3 md:px-6 py-2 md:py-3 text-left text-xs md:text-sm font-medium text-gray-500 uppercase tracking-wider">
                                    ID</th>
                                <th scope="col"
                                    class="px-3 md:px-6 py-2 md:py-3 text-left text-xs md:text-sm font-medium text-gray-500 uppercase tracking-wider">
                                    Name</th>
                                <th scope="col"
                                    class="px-3 md:px-6 py-2 md:py-3 text-left text-xs md:text-sm font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                                    Category</th>
                                <th scope="col"
                                    class="px-3 md:px-6 py-2 md:py-3 text-left text-xs md:text-sm font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">
                                    Price</th>
                                <th scope="col"
                                    class="px-3 md:px-6 py-2 md:py-3 text-left text-xs md:text-sm font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th scope="col"
                                    class="px-3 md:px-6 py-2 md:py-3 text-left text-xs md:text-sm font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                                    Images</th>
                                <th scope="col"
                                    class="px-3 md:px-6 py-2 md:py-3 text-left text-xs md:text-sm font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">
                                    Video</th>
                                <th scope="col"
                                    class="px-3 md:px-6 py-2 md:py-3 text-right text-xs md:text-sm font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="product in products.data" :key="product.id" class="hover:bg-gray-50">
                                <td
                                    class="px-3 md:px-6 py-2 md:py-4 whitespace-nowrap text-xs md:text-sm font-medium text-gray-900">
                                    {{ product.id }}</td>
                                <td
                                    class="px-3 md:px-6 py-2 md:py-4 text-xs md:text-sm text-gray-500 max-w-[100px] md:max-w-none truncate md:whitespace-nowrap">
                                    {{ product.name }}</td>
                                <td
                                    class="px-3 md:px-6 py-2 md:py-4 whitespace-nowrap text-xs md:text-sm text-gray-500 hidden sm:table-cell">
                                    {{ product.category_name || product.category_id }}</td>
                                <td
                                    class="px-3 md:px-6 py-2 md:py-4 whitespace-nowrap text-xs md:text-sm text-gray-500 hidden md:table-cell">
                                    <div v-if="product.discount" class="flex flex-col">
                                        <span class="line-through text-gray-400">${{ product.price }}</span>
                                        <span class="text-green-600 font-medium">${{ product.net_price }}</span>
                                    </div>
                                    <span v-else>${{ product.price }}</span>
                                </td>
                                <td class="px-3 md:px-6 py-2 md:py-4 whitespace-nowrap">
                                    <span v-if="product.status == 1"
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Active
                                    </span>
                                    <span v-else
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Inactive
                                    </span>
                                </td>
                                <td class="px-3 md:px-6 py-2 md:py-4 whitespace-nowrap min-w-max">
                                    <div class="flex -space-x-1 md:-space-x-2">
                                        <img v-if="product.main_image" :src="`/storage/${product.main_image}`"
                                            class="w-6 h-6 md:w-8 md:h-8 rounded-full border-2 border-white" />
                                        <img v-for="img in getProductImages(product)" :key="img"
                                            :src="`/storage/${img}`"
                                            class="w-6 h-6 md:w-8 md:h-8 rounded-full border-2 border-white" />
                                    </div>
                                </td>
                                <td
                                    class="px-3 md:px-6 py-2 md:py-4 whitespace-nowrap text-xs md:text-sm text-gray-500 hidden md:table-cell">
                                    <button v-if="product.video_url" @click="showVideoModal(product.video_url)"
                                        class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 md:h-4 w-3 md:w-4"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
                                        </svg>
                                        <span class="hidden md:inline">View</span>
                                    </button>
                                </td>
                                <td
                                    class="px-3 md:px-6 py-2 md:py-4 whitespace-nowrap text-right text-xs md:text-sm font-medium">
                                    <div class="flex justify-end space-x-1 md:space-x-2">
                                        <Link :href="`/products/${product.id}/edit`"
                                            class="text-yellow-600 hover:text-yellow-900 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 md:h-4 w-3 md:w-4"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                        <span class="hidden md:inline">Edit</span>
                                        </Link>
                                        <button @click="deleteProduct(product.id)"
                                            class="text-red-600 hover:text-red-900 flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 md:h-4 w-3 md:w-4"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span class="hidden md:inline">Delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="products.data.length === 0">
                                <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">No products found
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div
                    class="bg-white px-3 md:px-4 py-2 md:py-3 flex items-center justify-between border-t border-gray-200">
                    <div class="flex-1 flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <p class="text-xs md:text-sm text-gray-700">
                                Showing <span class="font-medium">{{ products.from }}</span> to <span
                                    class="font-medium">{{ products.to }}</span> of <span class="font-medium">{{
                                        products.total }}</span> results
                            </p>
                        </div>
                        <div class="">
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                aria-label="Pagination">
                                <Link v-for="(link, index) in products.links" :key="index" :href="link.url || '#'"
                                    :class="{
                                        'bg-blue-50 border-blue-500 text-blue-600 z-10': link.active,
                                        'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': !link.active && link.url,
                                        'pointer-events-none bg-gray-100 text-gray-300': !link.url
                                    }"
                                    class="relative inline-flex items-center px-2 md:px-3 py-1 md:py-2 border text-xs md:text-sm font-medium"
                                    preserve-scroll>
                                <span v-html="link.label"></span>
                                </Link>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Video Modal -->
            <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <!-- Background overlay -->
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                        @click="closeModal"></div>

                    <!-- Modal content -->
                    <div
                        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                        Product Video
                                    </h3>
                                    <div class="mt-2 aspect-w-16 aspect-h-9">
                                        <iframe v-if="videoUrl" class="w-full h-64 sm:h-96" :src="embedVideoUrl"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen>
                                        </iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" @click="closeModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import { debounce } from 'lodash';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    products: Object,
});

const search = ref('');
const showModal = ref(false);
const videoUrl = ref('');

// Convert YouTube URL to embed URL
const embedVideoUrl = computed(() => {
    if (!videoUrl.value) return '';
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
    const match = videoUrl.value.match(regExp);
    const videoId = (match && match[2].length === 11) ? match[2] : null;
    return `https://www.youtube.com/embed/${videoId}?autoplay=1`;
});

// Watch for search changes with debounce
watch(search, debounce(() => {
    handleSearch();
}, 300));

function handleSearch() {
    router.get('/products', { search: search.value }, {
        preserveState: true,
        replace: true,
    });
}

function deleteProduct(id) {
    if (confirm('Are you sure you want to delete this product?')) {
        router.delete(`/products/${id}`);
    }
}

function showVideoModal(url) {
    videoUrl.value = url;
    showModal.value = true;
}

function closeModal() {
    showModal.value = false;
    videoUrl.value = '';
}

function getProductImages(product) {
    const images = [];
    for (let i = 1; i <= 5; i++) {
        const key = `image_${i}`;
        if (product[key]) images.push(product[key]);
    }
    return images;
}
</script>
