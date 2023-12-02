<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;
    protected $collection = 'com_clientes';
    protected $primaryKey = '_id';
}
