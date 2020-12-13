<?php

namespace App\Http\Controllers\Mantenimiento\Ubigeo;

use App\Mantenimiento\Ubigeo\Departamento;
use App\Mantenimiento\Ubigeo\Provincia;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UbigeoController extends Controller
{
    public function provincias(Request $request)
    {
        $error = false;
        $message = "";
        $data= null;

        if (!is_null($request->departamento_id))
        {
            // Castear por seguridad
            $departamento_id = str_pad($request->departamento_id, 2, "0", STR_PAD_LEFT);
            $provincias = Departamento::find($departamento_id)->provincias;
            if (is_null($provincias)) {
                $error = true;
                $message = "Error interno del servidor";
            } else {
                $collection = collect([]);
                foreach ($provincias as $provincia) {
                    $collection->push([
                        'id' => $provincia->id,
                        'text' => $provincia->nombre
                    ]);
                }
            }
        } else {
            $error = true;
            $message = "Error interno del servidor";
        }

        $data = [
            'error' => $error,
            'message' => $message,
            'provincias' => $collection
        ];

        return response()->json($data);
    }

    public function distritos(Request $request)
    {
        $error = false;
        $message = "";
        $data= null;

        if (!is_null($request->provincia_id))
        {
            // Castear por seguridad
            $provincia_id = str_pad($request->provincia_id, 4, "0", STR_PAD_LEFT);
            $distritos = Provincia::find($provincia_id)->distritos;
            if (is_null($distritos)) {
                $error = true;
                $message = "Error interno del servidor";
            } else {
                $collection = collect([]);
                foreach ($distritos as $distrito) {
                    $collection->push([
                        'id' => $distrito->id,
                        'text' => $distrito->nombre
                    ]);
                }
            }
        } else {
            $error = true;
            $message = "Error interno del servidor";
        }

        $data = [
            'error' => $error,
            'message' => $message,
            'distritos' => $collection
        ];

        return response()->json($data);
    }
}
