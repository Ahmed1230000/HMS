<?php

namespace App\Domains\User\UseCases\ShowUserUseCase;

use App\Domains\User\DTOs\User\ShowUserDTO;
use App\Domains\User\Exceptions\User\UserNotFoundException;
use App\Domains\User\Repositories\Contracts\User\UserRepositoryInterface;
use App\Models\User;

class ShowUserUseCase
{
    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        private UserRepositoryInterface $repository
    ) {}

    /*
    |--------------------------------------------------------------------------
    | Handle
    |--------------------------------------------------------------------------
    */

    public function execute(int $id): User
    {
        $user = $this->repository->findModel($id);

        if (!$user) {
            throw new UserNotFoundException('User not found.');
        }
        return $user;
    }
}
