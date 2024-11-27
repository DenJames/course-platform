<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AppLayout from "@/Layouts/AppLayout.vue";
import CourseItem from "@/Components/CourseItem.vue";

const props = defineProps({
    courses: {
        type: Array,
        required: true,
    },
});

const search = ref('');
const filterStatus = ref('all'); // 'all', 'completed', 'in-progress'

const filteredCourses = computed(() => {
    return props.courses
        .filter(course => {
            const matchesSearch = course.title.toLowerCase().includes(search.value.toLowerCase());

            if (filterStatus.value === 'all') return matchesSearch;
            if (filterStatus.value === 'completed') return matchesSearch && course.completed_at;
            if (filterStatus.value === 'in-progress') return matchesSearch && !course.completed_at;

            return matchesSearch;
        });
});

const courseStats = computed(() => {
    const total = props.courses.length;
    const completed = props.courses.filter(c => c.completed_at).length;
    const inProgress = total - completed;

    return { total, completed, inProgress };
});
</script>

<template>
    <Head title="Mine kurser"/>

    <AppLayout>
        <div class="mt-10 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg p-8 text-white">
            <h1 class="text-3xl font-bold mb-4">Mine kurser</h1>
            <div class="grid grid-cols-3 gap-4 mt-6">
                <div class="bg-white/10 rounded-lg p-4">
                    <p class="text-sm opacity-80">Samlet antal kurser</p>
                    <p class="text-2xl font-bold">{{ courseStats.total }}</p>
                </div>
                <div class="bg-white/10 rounded-lg p-4">
                    <p class="text-sm opacity-80">Gennemført</p>
                    <p class="text-2xl font-bold">{{ courseStats.completed }}</p>
                </div>
                <div class="bg-white/10 rounded-lg p-4">
                    <p class="text-sm opacity-80">I gang</p>
                    <p class="text-2xl font-bold">{{ courseStats.inProgress }}</p>
                </div>
            </div>
        </div>

        <div class="my-8 flex gap-4">
            <input
                type="text"
                placeholder="Søg i dine kurser"
                class="w-1/4 py-2 px-4 border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200"
                v-model="search"
            >

            <select
                v-model="filterStatus"
                class="py-2 px-4 border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200"
            >
                <option value="all">Alle kurser</option>
                <option value="in-progress">I gang</option>
                <option value="completed">Gennemført</option>
            </select>
        </div>

        <div v-if="filteredCourses.length === 0" class="text-center py-12">
            <p class="text-gray-500 dark:text-gray-400">
                Ingen kurser matcher dine søgekriterier
            </p>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <template v-for="course in filteredCourses" :key="course.id">
                <CourseItem
                    :show-badge="false"
                    :can-buy="false"
                    :course="course"
                    class="relative"
                >
                    <div
                        v-if="course.completed_at"
                        class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded-md text-sm"
                    >
                        Gennemført
                    </div>
                </CourseItem>
            </template>
        </div>
    </AppLayout>
</template>
