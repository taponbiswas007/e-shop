<template>
    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Product</h1>
                    <nav class="flex mt-2" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-2">
                            <li>
                                <div class="flex items-center">
                                    <Link href="/dashboard"
                                        class="text-sm font-medium text-gray-500 hover:text-gray-700">Dashboard</Link>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <ChevronRightIcon class="h-5 w-5 flex-shrink-0 text-gray-400" />
                                    <Link href="/products"
                                        class="ml-2 text-sm font-medium text-gray-500 hover:text-gray-700">Products
                                    </Link>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <ChevronRightIcon class="h-5 w-5 flex-shrink-0 text-gray-400" />
                                    <span class="ml-2 text-sm font-medium text-gray-500">Edit</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
                <Link href="/products"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Back to Products
                </Link>
            </div>

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <form @submit.prevent="submit">
                    <div class="px-4 py-5 sm:p-6 space-y-6">
                        <!-- Success Message -->
                        <div v-if="$page.props.flash.success" class="rounded-md bg-green-50 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <CheckCircleIcon class="h-5 w-5 text-green-400" />
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">
                                        {{ $page.props.flash.success }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Basic Information Section -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Basic Information</h3>
                            <p class="mt-1 text-sm text-gray-500">General product details</p>

                            <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <!-- Category -->
                                <div class="sm:col-span-3">
                                    <label for="category_id"
                                        class="block text-sm font-medium text-gray-700">Category</label>
                                    <select id="category_id" v-model="form.category_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                                        :class="{ 'border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500': form.errors.category_id }">
                                        <option value="" disabled>Select a category</option>
                                        <option v-for="category in categories" :key="category.id" :value="category.id">
                                            {{ category.name }}
                                        </option>
                                    </select>
                                    <p v-if="form.errors.category_id" class="mt-2 text-sm text-red-600">
                                        {{ form.errors.category_id }}
                                    </p>
                                </div>

                                <!-- Product Name -->
                                <div class="sm:col-span-6">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Product
                                        Name</label>
                                    <input type="text" id="name" v-model="form.name"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        :class="{ 'border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500': form.errors.name }" />
                                    <p v-if="form.errors.name" class="mt-2 text-sm text-red-600">
                                        {{ form.errors.name }}
                                    </p>
                                </div>

                                <!-- Short Description -->
                                <div class="sm:col-span-6">
                                    <label for="short_description" class="block text-sm font-medium text-gray-700">Short
                                        Description</label>
                                    <textarea id="short_description" v-model="form.short_description" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        :class="{ 'border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500': form.errors.short_description }" />
                                    <p v-if="form.errors.short_description" class="mt-2 text-sm text-red-600">
                                        {{ form.errors.short_description }}
                                    </p>
                                </div>

                                <!-- Full Description -->
                                <div class="sm:col-span-6">
                                    <label for="description" class="block text-sm font-medium text-gray-700">Full
                                        Description</label>
                                    <textarea id="description" v-model="form.description" rows="5"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        :class="{ 'border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500': form.errors.description }" />
                                    <p v-if="form.errors.description" class="mt-2 text-sm text-red-600">
                                        {{ form.errors.description }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Images Section -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Images</h3>
                            <p class="mt-1 text-sm text-gray-500">Update product images</p>

                            <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <!-- Main Image -->
                                <div class="sm:col-span-6">
                                    <label class="block text-sm font-medium text-gray-700">Main Image</label>
                                    <div class="mt-1 flex items-center">
                                        <span class="inline-block h-16 w-16 overflow-hidden rounded-lg bg-gray-100">
                                            <img v-if="form.main_image_preview" :src="form.main_image_preview"
                                                class="h-full w-full object-cover" @error="handleImageError" />
                                            <img v-else-if="product.main_image_url" :src="product.main_image_url"
                                                class="h-full w-full object-cover" @error="handleImageError" />
                                            <svg v-else class="h-full w-full text-gray-300" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </span>
                                        <input type="file" ref="mainImageInput"
                                            class="ml-5 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                            accept="image/*" @change="handleMainImageChange" />
                                        <button v-if="form.main_image_preview || product.main_image_url" type="button"
                                            class="ml-2 text-sm text-red-600" @click="removeMainImage">
                                            Remove
                                        </button>
                                    </div>
                                    <p v-if="form.errors.main_image" class="mt-2 text-sm text-red-600">
                                        {{ form.errors.main_image }}
                                    </p>
                                </div>

                                <!-- Additional Images -->
                                <div class="sm:col-span-6">
                                    <label class="block text-sm font-medium text-gray-700">Additional Images</label>
                                    <div class="mt-1 grid grid-cols-2 gap-4 sm:grid-cols-5">
                                        <div v-for="i in 5" :key="i" class="relative">
                                            <label :for="`image_${i}`" class="block">
                                                <span class="sr-only">Choose image {{ i }}</span>
                                                <div
                                                    class="aspect-w-1 aspect-h-1 overflow-hidden rounded-lg bg-gray-100">
                                                    <img v-if="form[`image_${i}_preview`]"
                                                        :src="form[`image_${i}_preview`]" class="object-cover"
                                                        @error="handleImageError" />
                                                    <img v-else-if="product[`image_${i}_url`]"
                                                        :src="product[`image_${i}_url`]" class="object-cover"
                                                        @error="handleImageError" />
                                                    <svg v-else class="h-full w-full text-gray-300" fill="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path
                                                            d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                                    </svg>
                                                </div>
                                                <input type="file" :id="`image_${i}`" :ref="`image_${i}Input`"
                                                    class="sr-only" accept="image/*"
                                                    @change="(e) => handleImageChange(e, i)" />
                                                <input type="hidden" v-model="form[`remove_image_${i}`]">
                                            </label>
                                            <button v-if="form[`image_${i}_preview`] || product[`image_${i}_url`]"
                                                type="button"
                                                class="absolute top-0 right-0 p-1 bg-red-500 rounded-full text-white"
                                                @click="removeImage(i)">
                                                <XMarkIcon class="h-4 w-4" />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pricing Section -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Pricing</h3>
                            <p class="mt-1 text-sm text-gray-500">Update product pricing information</p>

                            <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <!-- Price -->
                                <div class="sm:col-span-2">
                                    <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">$</span>
                                        </div>
                                        <input type="number" id="price" v-model="form.price" step="0.01" min="0"
                                            class="block w-full pl-7 pr-12 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            :class="{ 'border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500': form.errors.price }"
                                            placeholder="0.00" @input="calculateNetPrice" />
                                        <div
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">USD</span>
                                        </div>
                                    </div>
                                    <p v-if="form.errors.price" class="mt-2 text-sm text-red-600">
                                        {{ form.errors.price }}
                                    </p>
                                </div>

                                <!-- Discount -->
                                <div class="sm:col-span-2">
                                    <label for="discount"
                                        class="block text-sm font-medium text-gray-700">Discount</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input type="number" id="discount" v-model="form.discount" step="0.01" min="0"
                                            max="100"
                                            class="block w-full pr-12 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            :class="{ 'border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500': form.errors.discount }"
                                            placeholder="0" @input="calculateNetPrice" />
                                        <div
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">%</span>
                                        </div>
                                    </div>
                                    <p v-if="form.errors.discount" class="mt-2 text-sm text-red-600">
                                        {{ form.errors.discount }}
                                    </p>
                                </div>

                                <!-- Net Price -->
                                <div class="sm:col-span-2">
                                    <label for="net_price" class="block text-sm font-medium text-gray-700">Net
                                        Price</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">$</span>
                                        </div>
                                        <input type="number" id="net_price" v-model="form.net_price" readonly
                                            class="block w-full pl-7 rounded-md bg-gray-100 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            :class="{ 'border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500': form.errors.net_price }" />
                                    </div>
                                    <p v-if="form.errors.net_price" class="mt-2 text-sm text-red-600">
                                        {{ form.errors.net_price }}
                                    </p>
                                </div>

                                <!-- Unit -->
                                <div class="sm:col-span-2">
                                    <label for="unit" class="block text-sm font-medium text-gray-700">Unit</label>
                                    <input type="text" id="unit" v-model="form.unit"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        :class="{ 'border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500': form.errors.unit }"
                                        placeholder="e.g., kg, piece, box" />
                                    <p v-if="form.errors.unit" class="mt-2 text-sm text-red-600">
                                        {{ form.errors.unit }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Video & Status Section -->
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Media & Status</h3>
                            <p class="mt-1 text-sm text-gray-500">Additional media and product status</p>

                            <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <!-- Video URL -->
                                <div class="sm:col-span-4">
                                    <label for="video_url" class="block text-sm font-medium text-gray-700">YouTube Video
                                        URL</label>
                                    <input type="url" id="video_url" v-model="form.video_url"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        :class="{ 'border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500': form.errors.video_url }"
                                        placeholder="https://www.youtube.com/watch?v=..." />
                                    <p v-if="form.errors.video_url" class="mt-2 text-sm text-red-600">
                                        {{ form.errors.video_url }}
                                    </p>
                                </div>

                                <!-- Status -->
                                <div class="sm:col-span-2">
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select id="status" v-model="form.status"
                                        class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                                        :class="{ 'border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500': form.errors.status }">
                                        <option :value="true">Active</option>
                                        <option :value="false">Inactive</option>
                                    </select>
                                    <p v-if="form.errors.status" class="mt-2 text-sm text-red-600">
                                        {{ form.errors.status }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="button" @click="deleteProduct"
                            class="mr-3 inline-flex justify-center rounded-md border border-transparent bg-red-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            Delete Product
                        </button>
                        <button type="submit"
                            class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            :disabled="form.processing">
                            <span v-if="!form.processing">Update Product</span>
                            <span v-else>
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Updating...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <ConfirmationModal :show="showDeleteModal" @close="showDeleteModal = false">
            <template #title>
                Delete Product
            </template>
            <template #content>
                Are you sure you want to delete <span class="font-semibold">{{ product.name }}</span>? This action
                cannot be undone.
            </template>
            <template #footer>
                <button @click="showDeleteModal = false" type="button"
                    class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
                <button @click="confirmDelete" type="button"
                    class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Delete
                </button>
            </template>
        </ConfirmationModal>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue'
import { useForm, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import ConfirmationModal from '@/Components/ConfirmationModal.vue'
import { ChevronRightIcon, XMarkIcon, CheckCircleIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    product: Object,
    categories: Array
})

const showDeleteModal = ref(false)

const form = useForm({
    category_id: props.product.category_id,
    name: props.product.name,
    short_description: props.product.short_description,
    description: props.product.description,
    main_image: null,
    main_image_preview: null,
    remove_main_image: false,
    image_1: null,
    image_1_preview: null,
    remove_image_1: false,
    image_2: null,
    image_2_preview: null,
    remove_image_2: false,
    image_3: null,
    image_3_preview: null,
    remove_image_3: false,
    image_4: null,
    image_4_preview: null,
    remove_image_4: false,
    image_5: null,
    image_5_preview: null,
    remove_image_5: false,
    video_url: props.product.video_url,
    price: props.product.price,
    discount: props.product.discount,
    net_price: props.product.net_price,
    unit: props.product.unit,
    status: props.product.status
})

const calculateNetPrice = () => {
    const price = parseFloat(form.price) || 0
    const discount = parseFloat(form.discount) || 0
    form.net_price = (price - (price * (discount / 100))).toFixed(2)
}

const handleMainImageChange = (e) => {
    const file = e.target.files[0]
    if (file) {
        form.main_image = file
        form.main_image_preview = URL.createObjectURL(file)
        form.remove_main_image = false
    }
}

const removeMainImage = () => {
    form.main_image = null
    form.main_image_preview = null
    form.remove_main_image = true
    // Reset file input
    document.querySelector('input[type="file"][ref="mainImageInput"]').value = ''
}

const handleImageChange = (e, index) => {
    const file = e.target.files[0]
    if (file) {
        form[`image_${index}`] = file
        form[`image_${index}_preview`] = URL.createObjectURL(file)
        form[`remove_image_${index}`] = false
    }
}

const removeImage = (index) => {
    form[`image_${index}`] = null
    form[`image_${index}_preview`] = null
    form[`remove_image_${index}`] = true
    // Reset file input
    document.getElementById(`image_${index}`).value = ''
}

const handleImageError = (e) => {
    e.target.src = '/images/placeholder-product.png'
    e.target.classList.add('object-contain', 'p-2')
    e.target.classList.remove('object-cover')
}

const submit = () => {
    form.put(`/products/${props.product.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
        },
    })
}

const deleteProduct = () => {
    showDeleteModal.value = true
}

const confirmDelete = () => {
    router.delete(`/products/${props.product.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            router.visit('/products')
        },
    })
}
</script>
