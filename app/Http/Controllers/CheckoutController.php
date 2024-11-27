<?php

namespace App\Http\Controllers;

use App\Http\Actions\CreateUser;
use App\Http\Requests\UserFormRequest;
use App\Mail\CreatePassword;
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
        $user = app(CreateUser::class)->execute($request);

        Mail::to($request->get('email'))->send(new CreatePassword($user));
    }
}
