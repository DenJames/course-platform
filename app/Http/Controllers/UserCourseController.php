<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserCourseController extends Controller
{
    public function __invoke()
    {
        $userCourses = Auth::user()
            ->courses()
            ->with(['author'])
            ->get()
            ->map(function ($course) {
                $course->completed = $course->lessons->count() >= Auth::user()->completedLessons->count();

                return $course;
            });

        return Inertia::render('UserCourses', [
            'courses' => $userCourses,
        ]);
    }
}
