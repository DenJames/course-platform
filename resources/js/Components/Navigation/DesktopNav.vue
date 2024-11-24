<script setup>
import Dropdown from "@/Components/Dropdown.vue";
import NavLink from "@/Components/NavLink.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
</script>

<template>
    <div class="flex">
        <div
            class="hidden sm:flex sm:items-center gap-10"
        >
            <div class="flex gap-3">
                <NavLink
                    :href="route('dashboard')"
                >
                    Kategorier
                </NavLink>

                <NavLink
                    :href="route('dashboard')"
                >
                    Udforsk
                </NavLink>
            </div>

            <div v-if="!$page.props.auth.user" class="flex gap-3">
                <NavLink
                    :href="route('login')"
                >
                    Log ind
                </NavLink>

                <NavLink
                    :href="route('register')"
                >
                    Opret
                </NavLink>
            </div>
        </div>
    </div>
</template>
