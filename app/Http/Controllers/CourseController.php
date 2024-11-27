<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index()
    {
        return Inertia::render('Welcome', [
            'courses' => Course::with(['author'])->inRandomOrder()->take(10)->get(),
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
        $lessons = $course->lessons()->pluck('id');
        auth()->user()->completedLessons()->whereIn('lesson_id', $lessons)->detach();

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
