<?php

namespace App\Http\Controllers\Mantenimiento\Empleado;

use App\Mantenimiento\Empleado\Empleado;
use Carbon\Carbon;
use DataTables;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function index()
    {
        return view('mantenimiento.empleados.index');
    }

    public function getTable()
    {
        $empleados = Empleado::all();
        $coleccion = collect([]);
        foreach($empleados as $empleado){
            $coleccion->push([
                'id' => $empleado->id,
                'documento' => $empleado->persona->getDocumento(),
                'apellidos_nombres' => $empleado->persona->getApellidosYNombres(),
                'cargo' => $empleado->cargo,
                'estado' => $empleado->estado,
            ]);
        }
        return DataTables::of($coleccion)->toJson();
    }

    public function create()
    {
        return view('mantenimiento.empleados.create');
    }

    public function store()
    {

    }

    public function edit()
    {

    }

    public function update()
    {

    }

    public function active()
    {

    }

    public function deactivate()
    {

    }
}
