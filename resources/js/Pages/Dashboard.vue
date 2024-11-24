<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import AppLayout from "@/Layouts/AppLayout.vue";
import StarIcon from "@/Components/Icons/StarIcon.vue";
import CourseItem from "@/Components/CourseItem.vue";
import ChevronLeftIcon from "@/Components/Icons/ChevronLeftIcon.vue";
import ChevronRightIcon from "@/Components/Icons/ChevronRightIcon.vue";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

const scrollContainer = ref(null);
const isAnimating = ref(false);
const canScrollLeft = ref(false);
const canScrollRight = ref(false);
const isDragging = ref(false);
const startX = ref(0);
const scrollLeft = ref(0);

const getCourseWidth = () => {
    const courseElement = scrollContainer.value?.querySelector('.flex-shrink-0');
    if (courseElement) {
        // Get width including gap (24px = gap-6)
        return courseElement.offsetWidth + 24;
    }
    return 0;
};

const scrollOneRight = () => {
    if (scrollContainer.value && !isAnimating.value && canScrollLeft.value) {
        isAnimating.value = true;
        const courseWidth = getCourseWidth();

        scrollContainer.value.scrollBy({
            left: courseWidth,
            behavior: 'smooth'
        });

        setTimeout(() => {
            isAnimating.value = false;
        }, 500);
    }
};

const scrollOneLeft = () => {
    if (scrollContainer.value && !isAnimating.value && canScrollRight.value) {
        isAnimating.value = true;
        const courseWidth = getCourseWidth();

        scrollContainer.value.scrollBy({
            left: -courseWidth,
            behavior: 'smooth'
        });

        setTimeout(() => {
            isAnimating.value = false;
        }, 500);
    }
};

const updateScrollButtons = () => {
    if (scrollContainer.value) {
        const container = scrollContainer.value;
        canScrollLeft.value = container.scrollLeft < (container.scrollWidth - container.offsetWidth);
        canScrollRight.value = container.scrollLeft > 0;
    }
};

// Drag to scroll functionality
const startDragging = (e) => {
    isDragging.value = true;
    startX.value = e.pageX - scrollContainer.value.offsetLeft;
    scrollLeft.value = scrollContainer.value.scrollLeft;
    scrollContainer.value.style.cursor = 'grabbing';
    scrollContainer.value.style.userSelect = 'none';
};

const stopDragging = () => {
    isDragging.value = false;
    scrollContainer.value.style.cursor = 'grab';
    scrollContainer.value.style.userSelect = '';
};

const drag = (e) => {
    if (!isDragging.value) return;
    e.preventDefault();
    const x = e.pageX - scrollContainer.value.offsetLeft;
    const walk = (x - startX.value);
    scrollContainer.value.scrollLeft = scrollLeft.value - walk;
};

onMounted(() => {
    if (scrollContainer.value) {
        scrollContainer.value.addEventListener('scroll', updateScrollButtons);
        scrollContainer.value.addEventListener('mousedown', startDragging);
        scrollContainer.value.addEventListener('mouseleave', stopDragging);
        scrollContainer.value.addEventListener('mouseup', stopDragging);
        scrollContainer.value.addEventListener('mousemove', drag);
        scrollContainer.value.style.cursor = 'grab';
        updateScrollButtons();
    }
});

onUnmounted(() => {
    if (scrollContainer.value) {
        scrollContainer.value.removeEventListener('scroll', updateScrollButtons);
        scrollContainer.value.removeEventListener('mousedown', startDragging);
        scrollContainer.value.removeEventListener('mouseleave', stopDragging);
        scrollContainer.value.removeEventListener('mouseup', stopDragging);
        scrollContainer.value.removeEventListener('mousemove', drag);
    }
});

const courses = [
    {
        title: 'Introduktion til Vue 3',
        description: 'Kom med, når vi tager dig igennem det basale i Vue 3, i dette interaktive kursus.',
        imageUrl: 'https://cdn.pixabay.com/photo/2016/05/27/08/51/mobile-phone-1419275_960_720.jpg',
        imageAlt: 'Vue logo',
        lessonCount: 8,
        rating: 100,
    },
    {
        title: 'Introduktion til React',
        description: 'Kom med, når vi tager dig igennem det basale i React, i dette interaktive kursus.',
        imageUrl: 'https://cdn.pixabay.com/photo/2016/05/27/08/51/mobile-phone-1419275_960_720.jpg',
        imageAlt: 'React logo',
        lessonCount: 23,
        rating: 95,
    },
    {
        title: 'Introduktion til Tailwind CSS',
        description: 'Kom med når vi tager dig igennem det basale i Tailwind CSS, i dette interaktive kursus.',
        imageUrl: 'https://cdn.pixabay.com/photo/2016/05/27/08/51/mobile-phone-1419275_960_720.jpg',
        imageAlt: 'Tailwind CSS logo',
        lessonCount: 29,
        rating: 70,
    },
    {
        title: 'Introduktion til Laravel',
        description: 'Kom med når vi tager dig igennem det basale i Laravel, i dette interaktive kursus.',
        imageUrl: 'https://cdn.pixabay.com/photo/2016/05/27/08/51/mobile-phone-1419275_960_720.jpg',
        imageAlt: 'Laravel logo',
        lessonCount: 57,
        rating: 77,
    },
    {
        title: 'Introduktion til PHP',
        description: 'Kom med når vi tager dig igennem det basale i PHP, i dette interaktive kursus.',
        imageUrl: 'https://cdn.pixabay.com/photo/2016/05/27/08/51/mobile-phone-1419275_960_720.jpg',
        imageAlt: 'PHP logo',
        lessonCount: 57,
        rating: 80,
    },
    {
        title: 'Introduktion til Javascript',
        description: 'Kom med når vi tager dig igennem det basale i Javascript, i dette interaktive kursus.',
        imageUrl: 'https://cdn.pixabay.com/photo/2016/05/27/08/51/mobile-phone-1419275_960_720.jpg',
        imageAlt: 'Javascript logo',
        lessonCount: 57,
        rating: 98,
    },
    {
        title: 'Introduktion til HTML',
        description: 'Kom med når vi tager dig igennem det basale i HTML, i dette interaktive kursus.',
        imageUrl: 'https://cdn.pixabay.com/photo/2016/05/27/08/51/mobile-phone-1419275_960_720.jpg',
        imageAlt: 'HTML logo',
        lessonCount: 57,
        rating: 95,
    },
];

const stars = [1, 2, 3, 4, 5];

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <Head title="Welcome"/>

    <AppLayout>
        <div v-if="$page.props.auth.user" class="flex gap-2 items-center mt-10">
            <div class="bg-purple-500 rounded-full w-16 h-16 flex items-center justify-center">
                <h2 class="font-bold text-2xl text-white">DE</h2>
            </div>

            <div class="flex flex-col">
                <h2 class="text-xl font-bold">Velkommen tilbage {{ $page.props.auth.user.name }}</h2>
                <p>Fortsæt din læring!</p>
            </div>
        </div>

        <div class="mt-10">
            <img src="https://cdn.pixabay.com/photo/2016/05/27/08/51/mobile-phone-1419275_960_720.jpg" alt=""
                 class="w-full h-96 bg-contain">
        </div>

        <div class="w-full relative">
            <button
                @click="scrollOneLeft"
                :disabled="!canScrollRight"
                class="h-12 w-12 rounded-full bg-black text-white flex items-center justify-center absolute -left-16 top-[calc(50%-20px)] transition-all hover:scale-105"
                :class="{'opacity-30': !canScrollRight, 'hover:opacity-30': !canScrollRight}">
                <ChevronLeftIcon class="size-6 text-white"/>
            </button>

            <button
                @click="scrollOneRight"
                :disabled="!canScrollLeft"
                class="h-12 w-12 rounded-full bg-black text-white flex items-center justify-center absolute -right-16 top-[calc(50%-20px)] transition-all hover:scale-105"
                :class="{'opacity-30': !canScrollLeft, 'hover:opacity-30': !canScrollLeft}">
                <ChevronRightIcon class="size-6 text-white"/>
            </button>

            <div
                ref="scrollContainer"
                class="w-full overflow-x-auto flex flex-grow-0 gap-6 mt-10 pb-4 carousel-container">
                <template v-for="(course, index) in courses" :key="index">
                    <CourseItem :course="course" class="flex-shrink-0"/>
                </template>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.carousel-container {
    -ms-overflow-style: none;
    scrollbar-width: none;
    scroll-behavior: smooth;
    transition: all 0.5s ease-in-out;
    cursor: grab;
}

.carousel-container::-webkit-scrollbar {
    display: none;
}

.carousel-container:active {
    cursor: grabbing;
}

button {
    transition: all 0.2s ease-in-out;
}

button:disabled {
    cursor: not-allowed;
}
</style>
