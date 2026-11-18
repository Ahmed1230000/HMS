<?php

namespace App\Domains\User\DTOs\User;

class ConfirmTwoFactorDTO
{
    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        public string $code
    ) {}


    public static function fromArray(array $data)
    {
        return new self(
            code: $data['code'] ?? ''
        );
    }
}
