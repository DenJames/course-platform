<?php

namespace App\Http\Actions;

use App\Http\Requests\UserFormRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateUser
{
    public function execute(UserFormRequest $request): User
    {
        return User::create([...$request->validated(), 'password' => Hash::make(Str::random())]);
    }
}
