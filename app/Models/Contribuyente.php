<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Eloquent\SoftDeletes;

class Contribuyente extends Model
{
    use HasFactory, SoftDeletes;

    protected $collection = 'sis_contribuyente';
    protected $primaryKey = '_id';

    function establecimientos() {
        return $this->hasMany(Establecimiento::class, 'contribuyente_id');
    }
}
