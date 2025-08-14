<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4">
            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Info -->
                    <div class="space-y-6">
                        <div>
                            <label>Category</label>
                            <select v-model="form.category_id">
                                <option v-for="category in categories" :value="category.id">
                                    {{ category.name }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label>Product Name</label>
                            <input type="text" v-model="form.name">
                        </div>

                        <div>
                            <label>Short Description</label>
                            <textarea v-model="form.short_description" rows="3"></textarea>
                        </div>

                        <div>
                            <label>Description</label>
                            <textarea v-model="form.description" rows="5"></textarea>
                        </div>
                    </div>

                    <!-- Images & Media -->
                    <div class="space-y-6">
                        <div>
                            <label>Main Image</label>
                            <input type="file" @input="form.main_image = $event.target.files[0]">
                        </div>

                        <div v-for="i in 5" :key="i">
                            <label>Image {{ i }}</label>
                            <input type="file" @input="form[`image_${i}`] = $event.target.files[0]">
                        </div>

                        <div>
                            <label>YouTube Video URL</label>
                            <input type="url" v-model="form.video_url">
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div class="space-y-6">
                        <div>
                            <label>Price</label>
                            <input type="number" step="0.01" v-model="form.price">
                        </div>

                        <div>
                            <label>Discount (%)</label>
                            <input type="number" step="0.01" v-model="form.discount">
                        </div>

                        <div>
                            <label>Net Price</label>
                            <input type="number" step="0.01" v-model="form.net_price" readonly>
                        </div>

                        <div>
                            <label>Unit</label>
                            <input type="text" v-model="form.unit">
                        </div>

                        <div>
                            <label>Status</label>
                            <select v-model="form.status">
                                <option :value="true">Active</option>
                                <option :value="false">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-primary mt-6">
                    Save Product
                </button>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    categories: Array,
    product: Object
});

const form = useForm({
    category_id: props.product?.category_id ?? '',
    name: props.product?.name ?? '',
    short_description: props.product?.short_description ?? '',
    description: props.product?.description ?? '',
    main_image: null,
    image_1: null,
    image_2: null,
    image_3: null,
    image_4: null,
    image_5: null,
    video_url: props.product?.video_url ?? '',
    price: props.product?.price ?? 0,
    discount: props.product?.discount ?? 0,
    net_price: props.product?.net_price ?? 0,
    unit: props.product?.unit ?? '',
    status: props.product?.status ?? true
});

// Calculate net price when price or discount changes
watch(() => [form.price, form.discount], () => {
    form.net_price = form.price - (form.price * (form.discount / 100));
});

const submit = () => {
    if (props.product) {
        form.put(`/products/${props.product.id}`);
    } else {
        form.post('/products');
    }
};
</script>
