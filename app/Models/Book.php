<?php

namespace App\Models;

use App\Observers\BookObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use MongoDB\Laravel\Eloquent\Model;

#[ObservedBy([BookObserver::class])]
class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'mongodb';

    protected $table = 'books';

    protected $fillable = [
        'title',
        'publication_date',
        'author',
    ];

    const VIEW_TOPIC = 'book-viewed';

    const CREATE_TOPIC = 'book-created';

    const UPDATE_TOPIC = 'book-updated';
}
