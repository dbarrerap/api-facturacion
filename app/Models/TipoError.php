<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Eloquent\SoftDeletes;

class TipoError extends Model
{
    use HasFactory, SoftDeletes;

    protected $collection = 'fe_errores';
    protected $primaryKey = '_id';
}
