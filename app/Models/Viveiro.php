<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Viveiro extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'width',
        'length',
        'area'
    ]; 

    public function biometrias()
    {
        return $this->hasMany(Biometria::class);
    }
    public function cultivos()
    {
        return $this->hasMany(Cultivo::class);
    }
    public function getCultivoAtivoAttribute()
    {
        return $this->cultivos()->where('status', 1)->first();
    }
    // App\Models\Viveiro.php

    public function latestBiometria()
    {
        return $this->hasOne(Biometria::class)->latest('date')->latest('id');
    }


}
