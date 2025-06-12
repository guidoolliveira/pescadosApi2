<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Biometria extends Model
{
    use HasFactory;

    protected $fillable = [
        'weight',
        'quantity',
        'date',
        'description', 
        'image',
        'viveiro_id',
        'cultivo_id',
        'shrimp_weight'
    ];

    public function viveiro()
    {
        return $this->belongsTo(Viveiro::class);
    }
    public function cultivos()
    {
        return $this->belongsTo(Cultivo::class);
    }
    
}
