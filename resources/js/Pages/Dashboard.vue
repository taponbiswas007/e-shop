<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import Chart from '@/Components/Charts/Chart.vue';
import ChartDoughnut from '@/Components/Charts/ChartDoughnut.vue';
import ChartBar from '@/Components/Charts/ChartBar.vue';
// Sample data (replace with real data from your backend)
const stats = [
    { name: 'Total Sales', value: '$24,580', change: '+12%', changeType: 'positive' },
    { name: 'New Orders', value: '1,429', change: '+4.5%', changeType: 'positive' },
    { name: 'Pending Orders', value: '56', change: '-2.3%', changeType: 'negative' },
    { name: 'Returning Customers', value: '1,203', change: '+8.1%', changeType: 'positive' },
];

const recentOrders = [
    { id: '#1001', customer: 'John Doe', date: '2023-06-15', amount: '$125.00', status: 'Shipped' },
    { id: '#1002', customer: 'Jane Smith', date: '2023-06-14', amount: '$89.50', status: 'Processing' },
    { id: '#1003', customer: 'Robert Johnson', date: '2023-06-14', amount: '$234.00', status: 'Delivered' },
    { id: '#1004', customer: 'Emily Davis', date: '2023-06-13', amount: '$56.75', status: 'Shipped' },
];
</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard Overview
            </h2>
        </template>

        <template #headerActions>
            <Link href="/orders/create"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <i class="fas fa-plus mr-2"></i> New Order
            </Link>
        </template>

        <div class="py-6">
            <div class=" w-full sm:px-6 lg:px-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-4">
                    <div v-for="stat in stats" :key="stat.name" class="overflow-hidden bg-white rounded-lg shadow">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="text-gray-400 fas" :class="{
                                        'fa-dollar-sign': stat.name === 'Total Sales',
                                        'fa-shopping-cart': stat.name === 'New Orders',
                                        'fa-clock': stat.name === 'Pending Orders',
                                        'fa-user-friends': stat.name === 'Returning Customers'
                                    }"></i>
                                </div>
                                <div class="flex-1 w-0 ml-4">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">
                                            {{ stat.name }}
                                        </dt>
                                        <dd>
                                            <div class="text-lg font-semibold text-gray-900">
                                                {{ stat.value }}
                                            </div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                            <div class="mt-4">
                                <span :class="{
                                    'text-green-600': stat.changeType === 'positive',
                                    'text-red-600': stat.changeType === 'negative'
                                }" class="text-sm font-medium">
                                    {{ stat.change }} from last week
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 gap-6 mb-8 lg:grid-cols-2">
                    <div class="p-6 bg-white rounded-lg shadow">
                        <h3 class="text-lg font-medium text-gray-900">Sales Overview</h3>
                        <div class="mt-4 h-80">
                            <Chart :data="salesChartData" /> <!-- Replace with your chart component -->
                        </div>
                    </div>
                    <div class="p-6 bg-white rounded-lg shadow">
                        <h3 class="text-lg font-medium text-gray-900">Revenue Sources</h3>
                        <div class="mt-4 h-80">
                            <ChartDoughnut :data="revenueChartData" /> <!-- Replace with your chart component -->
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="mb-8 overflow-hidden bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                            Recent Orders
                        </h3>
                        <p class="max-w-2xl mt-1 text-sm text-gray-500">
                            Latest customer purchases
                        </p>
                    </div>
                    <div class="border-t border-gray-200">
                        <!-- scroll wrapper -->
                        <div class="overflow-x-auto">
                            <!-- inline-block + min-w-full ensures scroll works -->
                            <div class="inline-block min-w-full align-middle">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                                Order #</th>
                                            <th
                                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                                Customer</th>
                                            <th
                                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                                Date</th>
                                            <th
                                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                                Amount</th>
                                            <th
                                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                                Status</th>
                                            <th class="px-6 py-3 relative">
                                                <span class="sr-only">Action</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="order in recentOrders" :key="order.id">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{
                                                order.id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{
                                                order.customer }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ order.date
                                                }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{
                                                order.amount }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <span :class="{
                                                    'bg-green-100 text-green-800': order.status === 'Delivered',
                                                    'bg-yellow-100 text-yellow-800': order.status === 'Processing',
                                                    'bg-blue-100 text-blue-800': order.status === 'Shipped'
                                                }" class="inline-flex px-2 text-xs font-semibold leading-5 rounded-full">
                                                    {{ order.status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                                <Link :href="`/orders/${order.id}`"
                                                    class="text-indigo-600 hover:text-indigo-900">View</Link>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <Link href="/products" class="flex items-start p-6 bg-white rounded-lg shadow hover:bg-gray-50">
                    <div class="flex-shrink-0 p-3 bg-indigo-100 rounded-md">
                        <i class="text-indigo-600 fas fa-box"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Manage Products</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Add, edit or remove products from your store
                        </p>
                    </div>
                    </Link>
                    <Link href="/customers" class="flex items-start p-6 bg-white rounded-lg shadow hover:bg-gray-50">
                    <div class="flex-shrink-0 p-3 bg-green-100 rounded-md">
                        <i class="text-green-600 fas fa-users"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Customer Management</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            View and manage your customer database
                        </p>
                    </div>
                    </Link>
                    <Link href="/reports" class="flex items-start p-6 bg-white rounded-lg shadow hover:bg-gray-50">
                    <div class="flex-shrink-0 p-3 bg-purple-100 rounded-md">
                        <i class="text-purple-600 fas fa-chart-bar"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">View Reports</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Analyze sales and business performance
                        </p>
                    </div>
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
