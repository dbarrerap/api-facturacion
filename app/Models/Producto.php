<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// use MongoDB\Laravel\Eloquent\Model;
// use MongoDB\Laravel\Eloquent\SoftDeletes;

class Producto extends Model
{
    use HasFactory, SoftDeletes;
    protected $primaryKey = '_id';

    // protected $collection = 'inv_productos';
    protected $table = 'inv_productos';

    function proveedor() {
        return $this->belongsTo(Proveedor::class);
    }
}
