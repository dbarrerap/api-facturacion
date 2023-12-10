<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// use MongoDB\Laravel\Eloquent\Model;
// use MongoDB\Laravel\Eloquent\SoftDeletes;

class Contribuyente extends Model
{
    use HasFactory, SoftDeletes;

    // protected $collection = 'sis_contribuyentes';
    protected $table = 'sis_contribuyentes';
    protected $primaryKey = '_id';

    protected $guarded = [];

    function establecimientos() {
        return $this->hasMany(Establecimiento::class, 'contribuyente_id');
    }

    function user() {
        return $this->morphOne(User::class, 'userable');
    }
}
