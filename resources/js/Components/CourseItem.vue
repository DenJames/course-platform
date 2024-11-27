<script setup>
import {ref} from 'vue';
import {useCartStore} from "@/Stores/useCartStore.js";
import {Link, router} from "@inertiajs/vue3";

const props = defineProps({
    course: {
        type: Object,
        required: true,
    },
    showBadge: {
        type: Boolean,
        default: true,
    },
    canBuy: {
        type: Boolean,
        default: true,
    },
    showProgress: {
        type: Boolean,
        default: false,
    },
});

const cartStore = useCartStore();
const isOverlayVisible = ref(false);
const isAddingToCart = ref(false);


const toggleOverlay = () => {
    isOverlayVisible.value = !isOverlayVisible.value;
};

const getStarWidth = (starPosition) => {
    const rating = props.course.avarage_rating;
    return Math.max(0, Math.min(100, (rating - (starPosition - 1)) * 100));
};

const addToCartWithAnimation = () => {
    isAddingToCart.value = true;
    cartStore.addItem(props.course);
    setTimeout(() => {
        isAddingToCart.value = false;
    }, 1000);
};

const addToCartAndCheckout = () => {
    if (!cartStore.isInCart(props.course)) {
        cartStore.addItem(props.course);
    }
    router.visit(route('cart'));
};
</script>

<template>
    <div
        class="group relative w-full h-96 rounded-md bg-white dark:bg-gray-800 overflow-hidden cursor-pointer border border-gray-200 dark:border-gray-700"
        @click="toggleOverlay"
    >
        <!-- Course Base Content -->
        <div class="relative w-full h-full">
            <!-- Top rated badge -->
            <div class="absolute top-4 right-4 bg-purple-600 px-3 py-1 rounded-full text-white text-sm"
                 v-if="showBadge">
                Top rated
            </div>

            <!-- Background Image -->
            <img
                :src="course.image"
                :alt="course.title"
                class="w-full h-40 object-cover"
            >

            <!-- Base Content -->
            <div class="absolute left-0 right-0 p-3 h-full">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 truncate text-nowrap">{{ course.title }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">{{ course.author.name }}</p>

                <!-- Add the progress bar here -->
                <div v-if="showProgress" class="mt-24 mb-1">
                    <div class="flex items-center justify-between text-sm mb-1">
                        <span class="text-gray-600 dark:text-gray-400">Fremgang</span>
                        <span class="text-gray-900 dark:text-gray-200">{{
                                Math.round(course.course_progress)
                            }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                        <div
                            class="bg-green-500 h-1.5 rounded-full transition-all duration-300"
                            :style="{ width: `${course.course_progress ?? 0}%` }"
                        ></div>
                    </div>
                </div>

                <p class="text-gray-900 dark:text-white font-bold mt-2" v-if="canBuy">{{ course.price }} DKK</p>

                <div class="dark:text-gray-400 mt-2 text-sm font-bold italic" v-if="course.enrolled">
                    Enrolled
                </div>

                <!-- Cart Buttons -->
                <div class="mt-14 flex gap-2" v-if="canBuy">
                    <button
                        v-if="!cartStore.isInCart(course)"
                        class="flex-1 flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded-lg transition-all duration-300 relative overflow-hidden"
                        :class="{ 'animate-pulse': isAddingToCart }"
                        @click.stop="addToCartWithAnimation"
                    >
                        <span class="flex items-center gap-2" :class="{ 'opacity-0': isAddingToCart }">
                            <!-- Shopping Cart Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Tilføj til kurv
                        </span>
                        <!-- Checkmark Icon -->
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="size-5 absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 transition-opacity duration-300"
                            :class="{ 'opacity-100': isAddingToCart, 'opacity-0': !isAddingToCart }"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </button>

                    <button
                        v-else
                        class="flex-1 flex items-center justify-center gap-2 bg-green-50 text-green-600 hover:bg-green-100 dark:bg-green-900/20 dark:text-green-400 dark:hover:bg-green-900/30 font-bold py-3 px-4 rounded-lg transition-all duration-300"
                        @click.stop="cartStore.removeItem(course)"
                    >
                        <!-- Checkmark Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        I kurv
                    </button>

                    <button
                        @click.stop="addToCartAndCheckout"
                        class="flex items-center justify-center bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold p-3 rounded-lg transition-colors duration-200"
                        :title="cartStore.isInCart(course) ? 'Gå til kurv' : 'Tilføj og gå til kurv'"
                    >
                        <!-- Shopping Bag Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
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
            <!-- Close Icon -->
            <button
                class="absolute top-2 right-2 text-gray-600 dark:text-gray-400"
                @click.stop="isOverlayVisible = false"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="p-6 h-full flex flex-col">
                <Link :href="route('courses.show', course)" title="Gå til kursus">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 truncate text-nowrap">{{ course.title }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">Udgivet af: {{ course.author.name }}</p>

                    <!-- Course Stats -->
                    <div class="flex items-center gap-2 mb-4">
                        <span class="text-gray-900 dark:text-white">{{
                                (props.course.avarage_rating).toFixed(1)
                            }}</span>
                        <div class="flex gap-1">
                            <div v-for="i in 5" :key="i" class="relative">
                                <!-- Background star (empty) -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300 dark:text-gray-600"
                                     viewBox="0 0 20 20" fill="currentColor">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <!-- Foreground star (filled) with clip path -->
                                <div class="absolute inset-0 overflow-hidden" :style="{ width: getStarWidth(i) + '%' }">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-500"
                                         viewBox="0 0 20 20" fill="currentColor">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <span class="text-gray-500 dark:text-gray-400 text-sm text-nowrap">({{ course.total_ratings }} anmeldelser)</span>
                    </div>

                    <strong class="text-sm dark:text-gray-200">Beskrivelse:</strong>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">{{  course.description.length > 60 ? course.description.substring(0, 60) + '...' :  course.description }}</p>

                    <strong class="text-sm mb-1 dark:text-gray-200">Indhold:</strong>
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" viewBox="0 0 20 20"
                                 fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm">{{ course.lessons_count }} lektioner</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" viewBox="0 0 20 20"
                                 fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm">Certifikat ved færdiggørelse</span>
                        </div>
                    </div>
                </Link>

                <!-- Overlay Cart Buttons -->
                <div class="mt-auto flex gap-2" v-if="canBuy">
                    <button
                        v-if="!cartStore.isInCart(course)"
                        class="flex-1 flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded-lg transition-all duration-300 relative overflow-hidden"
                        :class="{ 'animate-pulse': isAddingToCart }"
                        @click.stop="addToCartWithAnimation"
                    >
                       <span class="flex items-center gap-2">
                            <!-- Shopping Cart Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Tilføj til kurv
                        </span>
                    </button>

                    <button
                        v-else
                        class="flex-1 flex items-center justify-center gap-2 bg-green-50 text-green-600 hover:bg-green-100 dark:bg-green-900/20 dark:text-green-400 dark:hover:bg-green-900/30 font-bold py-3 px-4 rounded-lg transition-all duration-300"
                        @click.stop="cartStore.removeItem(course)"
                    >
                        <!-- Checkmark Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        I kurv
                    </button>

                    <button
                        @click.stop="addToCartAndCheckout"
                        class="flex items-center justify-center bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold p-3 rounded-lg transition-colors duration-200"
                        :title="cartStore.isInCart(course) ? 'Gå til kurv' : 'Tilføj og gå til kurv'"
                    >
                        <!-- Shopping Bag Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
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
