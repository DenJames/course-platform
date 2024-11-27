<?php

namespace App\Http\Controllers;

use App\Http\Actions\CreateUser;
use App\Http\Requests\UserFormRequest;
use App\Mail\CreatePassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function index()
    {
        return Inertia::render('Checkout');
    }

    public function store(UserFormRequest $request)
    {
        $user = User::where('email', $request->get('email'))->first();
        if (!$user) {
            $user = app(CreateUser::class)->execute($request);
        }

        // Get the course IDs from the request items
        $courseIds = collect($request->get('items'))
            ->pluck('id')
            ->filter()
            ->values()
            ->toArray();

        // Attach the courses to the user
        if (!empty($courseIds)) {
            $user->courses()->attach($courseIds, [
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Send password creation email
        Mail::to($request->get('email'))->send(new CreatePassword($user));
    }
}
