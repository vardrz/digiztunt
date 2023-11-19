<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balita extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function posyandu()
    {
        return $this->belongsTo(Posyandu::class, 'posyandu', 'id');
    }

    public function pelayanan()
    {
        return $this->hasMany(Pelayanan::class, 'id_balita', 'id');
    }
}
