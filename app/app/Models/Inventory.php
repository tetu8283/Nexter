<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'book_id',
    ];

    // 複数のinventoryは1つのstoreに所属する
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
