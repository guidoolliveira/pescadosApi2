<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
        'lot',
        'validity'
    ];

    protected $dates = ['validity'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function historico()
    {
        return $this->hasMany(EstoqueHistorico::class);
    }
}

