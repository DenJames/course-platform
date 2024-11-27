<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index()
    {
        $userCourses = Auth::user()
            ?->courses()
            ->with(['author'])
            ->select('courses.*')
            ->selectSub(
                function($query) {
                    $query->from('completed_lessons')
                        ->join('lessons', 'lessons.id', '=', 'completed_lessons.lesson_id')
                        ->whereColumn('lessons.course_id', 'courses.id')
                        ->where('completed_lessons.user_id', Auth::id())
                        ->select('completed_lessons.created_at')
                        ->latest('completed_lessons.created_at')
                        ->limit(1);
                },
                'latest_completion'
            )
            ->orderByRaw('COALESCE(latest_completion, "1900-01-01") DESC')
            ->get();

        return Inertia::render('Welcome', [
            'courses' => $userCourses,
            'recommendedCourses' => Course::with(['author'])->inRandomOrder()->take(10)->get(),
            'bestSellingCourses' => Course::with(['author'])->inRandomOrder()->take(10)->get(),
        ]);
    }

    public function show(Course $course)
    {
        $lessons = $course->lessons()
            ->orderBy('created_at')
            ->get()
            ->map(function ($lesson) {
                $lesson->completed = Auth::user()?->completedLessons?->contains($lesson->id);

                return $lesson;
            });

        return Inertia::render('Course/Show', [
            'course' => $course,
            'lessons' => $lessons,
            'isEnrolled' => Auth::user()?->courses?->contains($course->id)
        ]);
    }

    public function resetProgress(Course $course)
    {
        $lessonIds = $course->lessons()->pluck('id');
        $user = Auth::user();

        $user->completedLessons()->wherePivotIn('lesson_id', $lessonIds)->detach();

        return back();
    }

    public function markComplete(Course $course)
    {
        $lessons = $course->lessons()->pluck('id');

        Auth::user()->completedLessons()->syncWithoutDetaching(
            $lessons->mapWithKeys(function ($id) {
                return [$id => ['completed_at' => now()]];
            })->toArray()
        );

        return back();
    }
}
