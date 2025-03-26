<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    // storeは複数のuserを持つ
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // storeは複数の入荷予定を持つ
    public function arrivals()
    {
        return $this->hasMany(Arrival::class);
    }

    // storeは複数のinventoryを持つ
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
}
