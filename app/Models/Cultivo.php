<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cultivo extends Model
{
    use HasFactory;

    protected $fillable = [
        'viveiro_id',
        'data_inicio',
        'data_fim',
        'status',
        'quantidade_camarao',
    ];

    public function viveiro()
    {
        return $this->belongsTo(Viveiro::class);
    }

    public function usoDiarios()
    {
        return $this->hasMany(UsoDiario::class);
    }
    public function biometrias()
{
    return $this->hasMany(Biometria::class);
}
}
