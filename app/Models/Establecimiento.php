<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Eloquent\SoftDeletes;

class Establecimiento extends Model
{
    use HasFactory, SoftDeletes;

    protected $collection = 'sis_establecimiento';
    protected $primaryKey = '_id';

    function contribuyente() {
        return $this->belongsTo(Contribuyente::class, 'contribuyente_id');
    }
}
