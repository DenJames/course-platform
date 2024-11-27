<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use Inertia\Inertia;

class CourseCategoryController extends Controller
{
    public function __invoke(Category $category)
    {
        return Inertia::render('Courses', [
            'courses' => Course::where('category_id', $category->id)->with(['author'])->get(),
        ]);
    }
}
