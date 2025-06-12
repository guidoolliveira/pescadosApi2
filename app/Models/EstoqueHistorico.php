<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstoqueHistorico extends Model
{
    use HasFactory;
    protected $fillable = [
        'estoque_id',
        'quantidade_anterior',
        'quantidade_utilizada'
    ];

    public function entry()
    {
        return $this->belongsTo(Estoque::class, 'estoque_id');
    }
}

