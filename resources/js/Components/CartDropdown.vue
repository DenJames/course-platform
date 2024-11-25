<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useCartStore } from '../Stores/useCartStore.js';
import CartIcon from "@/Components/Icons/CartIcon.vue";
import { Link } from '@inertiajs/vue3';

const showDropdown = ref(false);
const dropdownRef = ref(null);
const cartStore = useCartStore();

const handleClickOutside = (event) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
        showDropdown.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
    <div
        class="relative group"
        ref="dropdownRef"
    >
        <button class="relative">
            <CartIcon class="size-6 dark:text-gray-300"/>
            <div
                v-if="cartStore.totalItems > 0"
                class="absolute size-5 rounded-full bg-purple-600 -top-2 -right-2 flex items-center justify-center text-purple-50 text-xs"
            >
                {{ cartStore.totalItems }}
            </div>
        </button>

        <!-- Invisible bridge to prevent hover gap - fix to prevent closing when moving mouse into the cart -->
        <div class="absolute w-full h-2 top-full"></div>

        <!-- Dropdown -->
        <div
            class="absolute right-0 mt-2 w-72 bg-white dark:bg-gray-800 rounded-lg shadow-lg border dark:border-gray-700 z-50 invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-all duration-300"
        >
            <div class="p-4">
                <h3 class="text-lg font-semibold mb-4 dark:text-gray-200">Indkøbskurv</h3>

                <!-- Empty State -->
                <div v-if="cartStore.totalItems === 0" class="text-gray-500 dark:text-gray-400 text-center py-4">
                    Din kurv er tom
                </div>

                <!-- Cart Items -->
                <div v-else class="space-y-4 max-h-96 overflow-y-auto">
                    <div
                        v-for="item in cartStore.items"
                        :key="item.id"
                        class="flex items-center justify-between gap-4 pb-4 border-b dark:border-gray-700"
                    >
                        <div class="flex items-center gap-3">
                            <img
                                :src="item.image"
                                :alt="item.title"
                                class="w-12 h-12 object-cover rounded"
                            />
                            <div>
                                <h4 class="font-medium dark:text-gray-200">{{ item.title }}</h4>
                                <p class="text-gray-600 dark:text-gray-400">{{ item.price }} DKK</p>
                            </div>
                        </div>

                        <button
                            @click.stop="cartStore.removeItem(item)"
                            class="text-red-500 hover:text-red-700"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Cart Footer -->
                <div class="mt-4 space-y-4">
                    <div class="flex justify-between font-semibold dark:text-gray-200">
                        <span>Total:</span>
                        <span>{{ cartStore.totalPrice }} DKK</span>
                    </div>

                    <Link
                        :href="route('checkout')"
                        class="block w-full bg-purple-600 text-white text-center py-2 rounded-lg hover:bg-purple-700 transition-colors"
                    >
                        Til betaling
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
