<?php

namespace App\Modules\User\Repositories;

use App\Modules\User\Interfaces\UserRepositoryInterface;
use App\Modules\User\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function index($request): LengthAwarePaginator
    {
        $query = User::when($request->has('search'), function ($query) use ($request) {
            $query->search($request->get('search'));
        })
            ->when($request->has('status'), function ($query) use ($request) {
                $query->active($request->get('status') === 'active');
            });

        // Sort functionality
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($request->get('per_page', 15));
    }

    public function store($request): User
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = $data['is_active'] ?? true;

        return User::create($data);
    }

    public function update($request, $user): User
    {
        $data = $request->validated();

        // Handle password update
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return $user->fresh();
    }

    public function destroy($user): bool
    {
        return $user->delete();
    }

    public function activate($user): User
    {
        $user->update(['is_active' => true]);

        return $user->fresh();
    }

    public function deactivate($user): User
    {
        $user->update(['is_active' => false]);

        return $user->fresh();
    }
}
