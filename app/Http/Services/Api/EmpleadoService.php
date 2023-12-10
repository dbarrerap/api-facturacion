<?php

namespace App\Http\Services\Api;

use App\Models\Empleado;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EmpleadoService {
    function getEmpleados(array $filter, int|null $perPage, int $page) {
        $empleados = Empleado::query()
        ->where('contribuyente_id', $filter['contribuyente']['_id'])
        ->when(
            isset($filter['nombres']),
            fn ($q) => $q->where('nombres', 'like', '%' . $filter['nombres'] . '%')
        )
        ->when(
            isset($filter['apellidos']),
            fn ($q) => $q->where('apellidos', 'like', '%' . $filter['apellidos'] . '%')
        )
        ->when(
            isset($filter['numero_documento']),
            fn ($q) => $q->where('numero_documento', 'like', '%' . $filter['numero_documento'] . '%')
        )
        ->when(
            isset($filter['estado']),
            fn ($q) => $q->where('estado', $filter['estado'])
        );

        return (!is_null($perPage))
            ? $empleados->paginate($perPage, ['*'], 'page', $page)
            : $empleados->get();
    }

    function setEmpleado(array $data, array $contribuyente, array $establecimiento) {
        DB::beginTransaction();
        try {
            $empleado = new Empleado();
            $empleado->contribuyente_id = $contribuyente['_id'];
            $empleado->establecimiento_id = $establecimiento['_id'];
            // $empleado->user_id = $user['_id'];
            $empleado->nombres = $data['nombres'];
            $empleado->apellidos = $data['apellidos'];
            $empleado->tipo_documento = $data['tipo_documento'];
            $empleado->numero_documento = $data['numero_documento'];
            if(isset($data['nacionalidad'])) $empleado->nacionalidad = $data['nacionalidad'];
            $empleado->direccion = $data['direccion'];
            $empleado->fecha_nacimiento = $data['fecha_nacimiento'];
            $empleado->telefono_fijo = $data['telefono_fijo'] ?? null;
            $empleado->telefono_movil = $data['telefono_movil'] ?? null;
            $empleado->correo = $data['correo'] ?? null;
            $empleado->discapacitado = $data['discapacitado'];
            $empleado->tipo_discapacidad = $data['tipo_discapacidad'] ?? null;
            $empleado->porcentaje_discapacidad = $data['porcentaje_discapacidad'] ?? null;
            $empleado->save();

            $user = new User([
                'name' => $empleado->nombre_completo,
                'email' => $empleado->correo,
                'password' => bcrypt($data['password'])
            ]);

            $empleado->user()->save($user);
            DB::commit();
            //
            return $empleado;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \Exception($th->getMessage());
        }
    }

    function getEmpleado(string $id) {
        try {
            $cliente = Empleado::findOrFail($id);
            //
            return $cliente;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    function updateEmpleado(string $id, array $data) {
        try {
            $empleado = Empleado::findOrFail($id);
            $empleado->nombres = $data['nombres'];
            $empleado->apellidos = $data['apellidos'];
            $empleado->fecha_nacimiento = $data['fecha_nacimiento'];
            $empleado->telefono_fijo = $data['telefono_fijo'] ?? null;
            $empleado->telefono_movil = $data['telefono_movil'] ?? null;
            $empleado->email = $data['email'] ?? null;
            $empleado->discapacitado = $data['discapacitado'];
            $empleado->tipo_discapacidad = $data['tipo_discapacidad'] ?? null;
            $empleado->porcentaje_discapacidad = $data['porcentaje_discapacidad'] ?? null;
            $empleado->save();
            //
            return $empleado;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    function deleteEmpleado(string $id) {
        try {
            $empleado = Empleado::findOrFail($id);
            $empleado->delete();
            //
            return $empleado;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }
}