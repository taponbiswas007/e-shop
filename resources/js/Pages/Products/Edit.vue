<template>
    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white shadow-xl rounded-xl overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
                    <h2 class="text-2xl font-bold text-white">Edit Product</h2>
                </div>

                <form @submit.prevent="submit" enctype="multipart/form-data" class="p-6 space-y-6">
                    <!-- Basic Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input v-model="form.name" type="text" placeholder="Product Name"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                required />
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select v-model="form.category_id"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                    {{ cat.name }}
                                </option>
                            </select>
                        </div>

                        <!-- Price -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">$</span>
                                <input v-model="form.price" type="number" placeholder="0.00"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 pl-8 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                    required />
                            </div>
                        </div>

                        <!-- Discount -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Discount (%)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500">%</span>
                                <input v-model="form.discount" type="number" placeholder="0"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-8 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
                            </div>
                        </div>

                        <!-- Net Price -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Net Price</label>
                            <input v-model="form.net_price" type="number" readonly
                                class="w-full border border-gray-300 bg-gray-100 rounded-lg px-4 py-2 font-medium text-gray-700" />
                        </div>

                        <!-- Unit -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                            <input v-model="form.unit" type="text" placeholder="e.g., Kg, Piece, Coil"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select v-model="form.status"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="1" class="text-green-600">Active</option>
                                <option value="0" class="text-red-600">Inactive</option>
                            </select>
                            <p v-if="errors.status" class="mt-1 text-sm text-red-600">{{ errors.status }}</p>
                        </div>
                    </div>

                    <!-- Description & Video -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Short Description</label>
                            <textarea v-model="form.short_description" placeholder="Brief product description"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                rows="3"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea v-model="form.description" placeholder="Detailed product description"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                rows="5"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Video URL</label>
                            <div class="flex gap-2 items-center">
                                <input v-model="form.video_url" type="text" placeholder="https://youtube.com/..."
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
                                <button type="button" v-if="form.video_url" @click="showVideo = true"
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">Preview</button>
                            </div>
                        </div>
                    </div>

                    <!-- Images Section -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Product Images</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                            <template v-for="i in 6" :key="i">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        {{ i === 1 ? 'Main Image' : `Image ${i - 1}` }}
                                    </label>
                                    <div class="relative group">
                                        <div v-if="form[`existing_image_${i}`] || previewImages[i]"
                                            class="border-2 border-dashed border-gray-300 rounded-lg overflow-hidden h-40 flex items-center justify-center bg-gray-50">
                                            <img :src="previewImages[i] || `/storage/${form[`existing_image_${i}`]}`"
                                                class="max-h-full max-w-full object-contain" />
                                            <button type="button" @click="removeImage(i)"
                                                class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div v-else
                                            class="border-2 border-dashed border-gray-300 rounded-lg h-40 flex items-center justify-center bg-gray-50 text-gray-400">
                                            <div class="text-center p-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-8 w-8"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <p class="mt-1 text-xs">Click to upload</p>
                                            </div>
                                        </div>
                                        <input type="file" @change="e => handleFile(e, i)"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                            accept="image/*" />
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4 flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                            Update Product
                        </button>
                    </div>
                </form>
            </div>


            <!-- Video Modal -->
            <div v-if="showVideo" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <!-- Background overlay -->
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                        @click="showVideo = false">
                    </div>

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
                                        <iframe v-if="youtubeEmbedUrl" class="w-full h-64 sm:h-96"
                                            :src="youtubeEmbedUrl" frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen>
                                        </iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" @click="showVideo = false"
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
import { ref, watch, computed } from 'vue';
import { router as Inertia } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    product: Object,
    categories: Array,
    errors: Object,
});

// Form reactive object
const form = ref({
    name: props.product.name,
    category_id: props.product.category_id,
    price: props.product.price,
    discount: props.product.discount,
    net_price: props.product.net_price,
    unit: props.product.unit,
    status: props.product.status,
    short_description: props.product.short_description,
    description: props.product.description,
    video_url: props.product.video_url,
    main_image: null,
    image_1: null,
    image_2: null,
    image_3: null,
    image_4: null,
    image_5: null,
    existing_image_1: props.product.main_image,
    existing_image_2: props.product.image_1,
    existing_image_3: props.product.image_2,
    existing_image_4: props.product.image_3,
    existing_image_5: props.product.image_4,
    existing_image_6: props.product.image_5,
});

// Net price calculation
watch([() => form.value.price, () => form.value.discount], () => {
    const price = parseFloat(form.value.price) || 0;
    const discount = parseFloat(form.value.discount) || 0;
    form.value.net_price = (price - (price * discount / 100)).toFixed(2);
});

// Image previews
const previewImages = ref({});

function handleFile(e, index) {
    const file = e.target.files[0];
    if (!file) return;
    const imgIndex = index === 1 ? 'main_image' : `image_${index - 1}`;
    form.value[imgIndex] = file;

    const reader = new FileReader();
    reader.onload = (event) => {
        previewImages.value[index] = event.target.result;
    };
    reader.readAsDataURL(file);
}

function removeImage(index) {
    const imgIndex = index === 1 ? 'main_image' : `image_${index - 1}`;
    form.value[imgIndex] = null;
    form.value[`existing_image_${index}`] = null;
    previewImages.value[index] = null;
}

// Video modal
const showVideo = ref(false);
const youtubeEmbedUrl = computed(() => {
    if (!form.value.video_url) return '';
    let videoId = form.value.video_url.split('v=')[1];
    if (!videoId) return form.value.video_url;
    const ampersandPosition = videoId.indexOf('&');
    if (ampersandPosition !== -1) videoId = videoId.substring(0, ampersandPosition);
    return `https://www.youtube.com/embed/${videoId}`;
});

// Submit
function submit() {
    const data = new FormData();
    Object.keys(form.value).forEach(key => {
        if (form.value[key] !== null) data.append(key, form.value[key]);
    });

    // Remove images if deleted
    for (let i = 1; i <= 6; i++) {
        if (!form.value[`existing_image_${i}`] && !previewImages.value[i]) {
            data.append(`remove_image_${i}`, '1');
        }
    }

    Inertia.post(`/products/${props.product.id}?_method=PUT`, data, { preserveScroll: true });
}
</script>

<style scoped>
img {
    transition: all 0.3s ease;
}

input[type="file"]::-webkit-file-upload-button {
    visibility: hidden;
}
</style>
