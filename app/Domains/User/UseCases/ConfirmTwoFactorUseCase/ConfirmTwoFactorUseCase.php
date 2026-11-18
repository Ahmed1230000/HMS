<?php

namespace App\Domains\User\UseCases\ConfirmTwoFactorUseCase;


use App\Domains\User\DTOs\User\ConfirmTwoFactorDTO;
use App\Domains\User\Exceptions\User\TwoFactorAuthenticationAlreadyEnabledException;
use App\Domains\User\Exceptions\User\UserNotFoundException;
use App\Models\User;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;

class ConfirmTwoFactorUseCase
{
    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(private ConfirmTwoFactorAuthentication $confirmTwoFactorAuthentication) {}

    /*
    |--------------------------------------------------------------------------
    | Handle
    |--------------------------------------------------------------------------
    */

    public function execute(ConfirmTwoFactorDTO $dto)
    {
        $user = auth()->user();

        if (!$user instanceof User) {
            throw new UserNotFoundException('User not found');
        }

        if (
            is_null($user->two_factor_secret) ||
            !is_null($user->two_factor_confirmed_at)
        ) {
            throw new TwoFactorAuthenticationAlreadyEnabledException(
                'Two-factor authentication is already enabled for this user.'
            );
        }

        $this->confirmTwoFactorAuthentication->__invoke($user, $dto->code);
    }
}
