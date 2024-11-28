```vue
<script setup>
import {Head, useForm, router, usePage} from '@inertiajs/vue3';
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
    },
});

const activeLessonId = ref(null);
const videoEndTimer = ref(null);
const isAddingToCart = ref(false);
const cartStore = useCartStore();
const showVideoModal = ref(false);
const isEditingReview = ref(false);

const form = useForm({
    rating: 0,
    content: ''
});

const totalDuration = computed(() => {
    return props.lessons.reduce((acc, lesson) => acc + lesson.duration, 0);
});

const completedLessons = computed(() => {
    return props.lessons.filter(lesson => lesson.completed).length;
});

const progress = computed(() => {
    if (props.lessons.length === 0) return 0;
    return (completedLessons.value / props.lessons.length) * 100;
});

const hasLeftReview = computed(() => {
    return props.course.reviews?.find(review => review.user_id === usePage().props.auth.user?.id);
});

const formatDuration = (duration) => {
    const hours = Math.floor(duration / 60);
    const minutes = duration % 60;
    if (hours === 0) return `${minutes}m`;
    return `${hours}t ${minutes}m`;
};

const handleVideoEnded = (lessonId) => {
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

const getRatingClass = (rating, currentIndex) => {
    if (rating >= currentIndex) return 'text-yellow-500';
    if (rating >= currentIndex - 0.5) return 'text-yellow-500 opacity-50';
    return 'text-gray-300';
};

const submitReview = () => {
    const url = isEditingReview.value
        ? route('reviews.update', props.course)
        : route('reviews.store', props.course);

    form.post(url, {
        preserveScroll: true,
        onSuccess: () => {
            isEditingReview.value = false;
            form.reset();
        }
    });
};

const editReview = (review) => {
    form.rating = review.rating;
    form.content = review.content;
    isEditingReview.value = true;
};

const deleteReview = () => {
    if (confirm('Er du sikker på, at du vil slette din anmeldelse?')) {
        router.delete(route('reviews.destroy', props.course.id), {
            preserveScroll: true,
            onSuccess: () => {
                isEditingReview.value = false;
                form.reset();
            }
        });
    }
};

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
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex flex-col gap-4 w-full md:w-1/3 h-full">
                    <div class="w-full py-2 px-4 rounded-lg bg-gradient-to-r from-purple-600 to-indigo-600 mt-10 flex items-center justify-between text-sm">
                        <span class="text-purple-300 font-bold">Din instruktør</span>
                        <span class="text-white font-bold text-nowrap">{{ course.author.name }}</span>
                    </div>

                    <div class="h-[275px] w-full">
                        <img class="h-full w-full rounded-lg object-cover object-center"
                             src="https://images.pexels.com/photos/774909/pexels-photo-774909.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2"
                             alt=""
                        >
                    </div>
                </div>

                <div class="mt-10 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl p-8 mb-8 w-2/3">
                    <h1 class="text-3xl font-bold mb-4">{{ course.title }}</h1>

                    <!-- Course Rating -->
                    <div class="flex items-center gap-2 mb-4 text-gray-200">
                        <span class="text-lg font-bold">{{ (course.avarage_rating).toFixed(1) }}</span>
                        <div class="flex gap-1">
                            <svg
                                v-for="index in 5"
                                :key="index"
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                :class="getRatingClass(course.avarage_rating, index)"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                        <span class="text-sm">
                            ({{ course.total_ratings }} {{ course.total_ratings === 1 ? 'anmeldelse' : 'anmeldelser' }})
                        </span>
                    </div>

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

                        <button @click="showVideoModal = true" class="flex items-center gap-2 hover:underline">
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
                                <div class="mt-4 flex justify-center" v-if="lesson.video_url">
                                    <video
                                        v-if="lesson.video_url"
                                        src="https://videos.pexels.com/video-files/27362710/12125786_2560_1440_25fps.mp4"
                                        class="h-80 rounded-lg"
                                        controls
                                        @ended="handleVideoEnded(lesson.id)"
                                    ></video>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="bg-white dark:bg-gray-800 dark:text-gray-200 rounded-xl shadow-sm mb-6">
                <div class="p-6">
                    <div v-if="!isEnrolled" class="text-gray-600 dark:text-gray-400 mb-6">
                        Du skal være tilmeldt kurset for at kunne anmelde det.
                    </div>

                    <!-- Review Form -->
                    <div v-if="isEnrolled && (!hasLeftReview || isEditingReview)" class="mb-6 border dark:border-gray-700 rounded-lg p-4">
                        <form @submit.prevent="submitReview">
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Din bedømmelse</label>
                                <div class="flex gap-1">
                                    <button
                                        v-for="star in 5"
                                        :key="star"
                                        type="button"
                                        @click="form.rating = star"
                                        class="text-gray-300 hover:text-yellow-500 transition-colors"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-8 w-8"
                                            :class="{ 'text-yellow-500': form.rating >= star }"
                                            fill="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    </button>
                                </div>
                                <p v-if="form.errors.rating" class="mt-1 text-sm text-red-500">
                                    {{ form.errors.rating }}
                                </p>
                            </div>

                            <div class="mb-4">
                                <label for="review-content" class="block text-sm font-medium mb-2">Din anmeldelse</label>
                                <textarea
                                    id="review-content"
                                    v-model="form.content"
                                    class="w-full rounded-lg border dark:border-gray-700 dark:bg-gray-900 p-3"
                                    :class="{ 'border-red-500': form.errors.content }"
                                    rows="4"
                                    placeholder="Fortæl andre, hvad du synes om kurset..."
                                />
                                <p v-if="form.errors.content" class="mt-1 text-sm text-red-500">
                                    {{ form.errors.content }}
                                </p>
                            </div>

                            <div class="flex justify-end gap-2">
                                <button
                                    v-if="isEditingReview"
                                    type="button"
                                    @click="deleteReview"
                                    :disabled="form.processing"
                                    class="bg-red-500 hover:bg-red-600 text-white rounded-lg px-4 py-2 disabled:opacity-50"
                                >
                                    Slet
                                </button>

                                <button
                                    type="button"
                                    @click="isEditingReview = false"
                                    :disabled="form.processing"
                                    class="bg-gray-500 hover:bg-gray-600 text-white rounded-lg px-4 py-2 disabled:opacity-50"
                                >
                                    Annuller
                                </button>

                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="bg-purple-600 hover:bg-purple-700 text-white rounded-lg px-6 py-2 disabled:opacity-50"
                                >
                                    {{ isEditingReview ? 'Opdater' : 'Send' }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Display Reviews -->
                    <div v-if="course.reviews?.length" class="space-y-4">
                        <div v-for="review in course.reviews" :key="review.id" class="border dark:border-gray-700 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="flex gap-1">
                                        <svg
                                            v-for="star in 5"
                                            :key="star"
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5"
                                            :class="star <= review.rating ? 'text-yellow-500' : 'text-gray-300'"
                                            fill="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    </div>
                                    <span class="font-medium">{{ review.user.name }}</span>
                                    <span class="text-sm text-gray-500">
                                       {{ new Date(review.created_at).toLocaleDateString('da-DK') }}
                                   </span>
                                </div>

                                <div v-if="review.user_id === $page.props.auth.user?.id" class="flex gap-2">
                                    <button
                                        @click="editReview(review)"
                                        class="text-purple-600 hover:text-purple-700"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </button>
                                    <button
                                        @click="deleteReview"
                                        class="text-red-500 hover:text-red-600"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300">{{ review.content }}</p>
                        </div>
                    </div>

                    <div v-else class="text-gray-500 text-center py-8">
                        Ingen anmeldelser endnu. Vær den første til at anmelde dette kursus!
                    </div>
                </div>
            </div>

            <!-- Video Modal -->
            <div v-if="showVideoModal"
                 class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-4xl">
                    <div class="p-4 flex justify-between items-center border-b dark:border-gray-700">
                        <h3 class="text-xl font-semibold dark:text-gray-200">Intro Video</h3>
                        <button @click="showVideoModal = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-4">
                        <video
                            src="https://videos.pexels.com/video-files/27362710/12125786_2560_1440_25fps.mp4"
                            class="w-full rounded-lg"
                            controls
                        ></video>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
