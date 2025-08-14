<template>
    <AuthenticatedLayout>
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Categories</h1>
                <Link href="/categories/create"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                + Add New
                </Link>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="category in categories" :key="category.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ category.name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="{
                                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800': category.status,
                                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800': !category.status
                                }">
                                    {{ category.status ? 'Active' : 'Deactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <Link :href="`/categories/${category.id}/edit`"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3">
                                Edit
                                </Link>
                                <button @click="deleteCategory(category.id)" class="text-red-600 hover:text-red-900">
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
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, router } from '@inertiajs/vue3';
defineProps({ categories: Array });

function deleteCategory(id) {
    if (confirm("Are you sure?")) {
        router.delete(`/categories/${id}`);
    }
}
</script>
