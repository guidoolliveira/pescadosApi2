<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsoDiario extends Model
{
    use HasFactory;

    protected $fillable = [
        'cultivo_id',
        'viveiro_id',
        'produto_id',
        'data',
        'quantidade_utilizada',
        'observacoes',
    ];

    public function cultivo()
    {
        return $this->belongsTo(Cultivo::class);
    }

    public function viveiro()
    {
        return $this->belongsTo(Viveiro::class);
    }

    public function produto()
    {
        return $this->belongsTo(Product::class);
    }
}
