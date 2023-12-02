<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class Catalogo extends Model
{
    use HasFactory;
    protected $primaryKey = '_id';

    protected $collection = 'sis_catalogo';
}
