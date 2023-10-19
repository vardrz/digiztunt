<?php

namespace App\Models;

// use App\Models\Balita;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pelayanan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public $timestamps = false;

    public function balita()
    {
        return $this->belongsTo(Balita::class, 'nik_balita', 'nik');
    }
}
