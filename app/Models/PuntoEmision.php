<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Eloquent\SoftDeletes;

class PuntoEmision extends Model
{
    use HasFactory, SoftDeletes;

    protected $collection = 'sis_ptoemision';
    protected $primaryKey = '_id';

    function establecimiento() {
        return $this->belongsTo(Establecimiento::class, 'establecimiento_id');
    }

    function contribuyente() {
        return $this->belongsTo(Contribuyente::class, 'contribuyente_id');
    }
}
