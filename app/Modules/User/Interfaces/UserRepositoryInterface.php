<?php

namespace App\Modules\User\Interfaces;

use App\Modules\User\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function index($request): LengthAwarePaginator;

    public function store($request): User;

    public function update($request, $user): User;

    public function destroy($user): bool;

    public function activate($user): User;

    public function deactivate($user): User;
}
