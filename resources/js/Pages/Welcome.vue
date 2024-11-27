<script setup>
import {Head, Link} from '@inertiajs/vue3';
import {ref, onMounted, onUnmounted} from 'vue';
import AppLayout from "@/Layouts/AppLayout.vue";
import StarIcon from "@/Components/Icons/StarIcon.vue";
import CourseItem from "@/Components/CourseItem.vue";
import ChevronLeftIcon from "@/Components/Icons/ChevronLeftIcon.vue";
import ChevronRightIcon from "@/Components/Icons/ChevronRightIcon.vue";
import {useCourse} from "@/Composables/useCourse.js";

defineProps({
    courses: {
        type: Array,
        required: true,
    },
    recommendedCourses: {
        type: Array,
        required: true,
    },
    bestSellingCourses: {
        type: Array,
        required: true,
    },
});

// Create refs for each carousel section
const yourCoursesContainer = ref(null);
const recommendedContainer = ref(null);
const topRatedContainer = ref(null);

// Separate scroll states for each carousel
const carouselStates = {
    yourCourses: ref({
        isAnimating: false,
        canScrollLeft: false,
        canScrollRight: false,
        isDragging: false,
        startX: 0,
        scrollLeft: 0
    }),
    recommended: ref({
        isAnimating: false,
        canScrollLeft: false,
        canScrollRight: false,
        isDragging: false,
        startX: 0,
        scrollLeft: 0
    }),
    topRated: ref({
        isAnimating: false,
        canScrollLeft: false,
        canScrollRight: false,
        isDragging: false,
        startX: 0,
        scrollLeft: 0
    })
};

const getCourseWidth = (container) => {
    const courseElement = container?.querySelector('.course-item');
    if (courseElement) {
        return courseElement.offsetWidth + 24; // 24px = gap-6
    }
    return 0;
};

const scrollOneRight = (container, state) => {
    if (container && !state.value.isAnimating && state.value.canScrollLeft) {
        state.value.isAnimating = true;
        const courseWidth = getCourseWidth(container);

        container.scrollBy({
            left: courseWidth,
            behavior: 'smooth'
        });

        setTimeout(() => {
            state.value.isAnimating = false;
        }, 500);
    }
};

const scrollOneLeft = (container, state) => {
    if (container && !state.value.isAnimating && state.value.canScrollRight) {
        state.value.isAnimating = true;
        const courseWidth = getCourseWidth(container);

        container.scrollBy({
            left: -courseWidth,
            behavior: 'smooth'
        });

        setTimeout(() => {
            state.value.isAnimating = false;
        }, 500);
    }
};

const updateScrollButtons = (container, state) => {
    if (container) {
        state.value.canScrollLeft = container.scrollLeft < (container.scrollWidth - container.offsetWidth);
        state.value.canScrollRight = container.scrollLeft > 0;
    }
};

const setupDragHandlers = (container, state) => {
    const startDragging = (e) => {
        state.value.isDragging = true;
        state.value.startX = e.pageX - container.offsetLeft;
        state.value.scrollLeft = container.scrollLeft;
        container.style.cursor = 'grabbing';
        container.style.userSelect = 'none';
    };

    const stopDragging = () => {
        state.value.isDragging = false;
        container.style.cursor = 'grab';
        container.style.userSelect = '';
    };

    const drag = (e) => {
        if (!state.value.isDragging) return;
        e.preventDefault();
        const x = e.pageX - container.offsetLeft;
        const walk = (x - state.value.startX);
        container.scrollLeft = state.value.scrollLeft - walk;
    };

    container.addEventListener('mousedown', startDragging);
    container.addEventListener('mouseleave', stopDragging);
    container.addEventListener('mouseup', stopDragging);
    container.addEventListener('mousemove', drag);
    container.style.cursor = 'grab';

    return () => {
        container.removeEventListener('mousedown', startDragging);
        container.removeEventListener('mouseleave', stopDragging);
        container.removeEventListener('mouseup', stopDragging);
        container.removeEventListener('mousemove', drag);
    };
};

onMounted(() => {
    // Setup scroll and drag handlers for each carousel
    const containers = {
        yourCourses: yourCoursesContainer.value,
        recommended: recommendedContainer.value,
        topRated: topRatedContainer.value
    };

    const cleanupFns = [];

    Object.entries(containers).forEach(([key, container]) => {
        if (container) {
            const state = carouselStates[key];
            const updateScroll = () => updateScrollButtons(container, state);

            // Force initial update of scroll buttons
            updateScroll();

            container.addEventListener('scroll', updateScroll);
            cleanupFns.push(() => container.removeEventListener('scroll', updateScroll));

            const dragCleanup = setupDragHandlers(container, state);
            cleanupFns.push(dragCleanup);
        }
    });

    onUnmounted(() => {
        cleanupFns.forEach(fn => fn());
    });
});
</script>

<template>
    <Head title="Welcome"/>

    <AppLayout>
        <!-- User welcome section -->
        <div v-if="$page.props.auth.user" class="flex gap-2 items-center">
            <div class="bg-purple-500 rounded-full w-16 h-16 flex items-center justify-center mt-10">
                <h2 class="font-bold text-2xl dark:text-gray-200 text-white">DE</h2>
            </div>

            <div class="flex flex-col dark:text-gray-200 mt-10">
                <h2 class="text-xl font-bold">Velkommen tilbage {{ $page.props.auth.user.name }}</h2>
                <p>Fortsæt din læring!</p>
            </div>
        </div>

        <div class="mt-10 hidden md:block relative">
            <img
                src="https://img-c.udemycdn.com/notices/featured_carousel_slide/image/34c63aef-8d1f-483e-b0ea-0ead94879e56.jpg"
                alt=""
                class="w-full h-64 bg-contain rounded-md">

            <div class="absolute top-5 left-5 rounded-md bg-white p-4 shadow-md w-2/5 z-30">
                <h2 class="text-2xl font-bold">
                    Tag din karriere til næste niveau i dag!
                </h2>

                <p>
                    Tag din karriere til næste niveau med onlinekurser. Tag din karriere til næste niveau med
                    onlinekurser. Tag din karriere til næste niveau med onlinekurser.
                </p>
            </div>

            <div class="bg-black/20 w-full h-full absolute top-0 left-0 z-20"></div>
        </div>

        <!-- Your Courses Section -->
        <div class="w-full relative" v-if="$page.props.auth.user">
            <button
                @click="scrollOneLeft(yourCoursesContainer, carouselStates.yourCourses)"
                :disabled="!carouselStates.yourCourses.value.canScrollRight"
                class="h-12 w-12 rounded-full bg-black dark:bg-gray-700 text-white flex items-center justify-center lg:absolute lg:-left-16 lg:top-[calc(50%-20px)] transition-all hover:scale-105 mt-4 lg:mt-0"
                :class="{'opacity-30': !carouselStates.yourCourses.value.canScrollRight}">
                <ChevronLeftIcon class="size-6 text-white"/>
            </button>

            <button
                @click="scrollOneRight(yourCoursesContainer, carouselStates.yourCourses)"
                :disabled="!carouselStates.yourCourses.value.canScrollLeft"
                class="h-12 w-12 rounded-full bg-black dark:bg-gray-700 text-white flex items-center justify-center right-0 top-0 absolute lg:-right-16 lg:top-[calc(50%-20px)] transition-all hover:scale-105"
                :class="{'opacity-30': !carouselStates.yourCourses.value.canScrollLeft}">
                <ChevronRightIcon class="size-6 text-white"/>
            </button>

            <div class="flex flex-col gap-2 mt-4 lg:mt-10">
                <h2 class="font-bold text-2xl dark:text-gray-200">Fortsæt med at se</h2>
                <div
                    ref="yourCoursesContainer"
                    class="w-full overflow-x-auto flex flex-grow-0 gap-6 pb-4 carousel-container">
                    <Link :href="route('courses.show', course)" v-for="(course, index) in courses" :key="index">
                        <CourseItem
                            :show-badge="false"
                            :can-buy="false"
                            :show-progress="true"
                            :course="{
                                ...course,
                                progress: useCourse().calculateProgress(course)
                            }"
                            class="relative course-item !w-72"
                        >
                            <div
                                v-if="course.completed_at"
                                class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded-md text-sm"
                            >
                                Gennemført
                            </div>
                        </CourseItem>
                    </Link>
                </div>
            </div>
        </div>

        <!-- Recommended Courses Section -->
        <div class="w-full relative">
            <button
                @click="scrollOneLeft(recommendedContainer, carouselStates.recommended)"
                :disabled="!carouselStates.recommended.value.canScrollRight"
                class="h-12 w-12 rounded-full bg-black dark:bg-gray-700 text-white flex items-center justify-center lg:absolute lg:-left-16 lg:top-[calc(50%-20px)] transition-all hover:scale-105 mt-4 lg:mt-0"
                :class="{'opacity-30': !carouselStates.recommended.value.canScrollRight}">
                <ChevronLeftIcon class="size-6 text-white"/>
            </button>

            <button
                @click="scrollOneRight(recommendedContainer, carouselStates.recommended)"
                :disabled="!carouselStates.recommended.value.canScrollLeft"
                class="h-12 w-12 rounded-full bg-black dark:bg-gray-700 text-white flex items-center justify-center right-0 top-0 absolute lg:-right-16 lg:top-[calc(50%-20px)] transition-all hover:scale-105"
                :class="{'opacity-30': !carouselStates.recommended.value.canScrollLeft}">
                <ChevronRightIcon class="size-6 text-white"/>
            </button>

            <div class="flex flex-col gap-2 mt-4 lg:mt-10">
                <h2 class="font-bold text-2xl dark:text-gray-200">Anbefalet til dig</h2>
                <div
                    ref="recommendedContainer"
                    class="w-full overflow-x-auto flex flex-grow-0 gap-6 pb-4 carousel-container">
                    <Link :href="route('courses.show', course)" v-for="(course, index) in recommendedCourses"
                          :key="index">
                        <CourseItem :course="course" class="flex-shrink-0 course-item !w-72"/>
                    </Link>
                </div>
            </div>
        </div>

        <!-- Top Rated Courses Section -->
        <div class="w-full relative">
            <button
                @click="scrollOneLeft(topRatedContainer, carouselStates.topRated)"
                :disabled="!carouselStates.topRated.value.canScrollRight"
                class="h-12 w-12 rounded-full bg-black dark:bg-gray-700 text-white flex items-center justify-center lg:absolute lg:-left-16 lg:top-[calc(50%-20px)] transition-all hover:scale-105 mt-4 lg:mt-0"
                :class="{'opacity-30': !carouselStates.topRated.value.canScrollRight}">
                <ChevronLeftIcon class="size-6 text-white"/>
            </button>

            <button
                @click="scrollOneRight(topRatedContainer, carouselStates.topRated)"
                :disabled="!carouselStates.topRated.value.canScrollLeft"
                class="h-12 w-12 rounded-full bg-black dark:bg-gray-700 text-white flex items-center justify-center right-0 top-0 absolute lg:-right-16 lg:top-[calc(50%-20px)] transition-all hover:scale-105"
                :class="{'opacity-30': !carouselStates.topRated.value.canScrollLeft}">
                <ChevronRightIcon class="size-6 text-white"/>
            </button>

            <div class="flex flex-col gap-2 mt-4 lg:mt-10">
                <h2 class="font-bold text-2xl dark:text-gray-200">Bedst sælgende</h2>
                <div
                    ref="topRatedContainer"
                    class="w-full overflow-x-auto flex flex-grow-0 gap-6 pb-4 carousel-container">
                    <Link :href="route('courses.show', course)" v-for="(course, index) in bestSellingCourses"
                          :key="index">
                        <CourseItem :course="course" :show-badge="false" class="flex-shrink-0 course-item !w-72"/>
                    </Link>
                </div>
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
