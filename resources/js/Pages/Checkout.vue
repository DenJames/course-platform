<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import {onMounted, ref} from "vue";
import {usePage} from "@inertiajs/vue3";

const step = ref(1);

const form = ref({
    name: '',
    email: '',
    phone: '',
    address: '',
    city: '',
    zip: '',
    country: '',
    card: '',
    expiry: '',
    cvc: '',
});

const page = usePage();

onMounted(() => {
    if (page.props.auth.user) {
        form.value.name = page.props.auth.user.name;
        form.value.email = page.props.auth.user.email;
    }
});
</script>

<template>
    <Head title="Checkout"/>

    <AppLayout>
        <div class="w-full mt-4 md:mt-32 2xl:px-64">
            <div class="w-full flex items-center justify-between relative">
                <!-- Line connecting steps -->
                <div class="absolute top-1/2 left-0 w-full h-1 bg-gray-400">
                    <div
                        class="h-full bg-yellow-500 transition-all duration-300"
                        :style="{ width: `${Math.min(((step - 1) / 2 * 100) + 25, 100)}%` }"
                    ></div>
                </div>

                <!-- Step 1 -->
                <div
                    class="size-14 rounded-full flex items-center justify-center text-white text-lg z-10 transition-colors duration-300"
                    :class="[step >= 1 ? 'bg-yellow-500' : 'bg-gray-400']"
                >
                    1
                </div>

                <!-- Step 2 -->
                <div
                    class="size-14 rounded-full flex items-center justify-center text-white text-lg z-10 transition-colors duration-300"
                    :class="[step >= 2 ? 'bg-yellow-500' : 'bg-gray-400']"
                >
                    2
                </div>

                <!-- Step 3 -->
                <div
                    class="size-14 rounded-full flex items-center justify-center text-white text-lg z-10 transition-colors duration-300"
                    :class="[step >= 3 ? 'bg-yellow-500' : 'bg-gray-400']"
                >
                    3
                </div>
            </div>

            <div class="flex flex-col lg:flex-row justify-center gap-8 mt-12">
                <div class="w-full md:w-2/3 space-y-4" v-if="step === 1">
                    <div class="flex flex-col lg:flex-row gap-4">
                        <div class="flex flex-col gap-1 w-full md:w-1/2">
                            <InputLabel value="Navn"/>
                            <TextInput v-model="form.name" type="text" class="w-full"/>
                        </div>

                        <div class="flex flex-col gap-1 w-full md:w-1/2">
                            <InputLabel value="E-mail"/>
                            <TextInput v-model="form.email" type="email" class="w-full"/>
                        </div>
                    </div>

                    <div class="flex flex-col lg:flex-row gap-4">
                        <div class="flex flex-col gap-1 w-full md:w-1/2">
                            <InputLabel value="Mobil"/>
                            <TextInput v-model="form.phone" type="text" class="w-full"/>
                        </div>

                        <div class="flex flex-col gap-1 w-full md:w-1/2">
                            <InputLabel value="Adresse"/>
                            <TextInput v-model="form.address" type="text" class="w-full"/>
                        </div>
                    </div>

                    <div class="flex  flex-col gap-1">
                        <InputLabel value="Kortnummer"/>
                        <TextInput v-model="form.card" type="text" class="w-full"/>
                    </div>

                    <div class="flex flex-col lg:flex-row gap-4">
                        <div class="flex flex-col gap-1 w-full md:w-1/2">
                            <InputLabel value="Udløbsdato"/>
                            <TextInput v-model="form.expiry" type="text" class="w-full"/>
                        </div>

                        <div class="flex flex-col gap-1 w-full md:w-1/2">
                            <InputLabel value="CVC"/>
                            <TextInput v-model="form.cvc" type="text" class="w-full"/>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-300 dark:bg-gray-800 rounded-md w-full md:w-1/3 p-3">
                    <h1 class="text-center text-2xl font-bold">Indkøbsliste</h1>

                    <button class="py-2 px-4 bg-purple-500 text-white rounded-md mt-4 w-full" @click="step >= 3 ? 3 : step++">
                        Fortsæt
                    </button>
                    <button class="py-2 px-4 bg-red-500 text-white rounded-md mt-4 w-full" @click="step <= 1 ? 1 : step--">
                        Tilbage
                    </button>
                </div>
            </div>

        </div>

    </AppLayout>
</template>
