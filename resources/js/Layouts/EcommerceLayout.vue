<script setup>
import { ref, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

// Heroicons (outline)
import {
    ShoppingCartIcon,
    HeartIcon,
    Bars3Icon,
    XMarkIcon,
    ArrowLeftOnRectangleIcon,
} from "@heroicons/vue/24/outline";

const page = usePage();
const user = computed(() => page.props?.auth?.user ?? null);
const firstLetter = computed(() =>
    user.value && user.value.name ? user.value.name.charAt(0).toUpperCase() : ""
);
const cartCount = computed(() => page.props?.cartCount ?? 0);

// mobile menu toggle
const open = ref(false);
const toggle = () => (open.value = !open.value);

// sample promo text (you can replace with dynamic prop)
const promoText = ref(
    "Flash Offer: Up to 40% off on selected electric tools! Free shipping over ৳3000"
);
</script>

<template>
    <div class="min-h-screen flex flex-col bg-gray-50 text-slate-800">
        <!-- TOP PROMO BAR -->
        <div class="bg-gradient-to-r from-indigo-600 via-yellow-500 to-amber-400 text-white">
            <div class="mx-auto flex items-center justify-between px-4 py-2 gap-4">
                <!-- Animated promo (modern marquee) -->
                <div class="overflow-hidden flex-1">
                    <div class="promo-track whitespace-nowrap animate-marquee">
                        <span class="inline-block px-6 py-1 font-medium">
                            ⚡ {{ promoText }}
                        </span>
                        <span class="inline-block px-6 py-1 font-medium">⚡ Extra discounts for registered users!</span>
                        <span class="inline-block px-6 py-1 font-medium">⚡ New Arrivals: Smart Plugs, LED Strips, Power
                            Tools</span>
                    </div>
                </div>

                <!-- Auth actions -->
                <div class="flex items-center gap-3">
                    <template v-if="!user">
                        <Link href="/login" class="text-sm hover:underline">Login</Link>
                        <span class="hidden sm:inline text-sm">|</span>
                        <Link href="/register" class="text-sm hover:underline">Register</Link>
                    </template>
                    <template v-else>
                        <div class="flex content-end items-start">
                            <Link href="/profile" class="flex shrink-0 items-center gap-2">
                            <div
                                class="w-9 h-9 rounded-full bg-white text-indigo-600 font-semibold flex items-center justify-center shadow-avatar">
                                {{ firstLetter }}
                            </div>
                            </Link>
                            <Link href="/logout" method="post" as="button"
                                class="flex items-center px-4 py-3 w-full text-left hover:bg-gray-200">
                            <ArrowLeftOnRectangleIcon class="w-5 h-5 mr-3" />
                            Logout
                            </Link>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- MIDDLE HEADER -->
        <header class="bg-white shadow-sm border-b">
            <div class=" mx-auto flex items-center justify-between px-4 py-4 gap-4">
                <!-- logo + brand -->
                <div class="flex items-center gap-3">
                    <Link href="/" class="flex items-center gap-3">
                    <ApplicationLogo class="h-12 w-25 text-indigo-600" />
                    <div class="leading-tight">
                        <div class="text-lg font-extrabold text-slate-800">
                            Alanoor Electric Store
                        </div>
                        <div class="text-xs text-slate-500 -mt-0.5">
                            Powering your home & tools
                        </div>
                    </div>
                    </Link>
                </div>

                <!-- search (hidden on tiny screens) -->
                <div class="flex-1 px-4 max-w-2xl hidden sm:block">
                    <form class="relative">
                        <input aria-label="Search products" placeholder="Search for products, tools, cables, bulbs..."
                            class="w-full border border-slate-200 rounded-full py-2.5 pl-4 pr-12 focus:outline-none focus:ring-2 focus:ring-amber-400" />
                        <button type="submit"
                            class="absolute right-2 top-1.5 px-3 py-1.5 rounded-full bg-amber-400 text-white text-sm">
                            Search
                        </button>
                    </form>
                </div>

                <!-- actions & mobile toggle -->
                <div class="flex items-center gap-3">
                    <Link href="/favorites"
                        class="hidden sm:flex items-center gap-2 text-slate-600 hover:text-amber-500">
                    <HeartIcon class="w-6 h-6" />
                    <span class="text-sm hidden md:inline">Favorites</span>
                    </Link>

                    <!-- Cart Icon -->
                    <Link href="/cart" class="relative flex items-center gap-2 text-slate-600 hover:text-amber-500">
                    <ShoppingCartIcon class="w-6 h-6" />
                    <span class="text-sm hidden md:inline">Cart</span>

                    <!-- Cart count badge -->
                    <span v-if="cartCount > 0"
                        class="absolute -top-1 -right-2 bg-red-500 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center">
                        {{ cartCount }}
                    </span>
                    </Link>

                    <button @click="toggle" class="sm:hidden p-2 rounded-md text-slate-600 hover:bg-slate-100">
                        <span v-if="!open">
                            <Bars3Icon class="w-6 h-6" />
                        </span>
                        <span v-else>
                            <XMarkIcon class="w-6 h-6" />
                        </span>
                    </button>
                </div>
            </div>

            <!-- bottom nav (desktop) -->
            <nav class="bg-amber-400 sm:block hidden">
                <div class=" mx-auto px-4">
                    <ul class="flex flex-wrap justify-center gap-6 text-white font-medium py-2 text-sm">
                        <li>
                            <Link href="/" class="py-1 px-2 hover:underline">Home</Link>
                        </li>
                        <li>
                            <Link href="/products" class="py-1 px-2 hover:underline">Products</Link>
                        </li>
                        <li>
                            <Link href="/brands" class="py-1 px-2 hover:underline">Brands</Link>
                        </li>
                        <li>
                            <Link href="/deals" class="py-1 px-2 hover:underline">Deals</Link>
                        </li>
                        <li>
                            <Link href="/about" class="py-1 px-2 hover:underline">About</Link>
                        </li>
                        <li>
                            <Link href="/contact" class="py-1 px-2 hover:underline">Contact</Link>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- mobile menu (collapsible) -->
            <transition name="fade">
                <div v-show="open" class="sm:hidden bg-white border-t">
                    <div class="px-4 py-3 space-y-2">
                        <Link href="/" class="block py-2 text-slate-700">Home</Link>
                        <Link href="/products" class="block py-2 text-slate-700">Products</Link>
                        <Link href="/deals" class="block py-2 text-slate-700">Deals</Link>
                        <Link href="/brands" class="block py-2 text-slate-700">Brands</Link>
                        <Link href="/contact" class="block py-2 text-slate-700">Contact</Link>
                    </div>
                </div>
            </transition>
        </header>
        <main class="p-4 overflow-y-auto flex-1">
            <slot />
        </main>

        <!-- SITE FOOTER (simple) -->
        <footer class="bg-slate-800 text-slate-200">
            <div class="max-w-7xl mx-auto px-4 py-6 flex flex-col sm:flex-row justify-between items-center gap-3">
                <div class="text-sm">
                    © {{ new Date().getFullYear() }} E-lectric Mart. All rights
                    reserved.
                </div>
                <div class="flex gap-4 text-sm">
                    <Link href="/help" class="hover:underline">Help</Link>
                    <Link href="/returns" class="hover:underline">Returns</Link>
                    <Link href="/contact" class="hover:underline">Support</Link>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
/* marquee-like animation (replace marquee tag) */
@keyframes marquee {
    0% {
        transform: translateX(0%);
    }

    100% {
        transform: translateX(-50%);
    }
}

.promo-track {
    display: inline-flex;
    gap: 1rem;
    will-change: transform;
    animation: marquee 18s linear infinite;
}

@media (prefers-reduced-motion: reduce) {
    .promo-track {
        animation: none;
    }

    /* avatar shadow (3D feel) */
}

.shadow-avatar {
    box-shadow: 0 6px 18px rgba(99, 102, 241, 0.18),
        inset 0 -4px 6px rgba(0, 0, 0, 0.04);
}

/* subtle fade transition */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.18s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.fade-enter-to,
.fade-leave-from {
    opacity: 1;
}

/* small rounding for card 3D-ish */
</style>
