<template>
    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="bg-white shadow rounded-lg p-6">

                <h2 class="text-2xl font-semibold mb-6">Edit Product</h2>

                <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-4">

                    <!-- Name -->
                    <div>
                        <label class="block font-medium mb-1">Name</label>
                        <input v-model="form.name" type="text" placeholder="Product Name"
                            class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200" required />
                    </div>

                    <!-- Category Select -->
                    <div>
                        <label class="block font-medium mb-1">Category</label>
                        <select v-model="form.category_id" class="w-full border rounded px-3 py-2">
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                {{ cat.name }}
                            </option>
                        </select>
                    </div>


                    <!-- Price -->
                    <div>
                        <label class="block font-medium mb-1">Price</label>
                        <input v-model="form.price" type="number" placeholder="Price"
                            class="w-full border rounded px-3 py-2" required />
                    </div>

                    <!-- Discount -->
                    <div>
                        <label class="block font-medium mb-1">Discount (%)</label>
                        <input v-model="form.discount" type="number" placeholder="Discount"
                            class="w-full border rounded px-3 py-2" />
                    </div>

                    <!-- Net Price -->
                    <div>
                        <label class="block font-medium mb-1">Net Price</label>
                        <input v-model="form.net_price" type="number" placeholder="Net Price"
                            class="w-full border rounded px-3 py-2" readonly />
                    </div>

                    <!-- Unit -->
                    <div>
                        <label class="block font-medium mb-1">Unit</label>
                        <input v-model="form.unit" type="text" placeholder="Unit (e.g., Kg, Coil)"
                            class="w-full border rounded px-3 py-2" />
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block font-medium mb-1">Status</label>
                        <select v-model="form.status" class="w-full border rounded px-3 py-2">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <!-- Short Description -->
                    <div>
                        <label class="block font-medium mb-1">Short Description</label>
                        <textarea v-model="form.short_description" placeholder="Short Description"
                            class="w-full border rounded px-3 py-2"></textarea>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block font-medium mb-1">Description</label>
                        <textarea v-model="form.description" placeholder="Full Description"
                            class="w-full border rounded px-3 py-2"></textarea>
                    </div>

                    <!-- Video URL -->
                    <div>
                        <label class="block font-medium mb-1">Video URL</label>
                        <input v-model="form.video_url" type="text" placeholder="Video URL"
                            class="w-full border rounded px-3 py-2" />
                    </div>

                    <!-- Images Preview and Upload -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        <template v-for="i in 6">
                            <div>
                                <label class="block font-medium mb-1">Image {{ i === 1 ? 'Main' : i - 1 }}</label>
                                <div class="mb-2">
                                    <img v-if="form[`existing_image_${i}`]"
                                        :src="`/storage/${form[`existing_image_${i}`]}`"
                                        class="w-full h-24 object-cover border rounded" />
                                </div>
                                <input type="file" @change="e => handleFile(e, i)" class="w-full" />
                            </div>
                        </template>
                    </div>

                    <!-- Submit -->
                    <div class="mt-4">
                        <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                            Update Product
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router as Inertia } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { watch } from 'vue';

const props = defineProps({
    product: Object,
    categories: Array,
});

// Reactive form
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
    // images: main + 5 optional
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

watch([() => form.value.price, () => form.value.discount], () => {
    const price = parseFloat(form.value.price) || 0;
    const discount = parseFloat(form.value.discount) || 0;
    form.value.net_price = (price - (price * discount / 100)).toFixed(2);
});

// Handle file selection
function handleFile(e, index) {
    if (index === 1) form.value.main_image = e.target.files[0];
    else form.value[`image_${index - 1}`] = e.target.files[0];
}

// Submit
function submit() {
    const data = new FormData();
    data.append('name', form.value.name);
    data.append('category_id', form.value.category_id);
    data.append('price', form.value.price);
    data.append('discount', form.value.discount);
    data.append('net_price', form.value.net_price);
    data.append('unit', form.value.unit);
    data.append('status', form.value.status);
    data.append('short_description', form.value.short_description);
    data.append('description', form.value.description);
    data.append('video_url', form.value.video_url);

    // Append images if selected
    if (form.value.main_image) data.append('main_image', form.value.main_image);
    for (let i = 1; i <= 5; i++) {
        if (form.value[`image_${i}`]) data.append(`image_${i}`, form.value[`image_${i}`]);
    }

    Inertia.post(`/products/${props.product.id}?_method=PUT`, data, {
        preserveScroll: true,
    });
}
</script>

<style scoped>
/* Optional: add professional shadows, hover effect */
img {
    transition: transform 0.2s;
}

img:hover {
    transform: scale(1.05);
}
</style>
