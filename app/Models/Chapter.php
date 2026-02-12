<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'order_sequence'];

    /**
     * Relasi ke Unit. Satu Chapter memiliki banyak Unit.
     */
    public function units()
    {
        return $this->hasMany(Unit::class)->orderBy('order_sequence');
    }
}
