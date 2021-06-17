<?php

namespace App\Http\Controllers\Almacenes;

use App\Almacenes\DetalleNotaSalidadArticulo;
use App\Almacenes\MovimientoNotaArticulo;
use App\Almacenes\NotaSalidadArticulo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Mantenimiento\Tabla\General;
use App\Compras\Articulo;
use App\Compras\LoteArticulo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class NotaSalidadArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('almacenes.nota_salidad_articulos.index');
    }
    public function gettable()
    {
        $data = DB::table("nota_salidad_articulo as n")
            ->select('n.*',)->where('n.estado', 'ACTIVO')->get();
        return Datatables::of($data)->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fecha_hoy = Carbon::now()->toDateString();
        $fecha = Carbon::createFromFormat('Y-m-d', $fecha_hoy);
        $fecha = str_replace("-", "", $fecha);
        $fecha = str_replace(" ", "", $fecha);
        $fecha = str_replace(":", "", $fecha);
        $origenes =  General::find(28)->detalles;
        $destinos =  General::find(29)->detalles;
        $lotes = DB::table('lote_articulos')->get();
        $ngenerado = $fecha . (DB::table('nota_salidad')->count() + 1);
        $articulos = Articulo::where('estado', 'ACTIVO')->get();
        return view('almacenes.nota_salidad_articulos.create', [
            "fecha_hoy" => $fecha_hoy,
            "origenes" => $origenes, 'destinos' => $destinos,
            'ngenerado' => $ngenerado,
            'articulos' => $articulos, 'lotes' => $lotes
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request;
        $data = $request->all();
        $rules = [
            'fecha' => 'required',
            'destino' => 'required',
            'origen' => 'required',
            'notadetalle_tabla' => 'required',
        ];
        $message = [
            'fecha.required' => 'El campo fecha  es Obligatorio',
            'destino.required' => 'El campo destino  es Obligatorio',
            'origen.required' => 'El campo origen  es Obligatorio',
            'notadetalle_tabla.required' => 'No hay dispositivos',
        ];
        Validator::make($data, $rules, $message)->validate();
        $notasalidad = new NotaSalidadArticulo();
        $notasalidad->numero = $request->get('numero');
        $notasalidad->fecha = $request->get('fecha');
        $destino = DB::table('tabladetalles')->where('id', $request->destino)->first();
        $notasalidad->destino = $destino->descripcion;
        $notasalidad->origen = $request->origen;
        $notasalidad->usuario = Auth()->user()->usuario;
        $notasalidad->save();
        $articulosJSON = $request->get('notadetalle_tabla');
        $notatabla = json_decode($articulosJSON[0]);
        foreach ($notatabla as $fila) {
            DetalleNotaSalidadArticulo::create([
                'nota_salidad_id' => $notasalidad->id,
                'lote_id' => $fila->lote_id,
                'cantidad' => $fila->cantidad,
                'articulo_id' => $fila->articulo_id,
            ]);
            $articulo = DB::table('articulos')->where('id', $fila->articulo_id)->first();
            MovimientoNotaArticulo::create([
                'cantidad' => $fila->cantidad,
                'observacion' => $articulo->descripcion,
                'movimiento' => "SALIDA",
                'lote_id' => $fila->lote_id,
                'usuario_id' => Auth()->user()->id,
                'nota_id' => $notasalidad->id,
                'articulo_id' => $fila->articulo_id,
            ]);
            $lote_articulo = LoteArticulo::findOrFail($fila->lote_id);
            $lote_articulo->cantidad = $lote_articulo->cantidad - $fila->cantidad;
            $lote_articulo->cantidad_logica = $lote_articulo->cantidad_logica - $fila->cantidad;
            $lote_articulo->save();
            $articuloU = Articulo::findOrFail($fila->articulo_id);
            $articuloU->stock = $articuloU->stock - $fila->cantidad;
            $articuloU->save();
        }
        $descripcion = "SE AGREGÓ LA NOTA DE SALIDAD DE ARTICULO";
        $gestion = "ALMACEN / NOTA SALIDAD";
        crearRegistro($notasalidad, $descripcion, $gestion);
        Session::flash('success', 'NOTA DE SALIDAD DE ARTICULO');
        return redirect()->route('almacenes.nota_salidad_articulo.index')->with('guardar', 'success');
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
        $notasalidad = NotaSalidadArticulo::findOrFail($id);
        $data = array();
        $detallenotasalidad = DB::table('detalle_nota_salidad_articulo')->where('nota_salidad_id', $notasalidad->id)->get();
        foreach ($detallenotasalidad as $fila) {
            $lote = DB::table('lote_articulos')->where('id', $fila->lote_id)->first();
            $articulo = DB::table('articulos')->where('id', $fila->articulo_id)->first();
            array_push($data, array(
                'articulo_id' => $fila->articulo_id,
                'cantidad' => $fila->cantidad,
                'lote' => $lote->lote,
                'articulo' => $articulo->descripcion,
                'lote_id' => $fila->lote_id
            ));
        }
        $origenes =  General::find(28)->detalles;
        $destinos =  General::find(29)->detalles;
        $lotes = DB::table('lote_articulos')->get();
        $articulos = Articulo::where('estado', 'ACTIVO')->get();
        return view('almacenes.nota_salidad_articulos.edit', [
            "origenes" => $origenes, 'destinos' => $destinos,
            'articulos' => $articulos, 'lotes' => $lotes, 'notasalidad' => $notasalidad, 'detalle' => json_encode($data)
        ]);
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
        //return $request;
        $data = $request->all();
        $rules = [
            'fecha' => 'required',
            'destino' => 'required',
            'origen' => 'required',
            'notadetalle_tabla' => 'required',
        ];
        $message = [
            'fecha.required' => 'El campo fecha  es Obligatorio',
            'destino.required' => 'El campo destino  es Obligatorio',
            'origen.required' => 'El campo origen  es Obligatorio',
            'notadetalle_tabla.required' => 'No hay dispositivos',
        ];
        Validator::make($data, $rules, $message)->validate();
        //$registro_sanitario = new RegistroSanitario();
        $notasalidad = NotaSalidadArticulo::findOrFail($id);
        $notasalidad->fecha = $request->get('fecha');
        $destino = DB::table('tabladetalles')->where('id', $request->destino)->first();
        $notasalidad->destino = $destino->descripcion;
        $notasalidad->usuario = Auth()->user()->usuario;
        $notasalidad->update();
        $articulosJSON = $request->get('notadetalle_tabla');
        $notatabla = json_decode($articulosJSON[0]);
        if ($notatabla != "") {
            DetalleNotaSalidadArticulo::where('nota_salidad_id', $notasalidad->id)->delete();
            foreach ($notatabla as $fila) {
                DetalleNotaSalidadArticulo::create([
                    'nota_salidad_id' => $id,
                    'lote_id' => $fila->lote_id,
                    'cantidad' => $fila->cantidad,
                    'articulo_id' => $fila->articulo_id,
                ]);
                $lote_articulo = LoteArticulo::findOrFail($fila->lote_id);
                $cantidadmovimiento = DB::table("movimiento_nota_articulo")->where('lote_id', $fila->lote_id)->where('articulo_id', $fila->articulo_id)->where('nota_id', $id)->where('movimiento', 'SALIDA')->first()->cantidad;
                $lote_articulo->cantidad = ($lote_articulo->cantidad + $cantidadmovimiento) - $fila->cantidad;
                $lote_articulo->cantidad_logica = ($lote_articulo->cantidad + $cantidadmovimiento)- $fila->cantidad;
                $lote_articulo->save();
                $articuloU = Articulo::findOrFail($fila->articulo_id);
                $articuloU->stock = ($articuloU->stock + $cantidadmovimiento) - $fila->cantidad;
                $articuloU->save();

                MovimientoNotaArticulo::where('lote_id', $fila->lote_id)->where('articulo_id', $fila->articulo_id)->where('nota_id', $id)->where('movimiento', 'SALIDA')->delete();
                $articulo = DB::table('articulos')->where('id', $fila->articulo_id)->first();
                MovimientoNotaArticulo::create([
                    'cantidad' => $fila->cantidad,
                    'observacion' => $articulo->descripcion,
                    'movimiento' => "SALIDA",
                    'lote_id' => $fila->lote_id,
                    'usuario_id' => Auth()->user()->id,
                    'articulo_id' => $fila->articulo_id,
                    'nota_id' => $id,
                ]);
            }
        }

        $descripcion = "SE AGREGÓ LA NOTA DE SALIDAD ";
        $gestion = "ALMACEN / NOTA SALIDAD";
        crearRegistro($notasalidad, $descripcion, $gestion);
        Session::flash('success', 'NOTA DE SALIDAD');
        return redirect()->route('almacenes.nota_salidad_articulo.index')->with('guardar', 'success');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notasalidad=NotaSalidadArticulo::findOrFail($id);
        $notasalidad->estado="ANULADO";
        $notasalidad->save();
        Session::flash('success','NOTA DE SALIDAD DE ARTICULO');
        return redirect()->route('almacenes.nota_salidad_articulo.index')->with('guardar', 'success');
    }
    public function getLot()
    {
        return datatables()->query(
            DB::table('lote_articulos')
                ->join('articulos', 'articulos.id', '=', 'lote_articulos.articulo_id')
                ->join('categorias', 'categorias.id', '=', 'articulos.categoria_id')
                ->select('lote_articulos.id', 'articulos.id as articulo_id', 'lote_articulos.lote', 'articulos.descripcion as nombre', 'articulos.codigo_fabrica', 'articulos.stock', 'articulos.precio_compra', 'articulos.presentacion', 'categorias.descripcion as categoria')
                ->where('lote_articulos.cantidad_logica', '>', 0)
                ->where('lote_articulos.estado', '1')
                ->orderBy('lote_articulos.id', 'ASC')
                ->where('articulos.estado', 'ACTIVO')
        )->toJson();
    }
}
