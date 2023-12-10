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

    protected function nombre_completo(): Attribute {
        // return "{$this->nombres} {$this->apellidos}";
        return Attribute::make(
            get: fn($model) => "{$model->nombres} {$model->apellidos}"
        );
    }

}
