<?php

namespace App\Domains\User\UseCases\EnableTwoFactorUseCase;

use App\Domains\User\Exceptions\User\TwoFactorAuthenticationAlreadyEnabledException;
use App\Domains\User\Exceptions\User\UserNotFoundException;
use App\Models\User;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;

class EnableTwoFactorUseCase
{
    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(private EnableTwoFactorAuthentication $enableTwoFactorAuthentication) {}

    /*
    |--------------------------------------------------------------------------
    | Handle
    |--------------------------------------------------------------------------
    */

    public function execute(): void
    {
        $user = auth()->user();
        if (!$user instanceof User) {
            throw new UserNotFoundException('User not found');
        }

        if ($user->hasEnabledTwoFactorAuthentication()) {
            throw new TwoFactorAuthenticationAlreadyEnabledException('Two-factor authentication is already enabled for this user.');
        }

        $this->enableTwoFactorAuthentication->__invoke($user);
    }
}
