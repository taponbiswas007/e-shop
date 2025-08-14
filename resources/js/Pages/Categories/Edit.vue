<template>
    <AuthenticatedLayout>
        <div class="max-w-2xl mx-auto rounded-md shadow-md bg-white p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Edit Category</h1>
                <Link href="/categories" class="text-sm text-indigo-600 hover:text-indigo-900">
                Back to Categories
                </Link>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="space-y-6">
                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Category Name</label>
                        <div class="mt-1">
                            <input type="text" id="name" v-model="form.name"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                :class="{ 'border-red-500': form.errors.name }" />
                            <p class="mt-1 text-sm text-red-600" v-if="form.errors.name">
                                {{ form.errors.name }}
                            </p>
                        </div>
                    </div>

                    <!-- Status Field -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <div class="mt-1">
                            <select id="status" v-model="form.status"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                :class="{ 'border-red-500': form.errors.status }">
                                <option :value="true">Active</option>
                                <option :value="false">Inactive</option>
                            </select>
                            <p class="mt-1 text-sm text-red-600" v-if="form.errors.status">
                                {{ form.errors.status }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-x-4">
                    <Link href="/categories"
                        class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Cancel
                    </Link>
                    <button type="submit"
                        class="rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50"
                        :disabled="form.processing">
                        <span v-if="!form.processing">Update</span>
                        <span v-else>Updating...</span>
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    category: Object
});

const form = useForm({
    name: props.category.name,
    status: props.category.status,
});

const submit = () => {
    form.put(`/categories/${props.category.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            // Optional: Add any success handling here
        },
    });
};
</script>
