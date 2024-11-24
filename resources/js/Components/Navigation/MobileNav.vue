<script setup>
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";

const props = defineProps({
    showingNavigationDropdown: {
        type: Boolean,
        default: false,
    },
    isDarkMode: {
        type: Boolean,
        required: true,
    },
});
</script>

<template>
    <div
        :class="{
            block: showingNavigationDropdown,
            hidden: !showingNavigationDropdown,
        }"
        class="sm:hidden"
    >


        <ResponsiveNavLink
            v-if="!$page.props.auth.user"
            :href="route('login')"
        >
            Log ind
        </ResponsiveNavLink>

        <ResponsiveNavLink
            v-if="!$page.props.auth.user"
            :href="route('register')"
        >
            Oprettelse
        </ResponsiveNavLink>

        <div
            v-if="$page.props.auth.user" class="space-y-1 pb-3 pt-2">
            <ResponsiveNavLink
                :href="route('dashboard')"
                :active="route().current('dashboard')"
            >
                Dashboard
            </ResponsiveNavLink>
        </div>


        <ResponsiveNavLink
            :href="route('checkout')"
        >
            Tjek ud
        </ResponsiveNavLink>

        <!-- Responsive Settings Options -->
        <div
            v-if="$page.props.auth.user"
            class="border-t border-gray-200 pb-1 pt-4 dark:border-gray-600"
        >
            <div class="px-4">
                <div
                    class="text-base font-medium text-gray-800 dark:text-gray-200"
                >
                    {{ $page.props.auth.user.name }}
                </div>
                <div class="text-sm font-medium text-gray-500">
                    {{ $page.props.auth.user.email }}
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <ResponsiveNavLink :href="route('profile.edit')">
                    Profile
                </ResponsiveNavLink>
                <ResponsiveNavLink
                    :href="route('logout')"
                    method="post"
                    as="button"
                >
                    Log Out
                </ResponsiveNavLink>
            </div>
        </div>

        <div class="w-full flex justify-center">
            <button
                @click="$emit('toggleDarkMode')"
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
    </div>
</template>

<style scoped>

</style>
