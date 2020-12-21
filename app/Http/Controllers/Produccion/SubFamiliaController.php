<?php

namespace App\Http\Controllers\Produccion;

use App\Http\Controllers\Controller;
use App\Produccion\Familia;
use App\Produccion\SubFamilia;
use Illuminate\Http\Request;

class SubFamiliaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getByFamilia(Request $request)
    {
        $error = false;
        $message = "";
        $data= null;
        $collection = collect([]);

        if (!is_null($request->familia_id)) {
            $familia = Familia::findOrFail($request->familia_id);
            foreach ($familia->sub_familias as $sub_familia) {
                $collection->push([
                    'id' => $sub_familia->id,
                    'text' => $sub_familia->descripcion
                ]);
            }
        } else {
            $error = true;
            $message = "Error interno del servidor";
        }

        $data = [
            'error' => $error,
            'message' => $message,
            'sub_familias' => $collection
        ];

        return response()->json($data);
    }
}
