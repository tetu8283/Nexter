<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'weight',
        'status_flag',
    ];

    // 複数の本は1つの在庫に所属する
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    // 1つの入荷予定は複数の本を持つ
    public function arrivals()
    {
        return $this->belongsTo(Arrival::class);
    }
}
