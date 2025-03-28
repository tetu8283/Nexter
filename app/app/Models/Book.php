<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Inventory;
use App\Models\Arrival;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'weight',
        'status_flag',
    ];

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }


    // 1つの入荷予定は複数の本を持つ
    public function arrivals()
    {
        return $this->belongsTo(Arrival::class);
    }
}
