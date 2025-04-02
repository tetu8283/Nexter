<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arrival extends Model
{
    use HasFactory;

    protected $table = 'arrival_books';

    // arrival_date を日付としてキャスト
    protected $casts = [
        'arrival_date' => 'date',
    ];

    protected $fillable = [
        'book_id',
        'store_id',
        'arrival_date',
        'arrival_flag' // 0: 登録, 1: 確定
    ];

    // arrivalとbookのリレーション
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // arrivalとstoreのリレーション
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    // arrivalは複数のbookを持つ
    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
