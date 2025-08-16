<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
const signInWithGoogle = () => {
    // First, get the auth URL from your backend
    axios.get(route('auth.google')) // Create this endpoint in your Laravel backend
        .then(response => {
            const authUrl = response.data.url;
            const width = 600;
            const height = 600;
            const left = (screen.width - width) / 2;
            const top = (screen.height - height) / 2;

            const popup = window.open(
                authUrl,
                'Google Login',
                `toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=${width}, height=${height}, top=${top}, left=${left}`
            );

            // Check for popup blocker
            if (!popup || popup.closed || typeof popup.closed === 'undefined') {
                alert('Popup blocked! Please allow popups for this site.');
                // Fallback to redirect
                window.location.href = authUrl;
            }
        })
        .catch(error => {
            console.error('Error getting auth URL:', error);
            // Fallback to direct redirect
            window.location.href = route('auth.google');
        });
};

const signInWithFacebook = () => {
    // Redirect to your backend Facebook OAuth route
    window.location.href = route('auth.facebook');

    // Alternative: Open a popup window
    const width = 600;
    const height = 600;
    const left = (screen.width - width) / 2;
    const top = (screen.height - height) / 2;
    window.open(
        route('auth.facebook'),
        'Facebook Login',
        `toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=${width}, height=${height}, top=${top}, left=${left}`
    );
};
</script>

<template>
    <AuthLayout>

        <Head title="Log in" />

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>
        <div v-if="$page.props.flash.google_error" class="mb-4 text-sm font-medium text-red-600">
            {{ $page.props.flash.google_error }}
        </div>
        <form @submit.prevent="submit" class="space-y-6">
            <div class="space-y-4">
                <div>
                    <InputLabel for="email" value="Email" class="block text-sm font-medium text-gray-700" />
                    <TextInput id="email" type="email"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        v-model="form.email" required autofocus autocomplete="username" />
                    <InputError class="mt-2 text-sm text-red-600" :message="form.errors.email" />
                </div>

                <div>
                    <InputLabel for="password" value="Password" class="block text-sm font-medium text-gray-700" />
                    <TextInput id="password" type="password"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        v-model="form.password" required autocomplete="current-password" />
                    <InputError class="mt-2 text-sm text-red-600" :message="form.errors.password" />
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <Checkbox name="remember" v-model:checked="form.remember"
                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                        <span class="ms-2 text-sm text-gray-600">Remember me</span>
                    </label>

                    <Link v-if="canResetPassword" :href="route('password.request')"
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                    Forgot password?
                    </Link>
                </div>
            </div>

            <div>
                <PrimaryButton
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in
                </PrimaryButton>
            </div>
            <div class="mt-4 text-center">
                <span class="text-sm text-gray-600">Don't have an account?</span>
                <Link :href="route('register')" class="ml-2 text-sm font-medium text-indigo-600 hover:text-indigo-500">
                Register
                </Link>
            </div>



            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">Or continue with</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <a href="" @click.prevent="signInWithGoogle"
                    class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" aria-hidden="true">
                        <path
                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                            fill="#4285F4" />
                        <path
                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                            fill="#34A853" />
                        <path
                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                            fill="#FBBC05" />
                        <path
                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                            fill="#EA4335" />
                    </svg>
                    <span class="ml-2">Google</span>
                </a>

                <a href="#" @click.prevent="signInWithFacebook"
                    class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-5 h-5" fill="#1877F2" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="ml-2">Facebook</span>
                </a>
            </div>
        </form>
    </AuthLayout>
</template>
