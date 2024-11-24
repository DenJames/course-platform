<script setup>
import {computed, ref, watch} from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import MobileNav from "@/Components/Navigation/MobileNav.vue";
import DesktopNav from "@/Components/Navigation/DesktopNav.vue";
import CartIcon from "@/Components/Icons/CartIcon.vue";

const showingNavigationDropdown = ref(false);
const isDarkMode = ref(document.querySelector('html').classList.contains('dark'));
const isAnimating = ref(false);

function toggleDarkMode() {
    isAnimating.value = true;
    const html = document.querySelector('html');
    html.classList.toggle('dark');
    isDarkMode.value = html.classList.contains('dark');

    setTimeout(() => {
        isAnimating.value = false;
    }, 500);
}

watch(isDarkMode, (newVal) => {
    const html = document.querySelector('html');
    if (newVal) {
        html.classList.add('dark');
    } else {
        html.classList.remove('dark');
    }
});
</script>

<template>
    <div class="relative">
        <!-- Animation overlay -->
        <div
            v-if="isAnimating"
            class="fixed inset-0 pointer-events-none z-[-1]"
            :class="isDarkMode ? 'dark-wave' : 'light-wave'"
        ></div>

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-500">
            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button
                    @click="showingNavigationDropdown = !showingNavigationDropdown"
                    class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none dark:text-gray-500 dark:hover:bg-gray-900 dark:hover:text-gray-400 dark:focus:bg-gray-900 dark:focus:text-gray-400"
                >
                    <svg
                        class="h-6 w-6"
                        stroke="currentColor"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <path
                            :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                        <path
                            :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>

            <MobileNav :showingNavigationDropdown="showingNavigationDropdown" :isDarkMode="isDarkMode" @toggleDarkMode="toggleDarkMode"/>

            <nav class="w-full py-4 px-24 hidden sm:flex items-center justify-between">
                <ApplicationLogo class="w-12 dark:text-gray-200"/>

                <div class="py-2 px-6 rounded-full shadow bg-white dark:bg-gray-800 border dark:border-gray-700 dark:shadow-gray-600">
                    <DesktopNav/>
                </div>

                <div class="flex items-center">
                    <div class="flex gap-6 items-center">
                        <Link :href="route('checkout')" class="relative">
                            <CartIcon class="size-6"/>

                            <div class="absolute size-5 rounded-full bg-purple-600 -top-2 -right-2 flex items-center justify-center text-purple-50 text-xs">
                                1
                            </div>
                        </Link>

                        <button
                            @click.stop="toggleDarkMode"
                            class="text-gray-800 dark:text-gray-200">
                            <svg v-if="isDarkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 3v1.5M12 19.5V21M4.219 4.219l1.061 1.061M17.719 17.719l1.061 1.061M3 12h1.5M19.5 12H21M4.219 19.781l1.061-1.061M17.719 6.281l1.061-1.061M12 7.5a4.5 4.5 0 100 9 4.5 4.5 0 000-9z"/>
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M21.752 15.002A9.718 9.718 0 0112.003 21c-5.385 0-9.75-4.365-9.75-9.75 0-4.42 2.91-8.166 6.878-9.448a.75.75 0 01.917.917 7.5 7.5 0 109.704 9.704.75.75 0 01.917.917z"/>
                            </svg>
                        </button>
                    </div>

                    <div class="hidden sm:ms-3 sm:flex sm:items-center" v-if="$page.props.auth.user">
                        <!-- Settings Dropdown -->
                        <div class="relative ms-3">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                        <button
                                            type="button"
                                            class="w-12 h-12 bg-purple-500 rounded-full text-white"
                                        >
                                            {{ $page.props.auth.user.name.slice(0, 2) }}
                                        </button>
                                </template>

                                <template #content>
                                    <DropdownLink
                                        :href="route('profile.edit')"
                                    >
                                        Profile
                                    </DropdownLink>
                                    <DropdownLink
                                        :href="route('logout')"
                                        method="post"
                                        as="button"
                                    >
                                        Log Out
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header
                class="bg-white shadow dark:bg-gray-800"
                v-if="$slots.header"
            >
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header"/>
                </div>
            </header>

            <!-- Page Content -->
            <main class="px-4 sm:px-10 md:px-24 lg:px-64 xl:px-96">
                <slot/>
            </main>
        </div>
    </div>
</template>

<style scoped>
@keyframes darkWave {
    from {
        clip-path: circle(0% at center);
    }
    to {
        clip-path: circle(150% at center);
    }
}

@keyframes lightWave {
    from {
        clip-path: circle(150% at center);
    }
    to {
        clip-path: circle(0% at center);
    }
}

.dark-wave {
    /* Changed animation duration to 500ms */
    animation: darkWave 500ms ease-in-out forwards;
}

.light-wave {
    /* Changed animation duration to 500ms */
    animation: lightWave 500ms ease-in-out forwards;
}
</style>
