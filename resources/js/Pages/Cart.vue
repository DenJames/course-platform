<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useCartStore } from "@/Stores/useCartStore.js";
import { computed } from "vue";
import { router } from "@inertiajs/vue3";

const cartStore = useCartStore();

const isEmpty = computed(() => cartStore.items.length === 0);


const proceedToCheckout = () => {
    router.visit(route('checkout'));
};

const continueShopping = () => {
    router.visit(route('welcome'));
};
</script>

<template>
    <Head title="Indkøbskurv" />

    <AppLayout>
        <div class="max-w-5xl mx-auto px-4 py-8 md:py-16">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold dark:text-gray-200">Indkøbskurv</h1>
                <span class="text-gray-600 dark:text-gray-400">{{ cartStore.totalItems }} kurser</span>
            </div>

            <!-- Empty Cart State -->
            <div v-if="isEmpty" class="text-center py-16">
                <div class="mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-semibold mb-4 dark:text-gray-200">Din indkøbskurv er tom</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-8">Udforsk vores kurser og find noget der passer til dig!</p>
                <button
                    @click="continueShopping"
                    class="inline-flex items-center px-6 py-3 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 transition-colors"
                >
                    Fortsæt med at handle
                </button>
            </div>

            <!-- Cart Items -->
            <div v-else class="flex flex-col lg:flex-row gap-8">
                <div class="w-full lg:w-2/3">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                        <div class="space-y-6">
                            <div
                                v-for="item in cartStore.items"
                                :key="item.id"
                                class="flex items-start gap-4 pb-6 border-b dark:border-gray-700 last:border-0 last:pb-0"
                            >
                                <img
                                    :src="item.image"
                                    :alt="item.title"
                                    class="w-24 h-24 object-cover rounded-lg"
                                />

                                <div class="flex-1">
                                    <div class="flex justify-between">
                                        <h3 class="font-medium text-lg dark:text-gray-200">{{ item.title }}</h3>
                                        <p class="font-semibold dark:text-gray-200">{{ item.price }} kr.</p>
                                    </div>

                                    <p class="text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">
                                        {{ item.description }}
                                    </p>

                                    <div class="flex items-center justify-between mt-4">
                                        <div class="flex items-center gap-2">
                                            <button
                                                @click="cartStore.removeItem(item)"
                                                class="text-red-500 hover:text-red-600 transition-colors p-1"
                                                title="Fjern fra kurv"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button
                        @click="continueShopping"
                        class="mt-6 text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors flex items-center gap-2"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Fortsæt med at handle
                    </button>
                </div>

                <!-- Order Summary -->
                <div class="w-full lg:w-1/3">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 sticky top-6">
                        <h2 class="text-xl font-semibold mb-6 dark:text-gray-200">Ordreoversigt</h2>

                        <div class="space-y-4">
                            <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                <span>Antal kurser</span>
                                <span>{{ cartStore.totalItems }}</span>
                            </div>

                            <div class="pt-4 border-t dark:border-gray-700">
                                <div class="flex justify-between font-semibold text-lg dark:text-gray-200">
                                    <span>Total</span>
                                    <span>{{ cartStore.totalPrice }} kr.</span>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                    Inkl. moms og gebyrer
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 space-y-3">
                            <button
                                @click="proceedToCheckout"
                                class="w-full py-3 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 transition-colors"
                            >
                                Gå til betaling
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
