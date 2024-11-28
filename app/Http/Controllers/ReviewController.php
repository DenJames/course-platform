<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewFormRequest;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ReviewController extends Controller
{
    public function store(ReviewFormRequest $request, Course $course)
    {
        abort_if(!Auth::user()->courses->contains($course), 403);

        if ($course->reviews()->where('user_id', Auth::id())->exists()) {
            throw ValidationException::withMessages([
                'content' => 'Du har allerede anmeldt dette kursus.',
            ]);
        }

        $course->reviews()->create([
            'user_id' => Auth::id(),
            'content' => $request->get('content'),
            'rating' => $request->get('rating'),
        ]);

        return redirect()->back()->with('success', 'Anmeldelse oprettet.');
    }

    public function update(ReviewFormRequest $request, Course $course)
    {
        abort_if(!Auth::user()->courses->contains($course), 403);

        $review = $course->reviews()->where('user_id', Auth::id())->first();

        $review->update([
            'content' => $request->get('content'),
            'rating' => $request->get('rating'),
        ]);

        return redirect()->back()->with('success', 'Anmeldelse opdateret.');
    }

    public function destroy(Course $course)
    {
        abort_if(!Auth::user()->courses->contains($course), 403);

        $course->reviews()->where('user_id', Auth::id())->delete();

        return redirect()->back()->with('success', 'Anmeldelse slettet.');
    }
}
