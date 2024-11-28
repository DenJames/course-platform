<script setup>
import { Head, Link } from '@inertiajs/vue3';
import {ref, onMounted, onUnmounted, computed} from 'vue';
import AppLayout from "@/Layouts/AppLayout.vue";
import StarIcon from "@/Components/Icons/StarIcon.vue";
import CourseItem from "@/Components/CourseItem.vue";
import ChevronLeftIcon from "@/Components/Icons/ChevronLeftIcon.vue";
import ChevronRightIcon from "@/Components/Icons/ChevronRightIcon.vue";

const props = defineProps({
    courses: {
        type: Array,
        required: true,
    },
});

const search = ref('');

const filteredCourses = computed(() => {
    return props.courses.filter(course => {
        return course.title.toLowerCase().includes(search.value.toLowerCase());
    });
});
</script>

<template>
    <Head title="Kurser"/>

    <AppLayout>
        <div class="mt-10 hidden md:block relative">
            <img src="https://img-c.udemycdn.com/notices/featured_carousel_slide/image/34c63aef-8d1f-483e-b0ea-0ead94879e56.jpg" alt=""
                 class="w-full h-64 bg-contain rounded-md">

            <div class="absolute top-5 left-5 rounded-md bg-white p-4 shadow-md w-2/5 z-30">
                <h2 class="text-2xl font-bold">
                    Tag din karriere til næste niveau i dag!
                </h2>

                <p>
                    Tag din karriere til næste niveau med onlinekurser. Tag din karriere til næste niveau med onlinekurser. Tag din karriere til næste niveau med onlinekurser.
                </p>
            </div>

            <div class="bg-black/20 w-full h-full absolute top-0 left-0 z-20"></div>
        </div>

        <div class="my-5">
            <input
                type="text"
                placeholder="Søg efter kursus"
                class="w-full md:w-1/4 py-2 px-4 border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200"
                v-model="search"
            >
        </div>
        <!-- Your Courses Section -->
        <div class="w-full relative grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <template v-for="(course, index) in filteredCourses" :key="index">
                <CourseItem :course="course" class="w-full" :show-badge="false" />
            </template>
        </div>
    </AppLayout>
</template>

<style scoped>
.carousel-container {
    -ms-overflow-style: none;
    scrollbar-width: none;
    scroll-behavior: smooth;
    transition: all 0.5s ease-in-out;
    cursor: grab;
}

.carousel-container::-webkit-scrollbar {
    display: none;
}

.carousel-container:active {
    cursor: grabbing;
}

button {
    transition: all 0.2s ease-in-out;
}

button:disabled {
    cursor: not-allowed;
}
</style>
