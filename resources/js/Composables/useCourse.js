export function useCourse() {
    function calculateProgress(course) {
        if (course.completed) return 100;
        if (!course.lessons || course.lessons.length === 0) return 0;

        const completedLessons = course.lessons.filter(lesson => lesson.completed).length;
        return (completedLessons / course.lessons.length) * 100;
    }

    return {
        calculateProgress
    }
}
