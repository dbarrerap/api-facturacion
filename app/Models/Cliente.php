<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// use MongoDB\Laravel\Eloquent\Model;
// use MongoDB\Laravel\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;
    protected $primaryKey = '_id';
    
    // protected $collection = 'com_clientes';
    protected $table = 'com_clientes';

    function facturas() {
        return $this->hasMany(Factura::class);
    }
}
