<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-blue-50 via-white to-blue-100 relative overflow-hidden">

        <!-- Electric 3D Style Background Animation -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="electric-lines"></div>
            <div class="electric-sparks"></div>
        </div>

        <!-- Logo Top -->
        <div class="z-10 flex flex-col items-center mb-6">
            <Link href="/">
            <ApplicationLogo class="h-20 w-20 fill-current text-blue-600 drop-shadow-lg" />
            </Link>
            <h1 class="mt-4 text-2xl font-bold text-gray-700 tracking-wide">
                Welcome to ElectroShop ⚡
            </h1>
            <p class="text-gray-500 text-sm">Your trusted electric goods partner</p>
        </div>

        <!-- Auth Card -->
        <div
            class="w-full max-w-md bg-white/90 backdrop-blur-md shadow-xl rounded-xl px-8 py-6 z-10 border border-blue-100">
            <slot />
        </div>

        <!-- Small Promo Text -->
        <div class="mt-6 text-xs text-gray-400 z-10">
            © {{ new Date().getFullYear() }} ElectroShop — Powered by Innovation ⚡
        </div>
    </div>
</template>

<style scoped>
/* Electric line animation */
.electric-lines {
    background: repeating-linear-gradient(to right,
            rgba(0, 153, 255, 0.1) 0px,
            rgba(0, 153, 255, 0.1) 2px,
            transparent 2px,
            transparent 4px);
    position: absolute;
    width: 200%;
    height: 100%;
    animation: moveLines 6s linear infinite;
}

@keyframes moveLines {
    from {
        transform: translateX(0);
    }

    to {
        transform: translateX(-50%);
    }
}

/* Electric sparks */
.electric-sparks {
    position: absolute;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle, rgba(0, 153, 255, 0.15) 0%, transparent 70%) repeat;
    background-size: 200px 200px;
    animation: flicker 3s infinite ease-in-out;
}

@keyframes flicker {

    0%,
    100% {
        opacity: 0.4;
    }

    50% {
        opacity: 0.8;
    }
}
</style>
