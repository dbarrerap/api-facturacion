<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// use MongoDB\Laravel\Eloquent\Model;
// use MongoDB\Laravel\Eloquent\SoftDeletes;

class Empleado extends Model
{
    use HasFactory, SoftDeletes;

    // protected $collection = 'sis_empleados';
    protected $table = 'sis_empleados';
    protected $primaryKey = '_id';

    protected $guarded = [];

    protected function nombreCompleto(): Attribute {
        // return "{$this->nombres} {$this->apellidos}";
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => "{$attributes['nombres']} {$attributes['apellidos']}"
        );
    }

}
