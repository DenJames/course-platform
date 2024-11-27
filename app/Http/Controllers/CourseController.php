<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Welcome', [
            'courses' => Course::with(['author'])->inRandomOrder()->take(10)->get(),
            'recommendedCourses' => Course::with(['author'])->inRandomOrder()->take(10)->get(),
            'bestSellingCourses' => Course::with(['author'])->inRandomOrder()->take(10)->get(),
        ]);
    }
}
