<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { useCartStore } from "@/Stores/useCartStore.js";
import { onMounted, ref, computed } from "vue";
import { usePage, router } from "@inertiajs/vue3";

const step = ref(1);
const cartStore = useCartStore();
const showOrderSummary = ref(false);
const orderId = ref(Math.random().toString(36).slice(2, 8).toUpperCase());

const steps = [
    { number: 1, title: 'Information' },
    { number: 2, title: 'Platform' },
    { number: 3, title: 'Betaling' }
];

const form = ref({
    personal: {
        name: '',
        email: '',
        phone: '',
    },
    platform: {
        github: '',
        preferred_platform: '',
        notifications: '',
    },
    payment: {
        card: '',
        expiry: '',
        cvc: '',
    }
});

const platformOptions = [
    {value: 'email', label: 'Email'},
    {value: 'web', label: 'Web Platform'}
];

const notificationOptions = [
    {value: 'daily', label: 'Dagligt'},
    {value: 'weekly', label: 'Ugentligt'},
    {value: 'monthly', label: 'Månedligt'},
    {value: 'none', label: 'Ingen'}
];

const currentStepFields = computed(() => {
    switch (step.value) {
        case 1:
            return [
                {label: 'Navn', key: 'name', type: 'text', group: 'personal'},
                {label: 'E-mail', key: 'email', type: 'email', group: 'personal'},
                {label: 'Telefon', key: 'phone', type: 'tel', group: 'personal'}
            ];
        case 2:
            return [
                {
                    label: 'Github Brugernavn',
                    key: 'github',
                    type: 'text',
                    group: 'platform',
                    placeholder: 'fx: user#1234'
                },
                {
                    label: 'Foretrukken leverings Platform',
                    key: 'preferred_platform',
                    type: 'select',
                    group: 'platform',
                    options: platformOptions
                },
            ];
        case 3:
            return [
                {label: 'Kortnummer', key: 'card', type: 'text', group: 'payment'},
                {label: 'Udløbsdato', key: 'expiry', type: 'text', group: 'payment'},
                {label: 'CVC', key: 'cvc', type: 'text', group: 'payment'}
            ];
        default:
            return [];
    }
});

const formattedDate = computed(() => {
    const date = new Date();
    return date.toLocaleDateString('da-DK', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
});

const page = usePage();
const userExists = ref(false);

onMounted(() => {
    if (page.props.auth.user) {
        form.value.personal.name = page.props.auth.user.name;
        form.value.personal.email = page.props.auth.user.email;
    }
});

const handleSubmit = () => {
    if (step.value === 2) {
        axios.post(route('check.user.existence'), form.value.personal)
            .then(response => {
                console.log(response.data);
                userExists.value = response.data.exists;
            });
    }

    if (step.value < 3) {
        step.value++;
    } else {
        showOrderSummary.value = true;

        router.post(route('user.store'), form.value.personal);
    }
};

const closeOrderSummary = () => {
    showOrderSummary.value = false;
    cartStore.clearCart();
    router.visit(route('dashboard'));
};

onMounted(() => {
    if (page.props.auth.user) {
        userExists.value = true;
    }
});
</script>

<template>
    <Head title="Checkout"/>

    <AppLayout>
        <div class="max-w-5xl mx-auto px-4 py-8 md:py-16">
            <div class="w-full flex items-center justify-between relative">
                <div class="absolute top-1/2 left-0 w-full h-1 bg-gray-400">
                    <div
                        class="h-full bg-purple-600 transition-all duration-300"
                        :style="{ width: `${Math.min(((step - 1) / 2 * 100) + 25, 100)}%` }"
                    ></div>
                </div>

                <div
                    v-for="s in steps"
                    :key="s.number"
                    class="size-14 rounded-full flex items-center justify-center text-white text-lg z-10 transition-colors duration-300"
                    :class="[step >= s.number ? 'bg-purple-600' : 'bg-gray-400']"
                >
                    {{ s.number }}
                    <span class="absolute -bottom-6 text-sm"
                          :class="step >= s.number ? 'text-purple-600' : 'text-gray-500'">
                        {{ s.title }}
                    </span>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-8 mt-14">
                <div class="w-full lg:w-2/3">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                        <h2 class="text-2xl font-semibold mb-6">
                            {{ steps[step - 1].title }}
                        </h2>

                        <form @submit.prevent="handleSubmit" class="space-y-6">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div
                                    v-for="field in currentStepFields"
                                    :key="field.key"
                                    class="md:col-span-2"
                                >
                                    <InputLabel :value="field.label" class="mb-1"/>
                                    <template v-if="field.type === 'select'">
                                        <select
                                            v-model="form[field.group][field.key]"
                                            class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                            required
                                        >
                                            <option value="" disabled selected>Vælg en mulighed</option>
                                            <option
                                                v-for="option in field.options"
                                                :key="option.value"
                                                :value="option.value"
                                            >
                                                {{ option.label }}
                                            </option>
                                        </select>
                                    </template>
                                    <TextInput
                                        v-else
                                        v-model="form[field.group][field.key]"
                                        :type="field.type"
                                        :placeholder="field.placeholder"
                                        class="w-full"
                                        required
                                    />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="w-full lg:w-1/3">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                        <h2 class="text-2xl font-semibold mb-6">Dine kurser</h2>

                        <div class="space-y-4">
                            <div v-for="item in cartStore.items" :key="item.id"
                                 class="flex items-center gap-4 pb-4 border-b dark:border-gray-700">
                                <img :src="item.image" :alt="item.title"
                                     class="w-16 h-16 object-cover rounded"/>
                                <div class="flex-1">
                                    <h3 class="font-medium dark:text-gray-200">{{ item.title }}</h3>
                                    <p class="text-gray-600 dark:text-gray-400">{{ item.price }} kr.</p>
                                </div>
                            </div>

                            <div class="border-t pt-4 mt-4">
                                <div class="flex justify-between font-semibold">
                                    <span>Total</span>
                                    <span>{{ cartStore.totalPrice }} kr.</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 space-y-3">
                            <button
                                v-if="cartStore.totalItems > 0"
                                @click="handleSubmit"
                                class="w-full py-3 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 transition-colors"
                            >
                                {{ step === 3 ? 'Gennemfør køb' : 'Fortsæt' }}
                            </button>

                            <button
                                v-if="step > 1"
                                @click="step--"
                                class="w-full py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                            >
                                Tilbage
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary Modal -->
        <div v-if="showOrderSummary"
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold">Ordrebekræftelse #{{ orderId }}</h2>
                        <p class="text-gray-600 dark:text-gray-400">{{ formattedDate }}</p>
                    </div>

                    <button @click="closeOrderSummary"
                            class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="my-4 rounded-md bg-green-400 text-green-50 py-2 px-4 text-center w-full" v-if="!userExists">
                    Vi har sendt en oprettelses mail til dig.
                </div>

                <div class="space-y-6">
                    <!-- Customer Info -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="font-medium mb-2">Kundeoplysninger</h3>
                            <p>{{ form.personal.name }}</p>
                            <p>{{ form.personal.email }}</p>
                            <p>{{ form.personal.phone }}</p>
                        </div>
                        <div>
                            <h3 class="font-medium mb-2">Platform Indstillinger</h3>
                            <p>Github: {{ form.platform.github }}</p>
                            <p>Platform:
                                {{ platformOptions.find(o => o.value === form.platform.preferred_platform)?.label }}</p>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div>
                        <h3 class="font-medium mb-4">Dine kurser</h3>
                        <div class="space-y-4">
                            <div v-for="item in cartStore.items" :key="item.id"
                                 class="flex items-center gap-4 pb-4 border-b dark:border-gray-700">
                                <img :src="item.image" :alt="item.title"
                                     class="w-16 h-16 object-cover rounded"/>
                                <div class="flex-1">
                                    <h4 class="font-medium dark:text-gray-200">{{ item.title }}</h4>
                                    <p class="text-gray-600 dark:text-gray-400">{{ item.price }} kr.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="border-t pt-4">
                        <div class="flex justify-between font-semibold text-lg">
                            <span>Total</span>
                            <span>{{ cartStore.totalPrice }} kr.</span>
                        </div>
                    </div>

                    <button @click="closeOrderSummary"
                            class="w-full py-3 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 transition-colors">
                        Luk og fortsæt
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
