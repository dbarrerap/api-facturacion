<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Eloquent\SoftDeletes;

class Factura extends Model
{
    use HasFactory, SoftDeletes;

    protected $collection = 'fac_factura';
    protected $primaryKey = '_id';

    function contribuyente() {
        return $this->belongsTo(Contribuyente::class);
    }

    function establecimiento() {
        return $this->belongsTo(Establecimiento::class, 'establecimiento', 'numero');
    }

    function punto_emision() {
        return $this->belongsTo(PuntoEmision::class, 'punto_emision', 'numero');
    }

    function detalles() {
        return $this->hasMany(FacturaDetalle::class);
    }

    function cliente() {
        return $this->belongsTo(Cliente::class);
    }
}
