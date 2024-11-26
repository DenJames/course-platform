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
                <Dropdown>
                    <template #trigger>
                        <button
                            class="inline-flex items-center gap-1 px-3 py-2 text-sm leading-4 font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            Kategorier
                            <ChevronDown class="size-4 ml-1"/>
                        </button>
                    </template>

                    <template #content>
                        <template v-if="$page.props.categories?.length">
                            <DropdownLink
                                v-for="category in $page.props.categories"
                                :key="category.id"
                                :href="route('courses.category', category.slug)"
                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 text-nowrap rounded"
                            >
                                <div class="flex items-center gap-2">
                                    <span>{{ category.name }}</span>
                                </div>
                            </DropdownLink>
                        </template>
                        <div v-else class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400 text-nowrap">
                            Ingen kategorier fundet
                        </div>
                    </template>
                </Dropdown>

                <NavLink
                    :href="route('welcome')"
                >
                    Alle kurser
                </NavLink>
            </div>

            <div class="flex gap-3">
                <NavLink
                    v-if="!$page.props.auth.user"
                    :href="route('login')"
                >
                    Log ind
                </NavLink>

                <NavLink
                    v-else
                    :href="route('welcome')"
                >
                    Mine kurser
                </NavLink>
            </div>
        </div>
    </div>
</template>
