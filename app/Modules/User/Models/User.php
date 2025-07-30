<?php

namespace App\Modules\User\Models;

use App\Exceptions\ModelNotFoundException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'date_of_birth',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function resolveRouteBinding($value, $field = null)
    {
        $model = static::where('id', $value)->first();
        if (empty($model)) {
            throw new ModelNotFoundException;
        }

        return $model;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope to search users.
     */
    public function scopeSearch($query, string $searchKey)
    {
        return $query->where(function ($q) use ($searchKey) {
            $q->where('name', 'like', "%{$searchKey}%")
                ->orWhere('email', 'like', "%{$searchKey}%");
        });
    }

    /**
     * Scope to get only active users.
     */
    public function scopeActive($query, bool $status)
    {
        return $query->where('is_active', $status);
    }
}
