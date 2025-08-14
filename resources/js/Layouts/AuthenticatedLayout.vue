<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);

// Heroicons
import {
    HomeIcon,
    ShoppingCartIcon,
    CubeIcon,
    UsersIcon,
    ChartBarIcon,
    Cog6ToothIcon,
    ArrowLeftOnRectangleIcon
} from '@heroicons/vue/24/outline'

const sidebarOpen = ref(false)

const menuItems = [
    { name: 'Dashboard', route: '/dashboard', icon: HomeIcon },
    { name: 'Orders', route: '/orders', icon: ShoppingCartIcon },
    { name: 'Products', route: '/products', icon: CubeIcon },
    { name: 'Customers', route: '/customers', icon: UsersIcon },
    { name: 'Reports', route: '/reports', icon: ChartBarIcon },
    { name: 'Settings', route: '/settings', icon: Cog6ToothIcon },
]

// Close sidebar when clicking overlay
const closeSidebar = () => {
    sidebarOpen.value = false
}

</script>

<template>
    <div class="flex min-h-screen bg-gray-100">
        <!-- Overlay for small screens -->
        <div v-if="sidebarOpen" class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden" @click="closeSidebar"></div>

        <!-- Sidebar -->
        <aside :class="[
            'bg-white shadow-lg w-64 fixed inset-y-0 left-0 transform transition-transform duration-200 ease-in-out z-30',
            sidebarOpen ? 'translate-x-0' : '-translate-x-full',
            'lg:translate-x-0'
        ]">
            <div class="flex items-center justify-center h-16 border-b">
                <span class="text-xl font-bold">E-Shop Admin</span>
            </div>
            <nav class="mt-4">
                <ul>
                    <li v-for="item in menuItems" :key="item.name">
                        <Link :href="item.route"
                            class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200 transition-colors rounded">
                        <component :is="item.icon" class="w-5 h-5 mr-3" />
                        {{ item.name }}
                        </Link>
                    </li>
                </ul>
            </nav>

            <!-- Logout -->
            <div class="absolute bottom-0 w-full border-t">
                <Link href="/logout" method="post" as="button"
                    class="flex items-center px-4 py-3 w-full text-left hover:bg-gray-200">
                <ArrowLeftOnRectangleIcon class="w-5 h-5 mr-3" />
                Logout
                </Link>
            </div>
        </aside>

        <!-- Main Content -->
        <div class=" lg:ml-64 w-screen">
            <!-- Top Navbar -->
            <header class="bg-white shadow h-16 flex items-center px-4 justify-between">
                <!-- Mobile hamburger -->
                <button @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden p-2 text-gray-700 hover:bg-gray-200 rounded">
                    ☰
                </button>
                <h1 class="text-lg font-semibold">
                    <slot name="header">Dashboard</slot>
                </h1>
                <div class="flex items-center space-x-3">
                    <span class="text-gray-600">{{ $page.props.auth.user.name }}</span>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-4 overflow-y-auto flex-1">
                <slot />
            </main>
        </div>
    </div>
</template>
