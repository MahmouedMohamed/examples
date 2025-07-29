<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use MongoDB\Laravel\Eloquent\Model;

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
}
