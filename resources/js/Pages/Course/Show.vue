<script setup>
import { Head, router } from '@inertiajs/vue3';
import { ref, computed, onBeforeUnmount } from 'vue';
import AppLayout from "@/Layouts/AppLayout.vue";
import {useCartStore} from "@/Stores/useCartStore.js";

const props = defineProps({
    course: {
        type: Object,
        required: true
    },
    lessons: {
        type: Array,
        required: true
    },
    isEnrolled: {
        type: Boolean,
        required: true
    }
});

const activeLessonId = ref(null);
const videoEndTimer = ref(null);
const isAddingToCart = ref(false);
const cartStore = useCartStore();

const totalDuration = computed(() => {
    return props.lessons.reduce((acc, lesson) => acc + lesson.duration, 0);
});

const formatDuration = (duration) => {
    const hours = Math.floor(duration / 60);
    const minutes = duration % 60;
    if (hours === 0) return `${minutes}m`;
    return `${hours}t ${minutes}m`;
};

const completedLessons = computed(() => {
    return props.lessons.filter(lesson => lesson.completed).length;
});

const progress = computed(() => {
    if (props.lessons.length === 0) return 0;
    return (completedLessons.value / props.lessons.length) * 100;
});

const handleVideoEnded = (lessonId) => {
    // Clear any existing timer
    if (videoEndTimer.value) {
        clearTimeout(videoEndTimer.value);
    }

    if (!props.lessons.find(lesson => lesson.id === lessonId)?.completed) {
        toggleLessonCompletion(lessonId);
    }
};

const toggleLessonCompletion = (lessonId) => {
    router.post(route('lessons.toggle-completion', lessonId), {}, {
        preserveScroll: true
    });
};

const resetProgress = () => {
    if (confirm('Er du sikker på, at du vil nulstille alt kursus fremgang?')) {
        router.post(route('courses.reset-progress', props.course.id), {}, {
            preserveScroll: true
        });
    }
};

const markCourseComplete = () => {
    router.post(route('courses.mark-complete', props.course.id), {}, {
        preserveScroll: true
    });
};

const addToCart = () => {
    isAddingToCart.value = true;
    cartStore.addItem(props.course);
};

// Clean up timer when component is unmounted
onBeforeUnmount(() => {
    if (videoEndTimer.value) {
        clearTimeout(videoEndTimer.value);
    }
});
</script>

<template>
    <Head :title="course.title"/>

    <AppLayout>
        <div class="max-w-7xl mx-auto">
            <!-- Course Header -->
            <div class="flex flex-col md:flex-row gap-4 h-[500px]">
                <div class="flex flex-col gap-4 w-full md:w-1/3 h-full">
                    <div class="w-full py-2 px-4 rounded-lg bg-purple-600 mt-10 flex items-center justify-between text-sm ">
                        <span class="text-purple-300 font-bold">Din instruktør</span>

                        <span class="text-white font-bold text-nowrap">
                            {{ course.author.name }}
                        </span>
                    </div>

                    <div class="h-[375px] w-full">
                        <img class="h-full bg-cover w-full rounded-lg" src="https://images.pexels.com/photos/774909/pexels-photo-774909.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2" alt="">
                    </div>
                </div>

                <div class="mt-10 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl p-8 mb-8 w-2/3">
                    <h1 class="text-3xl font-bold mb-4">{{ course.title }}</h1>
                    <p class="opacity-90 mb-6 text-sm">{{ course.description }}</p>

                    <div class="flex items-center gap-6">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <span>{{ formatDuration(totalDuration) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 0 1 0 .656l-5.603 3.113a.375.375 0 0 1-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112Z" />
                            </svg>
                            <span>{{ lessons.length }} lektioner</span>
                        </div>

                        <button class="flex items-center gap-2 hover:underline">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 0 1 0 .656l-5.603 3.113a.375.375 0 0 1-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112Z" />
                            </svg>
                            <span>Intro video</span>
                        </button>
                    </div>

                    <div v-if="!isEnrolled" class="mt-4 flex gap-2 items-center">

                        <button
                            v-if="!cartStore.isInCart(course)"
                            @click="addToCart"
                            class="inline-flex items-center gap-2 bg-white text-purple-600 hover:bg-white/90 transition-colors duration-200 rounded-lg px-6 py-3 font-medium"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                            </svg>
                            Tilføj til kurv
                        </button>

                        <button
                            v-else
                            class="inline-flex items-center justify-center gap-2 bg-white/20 text-white hover:bg-green-900/30 font-bold py-3 px-4 rounded-lg transition-all duration-300"
                            @click.stop.prevent="cartStore.removeItem(course)"
                        >
                            Fjern fra kurv
                        </button>

                        <p class="font-bold text-sm">
                            Pris: {{ course.price }} DKK
                        </p>
                    </div>

                    <div v-if="isEnrolled" class="mt-6">
                        <div class="bg-white/10 rounded-lg p-4">
                            <div class="flex justify-between mb-2">
                                <span>Fremgang</span>
                                <span>{{ Math.round(progress) }}%</span>
                            </div>
                            <div class="w-full bg-white/20 rounded-full h-2">
                                <div
                                    class="bg-green-500 h-2 rounded-full transition-all duration-300"
                                    :style="{ width: `${progress}%` }"
                                ></div>
                            </div>
                        </div>
                        <div class="mt-4 flex flex-col md:flex-row gap-2">
                            <button
                                @click="resetProgress"
                                class="flex items-center gap-2 bg-white/10 hover:bg-white/20 transition-colors duration-200 rounded-lg px-4 py-2"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                                Nulstil fremgang
                            </button>
                            <button
                                @click="markCourseComplete"
                                class="flex items-center gap-2 bg-white/10 hover:bg-white/20 transition-colors duration-200 rounded-lg px-4 py-2"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                Marker kursus som færdig
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Content -->
            <div class="bg-white dark:bg-gray-800 dark:text-gray-200 rounded-xl shadow-sm mb-6">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-4">Kursusindhold</h2>

                    <div class="space-y-4 max-h-[500px] overflow-y-auto">
                        <div
                            v-for="lesson in lessons"
                            :key="lesson.id"
                            class="border dark:border-gray-700 rounded-lg hover:border-purple-500 transition-colors duration-200"
                            :class="{'border-purple-500': activeLessonId === lesson.id}"
                        >
                            <div class="p-4 flex items-center justify-between">
                                <button
                                    @click="activeLessonId = activeLessonId === lesson.id ? null : lesson.id"
                                    class="flex items-center gap-4"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-gray-600 dark:text-gray-400">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 0 1 0 .656l-5.603 3.113a.375.375 0 0 1-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112Z" />
                                    </svg>

                                    <div class="text-left">
                                        <h3 class="font-medium">{{ lesson.title }}</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ formatDuration(lesson.duration) }}
                                        </p>
                                    </div>
                                </button>

                                <button
                                    v-if="isEnrolled"
                                    @click="toggleLessonCompletion(lesson.id)"
                                    class="flex items-center justify-center w-8 h-8 rounded-full transition-colors duration-200"
                                    :class="lesson.completed ? 'bg-green-500 hover:bg-green-600' : 'bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600'"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5" :class="lesson.completed ? 'text-white' : 'text-gray-600 dark:text-gray-400'">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>

                                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                            </div>

                            <div
                                v-if="activeLessonId === lesson.id && isEnrolled"
                                class="p-4 border-t dark:border-gray-700"
                            >
                                <p class="text-gray-600 dark:text-gray-400">
                                    {{ lesson.description }}
                                </p>
                                <div class="mt-4" v-if="lesson.video_url">
                                    <video
                                        v-if="lesson.video_url"
                                        src="https://videos.pexels.com/video-files/27362710/12125786_2560_1440_25fps.mp4"
                                        class="w-full rounded-lg"
                                        controls
                                        @ended="handleVideoEnded(lesson.id)"
                                    ></video>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
