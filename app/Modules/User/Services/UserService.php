<?php

namespace App\Modules\User\Services;

use App\Modules\User\Interfaces\UserRepositoryInterface;
use App\Modules\User\Interfaces\UserServiceInterface;
use App\Modules\User\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService implements UserServiceInterface
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function index($request): LengthAwarePaginator
    {
        return $this->userRepository->index($request);
    }

    public function store($request): User
    {
        return $this->userRepository->store($request);
    }

    public function update($request, $user): User
    {
        return $this->userRepository->update($request, $user);
    }

    public function destroy($user): bool
    {
        return $this->userRepository->destroy($user);
    }

    public function activate($user): User
    {
        return $this->userRepository->activate($user);
    }

    public function deactivate($user): User
    {
        return $this->userRepository->deactivate($user);
    }
}
