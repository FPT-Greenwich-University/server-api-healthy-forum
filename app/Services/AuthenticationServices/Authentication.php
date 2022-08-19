<?php

namespace App\Services\AuthenticationServices;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Authentication implements AuthenticationInterface
{
    final public function checkValidPassword(int $userId, string $password): bool
    {
        $user = User::find($userId);

        if (!Hash::check($password, $user->password)) {
            return false;
        }

        return true;
    }

}
