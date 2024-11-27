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
            ->get();

        return Inertia::render('UserCourses', [
            'courses' => $userCourses,
        ]);
    }
}
