<?php

namespace App\Services\AuthenticationServices;

interface AuthenticationInterface
{
    /**
     * <p>Check password of user is valid</p>
     *
     * @param int $userId <p>The user's id authenticated</p>
     * @param string $password <p>The password which is compare with password of user authenticated<p>
     * @return bool <p>Returns <b>TRUE<b> if password is valid</p>
     * <p>otherwise <b>FALSE</b></p>
     */
    public function checkValidPassword(int $userId, string $password): bool;
}
