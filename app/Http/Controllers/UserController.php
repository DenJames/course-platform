<?php

namespace App\Http\Controllers;

use App\Http\Actions\CreateUser;
use App\Http\Requests\UserFormRequest;
use App\Mail\CreatePassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function store(UserFormRequest $request)
    {
        $user = app(CreateUser::class)->execute($request);

        Mail::to($request->get('email'))->send(new CreatePassword($user));
    }

    public function check(Request $request)
    {
        return response()->json([
            'exists' => User::where('email', $request->get('email'))->exists()
        ]);
    }
}
