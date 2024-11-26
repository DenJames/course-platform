<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

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
</script>

<template>
    <AppLayout>
        <Head title="Log in" />

        <div
            class="flex min-h-[calc(100svh-200px)] flex-col items-center pt-6 justify-center sm:pt-0"
        >
            <div>
                <Link href="/">
                    <ApplicationLogo class="h-20 w-20 fill-current text-gray-500" />
                </Link>
            </div>

            <div
                class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg dark:bg-gray-800"
            >
                <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
                    {{ status }}
                </div>

                <form @submit.prevent="submit">
                    <div>
                        <InputLabel for="email" value="Email" />

                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                        />

                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="password" value="Password" />

                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                        />

                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="mt-4 flex items-center justify-between">
                        <label class="flex items-center">
                            <Checkbox name="remember" v-model:checked="form.remember" />
                            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400"
                            >Remember me</span
                            >
                        </label>

                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="rounded-md text-sm text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                        >
                            Forgot your password?
                        </Link>
                    </div>

                    <div class="mt-4 flex items-center justify-end">
                        <Link :href="route('register')" class="ms-2 text-sm text-gray-600 dark:text-gray-400 underline hover:text-black">
                            Opret bruger
                        </Link>

                        <PrimaryButton
                            class="ms-4"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Log in
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
