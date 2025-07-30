<?php

namespace App\Modules\User\Controllers;

use App\Modules\User\Interfaces\UserServiceInterface;
use App\Modules\User\Models\User;
use App\Modules\User\Requests\StoreUserRequest;
use App\Modules\User\Requests\UpdateUserRequest;
use App\Modules\User\Resources\UserCollectionResource;
use App\Modules\User\Resources\UserResource;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;

class UserController
{
    use ApiResponse;

    public function __construct(private UserServiceInterface $userService) {}

    public function index(Request $request)
    {
        try {
            $users = $this->userService->index($request);

            return $this->success('Users retrieved successfully', new UserCollectionResource($users));
        } catch (Exception $e) {
            return $this->error('Failed to retrieve users: '.$e->getMessage());
        }
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $user = $this->userService->store($request);

            return $this->success('User created successfully', new UserResource($user), 'item');
        } catch (Exception $e) {
            return $this->error('Failed to create user: '.$e->getMessage());
        }
    }

    public function show(User $user)
    {
        try {
            return $this->success('User retrieved successfully', new UserResource($user), 'item');
        } catch (Exception $e) {
            return $this->error('User not found', 404);
        }
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $user = $this->userService->update($request, $user);

            return $this->success('User updated successfully', new UserResource($user), 'item');
        } catch (Exception $e) {
            return $this->error('Failed to update user: '.$e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        try {
            $this->userService->destroy($user);

            return $this->success('User deleted successfully', [], 'item');
        } catch (Exception $e) {
            return $this->error('Failed to delete user: '.$e->getMessage());
        }
    }

    public function activate(User $user)
    {
        try {
            $user = $this->userService->activate($user);

            return $this->success('User activated successfully', new UserResource($user), 'item');
        } catch (Exception $e) {
            return $this->error('Failed to activate user: '.$e->getMessage());
        }
    }

    public function deactivate(User $user)
    {
        try {
            $user = $this->userService->deactivate($user);

            return $this->success('User deactivated successfully', new UserResource($user), 'item');
        } catch (Exception $e) {
            return $this->error('Failed to deactivate user: '.$e->getMessage());
        }
    }
}
