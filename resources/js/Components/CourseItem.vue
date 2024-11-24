<script setup>
import { ref } from 'vue';

const props = defineProps({
    course: {
        type: Object,
        required: true,
    }
});

const isOverlayVisible = ref(false);

const toggleOverlay = () => {
    isOverlayVisible.value = !isOverlayVisible.value;
};

const getStarWidth = (starPosition) => {
    // Convert 0-100 rating to 0-5 scale
    const rating = props.course.rating / 20;

    // Calculate how filled this particular star should be
    return Math.max(0, Math.min(100, (rating - (starPosition - 1)) * 100));
};
</script>

<template>
    <div
        class="group relative w-72 h-96 rounded-md bg-white dark:bg-gray-800 overflow-hidden cursor-pointer border border-gray-200 dark:border-gray-700"
        @click="toggleOverlay"
    >
        <!-- Course Base Content -->
        <div class="relative w-full h-full">
            <!-- Top rated badge -->
            <div class="absolute top-4 right-4 bg-purple-600 px-3 py-1 rounded-full text-white text-sm">
                Top rated
            </div>

            <!-- Background Image -->
            <img
                :src="course.imageUrl"
                :alt="course.imageAlt"
                class="w-full h-40"
            >

            <!-- Base Content -->
            <div class="absolute left-0 right-0 p-3 h-full">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ course.title }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">John doe</p>
                <p class="text-gray-900 dark:text-white font-bold mt-2">100 DKK</p>

                <div class="dark:text-gray-400 mt-2 text-sm font-bold italic" v-if="course.enrolled">
                    Enrolled
                </div>

                <div class="mt-14 block" :class="{ 'hidden' : course.enrolled }">
                    <button
                        class="w-full bg-purple-600 hover:bg-purple-700 dark:bg-purple-600 dark:hover:bg-purple-700 text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200"
                        @click.stop
                    >
                        Tilføj til kurv
                    </button>
                </div>
            </div>

        </div>

        <!-- Sliding Overlay -->
        <div
            class="absolute inset-0 bg-white dark:bg-gray-800 transition-transform duration-300 ease-in-out border-t border-gray-200 dark:border-gray-700"
            :class="{
                'translate-y-full': !isOverlayVisible,
                'translate-y-0': isOverlayVisible,
                'group-hover:translate-y-0': true
            }"
        >
            <!-- close icon -->
            <button
                class="absolute top-2 right-2 text-gray-600 dark:text-gray-400"
                @click.stop="isOverlayVisible = false"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="p-6 h-full flex flex-col">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ course.title }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">Udgivet af: John doe</p>

                    <!-- Course Stats -->
                    <div class="flex items-center gap-2 mb-4">
                        <span class="text-gray-900 dark:text-white">{{ (course.rating / 20).toFixed(1) }}</span>
                        <div class="flex gap-1">
                            <div v-for="i in 5" :key="i" class="relative">
                                <!-- Background star (empty) -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300 dark:text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <!-- Foreground star (filled) with clip path -->
                                <div class="absolute inset-0 overflow-hidden" :style="{ width: getStarWidth(i) + '%' }">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <span class="text-gray-500 dark:text-gray-400 text-sm text-nowrap">(123 anmeldelser)</span>
                    </div>

                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">{{ course.description }}</p>

                    <div class="space-y-2 mb-4">
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" />
                            </svg>
                            <span class="text-sm">{{ course.lessonCount }} lektioner</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm">Certifikat ved færdiggørelse</span>
                        </div>
                    </div>
                </div>

                <div class="mt-auto" v-if="!course.enrolled">
                    <button
                        class="w-full bg-purple-600 hover:bg-purple-700 dark:bg-purple-600 dark:hover:bg-purple-700 text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200"
                        @click.stop
                    >
                        Tilføj til kurv
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
@media (hover: hover) {
    .group:hover .translate-y-full {
        transform: translateY(0);
    }
}

.group {
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
}

.group:hover {
    box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
}

@media (prefers-color-scheme: dark) {
    .group {
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.2), 0 2px 4px -2px rgb(0 0 0 / 0.2);
    }

    .group:hover {
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.2), 0 4px 6px -4px rgb(0 0 0 / 0.2);
    }
}
</style>
