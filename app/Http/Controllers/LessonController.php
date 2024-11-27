<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function toggleCompletion(Lesson $lesson)
    {
        $user = Auth::user();

        if ($user->completedLessons()->where('lesson_id', $lesson->id)->exists()) {
            $user->completedLessons()->detach($lesson->id);
        } else {
            $user->completedLessons()->attach($lesson->id, [
                'completed_at' => now()
            ]);
        }

        return back();
    }
}
