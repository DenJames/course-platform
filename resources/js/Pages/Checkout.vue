<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import {useCartStore} from "@/Stores/useCartStore.js";
import {onMounted, ref, computed} from "vue";
import {usePage, router} from "@inertiajs/vue3";

const step = ref(1);
const cartStore = useCartStore();
const showOrderSummary = ref(false);
const orderId = ref(Math.random().toString(36).slice(2, 8).toUpperCase());
const errors = ref({});

const steps = [
    {number: 1, title: 'Information'},
    {number: 2, title: 'Betaling'}
];

const form = ref({
    personal: {
        name: '',
        email: '',
    },
    payment: {
        card: '',
        expiry: '',
        cvc: '',
    }
});

const currentStepFields = computed(() => {
    switch (step.value) {
        case 1:
            return [
                {
                    label: 'Navn',
                    key: 'name',
                    type: 'text',
                    group: 'personal',
                    placeholder: 'Dit fulde navn',
                    required: true
                },
                {
                    label: 'E-mail',
                    key: 'email',
                    type: 'email',
                    group: 'personal',
                    placeholder: 'din@email.dk',
                    required: true
                },
            ];
        case 2:
            return [
                {
                    label: 'Kortnummer',
                    key: 'card',
                    type: 'text',
                    group: 'payment',
                    placeholder: '1234 5678 9012 3456',
                    required: true
                },
                {
                    label: 'Udløbsdato',
                    key: 'expiry',
                    type: 'text',
                    group: 'payment',
                    placeholder: 'MM/YY',
                    required: true
                },
                {
                    label: 'CVC',
                    key: 'cvc',
                    type: 'text',
                    group: 'payment',
                    placeholder: '123',
                    required: true
                }
            ];
        default:
            return [];
    }
});

const validateField = (field, value) => {
    errors.value[field] = [];

    switch (field) {
        case 'personal.name':
            if (!value) errors.value[field] = ['Navn er påkrævet'];
            else if (value.length < 2) errors.value[field] = ['Navn skal være mindst 2 tegn'];
            break;

        case 'personal.email':
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!value) errors.value[field] = ['Email er påkrævet'];
            else if (!emailRegex.test(value)) errors.value[field] = ['Ugyldig email adresse'];
            break;

        case 'payment.card':
            const cardRegex = /^[0-9]{16}$/;
            if (!value) errors.value[field] = ['Kortnummer er påkrævet'];
            else if (!cardRegex.test(value.replace(/\s/g, ''))) errors.value[field] = ['Ugyldigt kortnummer'];
            break;

        case 'payment.expiry':
            const expiryRegex = /^(0[1-9]|1[0-2])\/([0-9]{2})$/;
            if (!value) errors.value[field] = ['Udløbsdato er påkrævet'];
            else if (!expiryRegex.test(value)) errors.value[field] = ['Ugyldig udløbsdato (MM/YY)'];
            break;

        case 'payment.cvc':
            const cvcRegex = /^[0-9]{3,4}$/;
            if (!value) errors.value[field] = ['CVC er påkrævet'];
            else if (!cvcRegex.test(value)) errors.value[field] = ['Ugyldig CVC'];
            break;
    }
};

const formattedDate = computed(() => {
    const date = new Date();
    return date.toLocaleDateString('da-DK', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
});

const hasErrors = computed(() => {
    return Object.keys(errors.value).some(key => errors.value[key]?.length > 0);
});

const isStepValid = computed(() => {
    const fields = currentStepFields.value;

    // Check if all required fields are filled
    const allRequiredFieldsFilled = fields.every(field => {
        if (!field.required) return true;
        const value = form.value[field.group][field.key];
        return value && value.trim() !== '';
    });

    // Check if there are no errors
    return allRequiredFieldsFilled && !hasErrors.value;
});

const validateCurrentStep = () => {
    const fields = currentStepFields.value;
    let isValid = true;

    fields.forEach(field => {
        const fullKey = `${field.group}.${field.key}`;
        const value = form.value[field.group][field.key];

        validateField(fullKey, value);
        if (errors.value[fullKey]?.length > 0) isValid = false;
    });

    return isValid;
};

const page = usePage();
const userExists = ref(false);

onMounted(() => {
    if (page.props.auth.user) {
        form.value.personal.name = page.props.auth.user.name;
        form.value.personal.email = page.props.auth.user.email;
        userExists.value = true;
    }
});

const handleSubmit = () => {
    if (!validateCurrentStep()) {
        return;
    }

    if (step.value < 2) {
        step.value++;
    } else {
        showOrderSummary.value = true;

        router.post(route('process.purchases'), {
            ...form.value.personal,
            items: cartStore.items,
        });
    }
};

const closeOrderSummary = () => {
    showOrderSummary.value = false;
    cartStore.clearCart();
    router.visit(route('welcome'));
};
</script>

<template>
    <Head title="Checkout"/>

    <AppLayout>
        <div class="max-w-5xl mx-auto px-4 py-8 md:py-16 dark:text-gray-200">
            <!-- Progress Bar -->
            <div class="w-full flex items-center justify-between relative">
                <div class="absolute top-1/2 left-0 w-full h-1 bg-gray-400">
                    <div
                        class="h-full bg-purple-600 transition-all duration-300"
                        :style="{ width: `${Math.min(((step - 1) / 1 * 100) + 50, 100)}%` }"
                    ></div>
                </div>

                <div
                    v-for="s in steps"
                    :key="s.number"
                    class="size-14 rounded-full flex items-center justify-center text-white text-lg z-10 transition-colors duration-300 dark:text-gray-200"
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
                                    <TextInput
                                        v-model="form[field.group][field.key]"
                                        :type="field.type"
                                        :placeholder="field.placeholder"
                                        class="w-full"
                                        :required="field.required"
                                        @input="validateField(`${field.group}.${field.key}`, form[field.group][field.key])"
                                    />
                                    <div v-if="errors[`${field.group}.${field.key}`]?.length"
                                         class="text-red-500 text-sm mt-1">
                                        {{ errors[`${field.group}.${field.key}`][0] }}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 space-y-3">
                                <button
                                    v-if="cartStore.totalItems > 0"
                                    type="submit"
                                    :disabled="!isStepValid"
                                    class="w-full py-3 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-purple-600"
                                >
                                    {{ step === 2 ? 'Gennemfør køb' : 'Fortsæt' }}
                                </button>

                                <button
                                    v-if="step > 1"
                                    @click.prevent="step--"
                                    class="w-full py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                                >
                                    Tilbage
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Cart Summary -->
                <div class="w-full lg:w-1/3">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                        <h2 class="text-2xl font-semibold mb-6 dark:text-gray-200">Dine kurser</h2>

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

                            <div class="pt-4 mt-4">
                                <div class="flex justify-between font-semibold dark:text-gray-200">
                                    <span>Total</span>
                                    <span>{{ cartStore.totalPrice }} kr.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary Modal -->
        <div v-if="showOrderSummary"
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 dark:text-gray-200">
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
                    <div>
                        <h3 class="font-medium mb-2">Kundeoplysninger</h3>
                        <p>{{ form.personal.name }}</p>
                        <p>{{ form.personal.email }}</p>
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
                    <div class="pt-4">
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

<style scoped>
/* Progress bar and modal transitions */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.modal-enter-active,
.modal-leave-active {
    transition: all 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
    transform: translateY(-20px);
}

/* Form validation styles */
.error-message {
    color: #ef4444;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.invalid-input {
    border-color: #ef4444 !important;
}

/* Progress bar animation */
@keyframes progress {
    from {
        width: 0;
    }
    to {
        width: 100%;
    }
}

.progress-animation {
    animation: progress 0.5s ease-out;
}
</style>
