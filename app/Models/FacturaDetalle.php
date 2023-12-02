<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Eloquent\SoftDeletes;

class FacturaDetalle extends Model
{
    use HasFactory, SoftDeletes;

    protected $collection = 'fac_factura_detalle';
    protected $primaryKey = '_id';

    function factura() {
        return $this->belongsTo(Factura::class, 'factura_id');
    }
}
