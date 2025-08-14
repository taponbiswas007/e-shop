<template>
    <AuthenticatedLayout>
        <div class="max-w-2xl mx-auto rounded-md shadow-md bg-white p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Create Category</h1>

            <form @submit.prevent="submit">
                <div class="space-y-6">
                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Category Name</label>
                        <input type="text" id="name" v-model="form.name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            :class="{ 'border-red-500': form.errors.name }" />
                        <p class="mt-1 text-sm text-red-600" v-if="form.errors.name">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <!-- Status Field - Fixed Boolean Handling -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="status" v-model="form.status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            :class="{ 'border-red-500': form.errors.status }">
                            <option :value="true">Active</option>
                            <option :value="false">Inactive</option>
                        </select>
                        <p class="mt-1 text-sm text-red-600" v-if="form.errors.status">
                            {{ form.errors.status }}
                        </p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <Link href="/categories"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                    </Link>
                    <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        :disabled="form.processing">
                        <span v-if="!form.processing">Save</span>
                        <span v-else>Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    status: true, // Default to true (Active)
});

const submit = () => {
    form.post('/categories', {
        onSuccess: () => {
            form.reset();
        },
        preserveScroll: true,
    });
};
</script>
