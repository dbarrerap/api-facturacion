<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// use MongoDB\Laravel\Eloquent\Model;
// use MongoDB\Laravel\Eloquent\SoftDeletes;

class Proveedor extends Model
{
    use HasFactory, SoftDeletes;
    protected $primaryKey = '_id';

    // protected $collection = 'inv_proveedores';
    protected $table = 'inv_proveedores';

    function productos() {
        return $this->hasMany(Producto::class);
    }
}
