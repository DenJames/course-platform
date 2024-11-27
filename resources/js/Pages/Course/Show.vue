<script setup>
import { Head, router } from '@inertiajs/vue3';
import { ref, computed, onBeforeUnmount } from 'vue';
import AppLayout from "@/Layouts/AppLayout.vue";

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
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl p-8 mb-8">
                <h1 class="text-3xl font-bold mb-4">{{ course.title }}</h1>
                <p class="text-lg opacity-90 mb-6">{{ course.description }}</p>

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

                    <div class="mt-4 flex gap-2">
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
                            class="flex items-center gap-2 bg-green-500/20 hover:bg-green-500/30 transition-colors duration-200 rounded-lg px-4 py-2"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            Marker kursus som færdig
                        </button>
                    </div>
                </div>
            </div>

            <!-- Course Content -->
            <div class="bg-white dark:bg-gray-800 dark:text-gray-200 rounded-xl shadow-sm">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-4">Kursusindhold</h2>

                    <div class="space-y-4">
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
