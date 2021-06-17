<?php

namespace App\Http\Controllers\Almacenes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Auth\User;
use App\Mantenimiento\Tabla\General;
use App\Compras\Articulo;
use App\Almacenes\NotaIngresoArticulo;
use App\Compras\LoteArticulo;
use App\Almacenes\DetalleNotaIngresoArticulo;
use App\Almacenes\MovimientoNotaArticulo;
use App\Exports\NotaIngresoArticulo\NotaArticuloMultisheet;
use App\Imports\NotaIngresoArticulo\NotaArticuloImportMultiSheet;
use App\Imports\NotaIngresoArticulo\NotaArticuloSheet;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Exception;

class NotaIngresoArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('almacenes.nota_ingresos_articulos.index');
    }
    public function gettable()
    {
        $data = DB::table("nota_ingreso_articulo as n")
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
        $ngenerado = $fecha . (DB::table('nota_ingreso')->count() + 1);
        $usuarios = User::get();
        $articulos = Articulo::where('estado', 'ACTIVO')->get();
        return view('almacenes.nota_ingresos_articulos.create', [
            "fecha_hoy" => $fecha_hoy,
            "origenes" => $origenes, 'destinos' => $destinos,
            'ngenerado' => $ngenerado, 'usuarios' => $usuarios,
            'articulos' => $articulos
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
            'notadetalle_tabla.required' => 'No hay Articulos',
        ];

        Validator::make($data, $rules, $message)->validate();

        //$registro_sanitario = new RegistroSanitario();
        $notaingreso = new NotaIngresoArticulo();
        $notaingreso->numero = $request->get('numero');
        $notaingreso->fecha = $request->get('fecha');
        $destino = DB::table('tabladetalles')->where('id', $request->destino)->first();
        $notaingreso->destino = $destino->descripcion;
        $origen = DB::table('tabladetalles')->where('id', $request->origen)->first();
        $notaingreso->origen = $origen->descripcion;
        $notaingreso->usuario = Auth()->user()->usuario;
        $notaingreso->save();

        $articulosJSON = $request->get('notadetalle_tabla');
        $notatabla = json_decode($articulosJSON[0]);

        foreach ($notatabla as $fila) {

            $consulta = DB::table("lote_articulos")->where('lote', $fila->lote)->where('articulo_id', $fila->articulo_id);
            if ($consulta->count() != 0) {
                $loteid = $consulta->first()->id;
                $lote_articulo = LoteArticulo::where('id', $loteid)->first();
                $lote_articulo->cantidad = $lote_articulo->cantidad + $fila->cantidad;
                $lote_articulo->cantidad_logica = $lote_articulo->cantidad_logica + $fila->cantidad;
                $lote_articulo->save();
            } else {
                $articulo = Articulo::findOrFail($fila->articulo_id);
                $lote_articulo = new LoteArticulo();
                $lote_articulo->detalle_id = 0;
                $lote_articulo->lote = $fila->lote;
                $lote_articulo->articulo_id = $articulo->id;
                $lote_articulo->codigo_articulo = "-";
                $lote_articulo->descripcion_articulo = $articulo->descripcion;
                $lote_articulo->cantidad = $fila->cantidad;
                $lote_articulo->cantidad_logica = $fila->cantidad;
                $lote_articulo->fecha_vencimiento = $fila->fechavencimiento;
                $lote_articulo->estado = "1";
                $lote_articulo->save();
            }
            DetalleNotaIngresoArticulo::create([
                'nota_ingreso_articulo_id' => $notaingreso->id,
                'lote' => $lote_articulo->id,
                'cantidad' => $fila->cantidad,
                'articulo_id' => $fila->articulo_id,
                'fecha_vencimiento' => $fila->fechavencimiento
            ]);
            MovimientoNotaArticulo::create([
                'cantidad' => $fila->cantidad,
                'observacion' => $articulo->descripcion,
                'movimiento' => "INGRESO",
                'lote_id' => $lote_articulo->id,
                'usuario_id' => Auth()->user()->id,
                'nota_id' => $notaingreso->id,
                'articulo_id' => $fila->articulo_id,
            ]);
        }

        //Registro de actividad
        $descripcion = "SE AGREGÓ LA NOTA DE INGRESO ";
        $gestion = "ALMACEN / NOTA INGRESO";
        crearRegistro($notaingreso, $descripcion, $gestion);


        Session::flash('success', 'NOTA DE INGRESO');
        return redirect()->route('almacenes.nota_ingreso_articulo.index')->with('guardar', 'success');
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
        $notaingreso = NotaIngresoArticulo::findOrFail($id);
        $data = array();
        $detallenotaingreso = DB::table('detalle_nota_ingreso_articulo')->where('nota_ingreso_articulo_id', $notaingreso->id)->get();
        foreach ($detallenotaingreso as $fila) {
            $lote = DB::table('lote_articulos')->where('id', $fila->lote)->first();
            $articulo = DB::table('articulos')->where('id', $fila->articulo_id)->first();
            array_push($data, array(
                'articulo_id' => $fila->articulo_id,
                'cantidad' => $fila->cantidad,
                'lote' => $lote->lote,
                'articulo' => $articulo->descripcion,
                'fechavencimiento' => $fila->fecha_vencimiento,
            ));
        }
        $origenes =  General::find(28)->detalles;
        $destinos =  General::find(29)->detalles;
        $lotes = DB::table('lote_articulos')->get();
        $usuarios = User::get();
        $articulos = Articulo::where('estado', 'ACTIVO')->get();
        return view('almacenes.nota_ingresos_articulos.edit', [
            "origenes" => $origenes, 'destinos' => $destinos,
            'usuarios' => $usuarios,
            'articulos' => $articulos, 'lotes' => $lotes, 'notaingreso' => $notaingreso, 'detalle' => json_encode($data)
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
        $notaingreso = NotaIngresoArticulo::findOrFail($id);
        $notaingreso->fecha = $request->get('fecha');
        $destino = DB::table('tabladetalles')->where('id', $request->destino)->first();
        $notaingreso->destino = $destino->descripcion;
        $origen = DB::table('tabladetalles')->where('id', $request->origen)->first();
        $notaingreso->origen = $origen->descripcion;
        $notaingreso->usuario = Auth()->user()->usuario;
        $notaingreso->update();

        $articulosJSON = $request->get('notadetalle_tabla');
        $notatabla = json_decode($articulosJSON[0]);

        if ($notatabla != "") {
            DetalleNotaIngresoArticulo::where('nota_ingreso_articulo_id', $notaingreso->id)->delete();
            foreach ($notatabla as $fila) {
                $loteid = 0;
                $articulo = Articulo::findOrFail($fila->articulo_id);
                $consulta = DB::table("lote_articulos")->where('lote', $fila->lote)->where('articulo_id', $fila->articulo_id);
                if ($consulta->count() != 0) {
                    $loteid = $consulta->first()->id;
                    $lote_articulo = LoteArticulo::findOrFail($loteid);
                    $consulta_movimiento = DB::table("movimiento_nota_articulo")->where('lote_id', $loteid)->where('articulo_id', $fila->articulo_id)
                        ->where('nota_id', $id)->where('movimiento', 'INGRESO');
                    if ($consulta_movimiento->count() != 0) {
                        $cantidadmovimiento = $consulta_movimiento->first()->cantidad;
                        $lote_articulo->cantidad = ($lote_articulo->cantidad - $cantidadmovimiento) + $fila->cantidad;
                        $lote_articulo->cantidad_logica = ($lote_articulo->cantidad_logica - $cantidadmovimiento) + $fila->cantidad;
                        $lote_articulo->save();
                    } else {

                        $lote_articulo->cantidad = $lote_articulo->cantidad + $fila->cantidad;
                        $lote_articulo->cantidad_logica = $lote_articulo->cantidad_logica + $fila->cantidad;
                        $lote_articulo->save();
                    }
                } else {
                    $lote_articulo = new LoteArticulo();
                    $lote_articulo->detalle_id = 0;
                    $lote_articulo->lote = $fila->lote;
                    $lote_articulo->articulo_id = $articulo->id;
                    $lote_articulo->codigo_articulo = "-";
                    $lote_articulo->descripcion_articulo = $articulo->descripcion;
                    $lote_articulo->cantidad = $fila->cantidad;
                    $lote_articulo->cantidad_logica = $fila->cantidad;
                    $lote_articulo->fecha_vencimiento = $fila->fechavencimiento;
                    $lote_articulo->estado = "1";
                    $lote_articulo->save();
                }
                DetalleNotaIngresoArticulo::create([
                    'nota_ingreso_articulo_id' => $id,
                    'lote' => $lote_articulo->id,
                    'cantidad' => $fila->cantidad,
                    'articulo_id' => $fila->articulo_id,
                    'fecha_vencimiento' => $fila->fechavencimiento
                ]);

                $articuloactualizacion = Articulo::findOrFail($fila->articulo_id);
                $articuloactualizacion->stock = $fila->cantidad;
                $articuloactualizacion->update();


                MovimientoNotaArticulo::where('lote_id', $lote_articulo->id)->where('articulo_id', $fila->articulo_id)->where('nota_id', $id)->where('movimiento', 'INGRESO')->delete();
                MovimientoNotaArticulo::create([
                    'cantidad' => $fila->cantidad,
                    'observacion' => $articulo->descripcion,
                    'movimiento' => "INGRESO",
                    'lote_id' => $lote_articulo->id,
                    'usuario_id' => Auth()->user()->id,
                    'articulo_id' => $fila->articulo_id,
                    'nota_id' => $id,
                ]);
            }
        }






        //Registro de actividad
        $descripcion = "SE ACTUALIZO NOTA DE INGRESO DE ARTICULO";
        $gestion = "ALMACEN / NOTA INGRESO DE ARTICULO";
        crearRegistro($notaingreso, $descripcion, $gestion);


        Session::flash('success', 'NOTA DE INGRESO');
        return redirect()->route('almacenes.nota_ingreso_articulo.index')->with('guardar', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notaingreso=NotaIngresoArticulo::findOrFail($id);
        $notaingreso->estado="ANULADO";
        $notaingreso->save();
        Session::flash('success','NOTA DE INGRESO DE ARTICULO');
        return redirect()->route('almacenes.nota_ingreso_articulo.index')->with('guardar', 'success');
    }
    public function getDownload(){
        ob_end_clean(); // this
        ob_start();
        return  Excel::download(new NotaArticuloMultisheet,'modelo_nota_articulo.xlsx');
    }
    public function uploadnotaingreso(Request $request)
    {

        $data=array();
        $file=$request->file();
        $archivo=$file['files'][0];
        $objeto=new NotaArticuloSheet();
        Excel::import($objeto,$archivo);

        $datos= $objeto->get_data();

        try
        {
            Excel::import(new NotaArticuloImportMultiSheet,$archivo);

        }
        catch (\Maatwebsite\Excel\Validators\ValidationException $e) {

            $failures = $e->failures();

            foreach ($failures as $failure) {
                array_push($data,array(
                    "fila"=>$failure->row(),
                    "atributo"=>$failure->attribute(),
                    "error"=>$failure->errors()
                ));

            }
            array_push($data,array("excel"=>$datos));

        }
        catch (Exception $er)
        {
            //Log::info($er);
        }

        return json_encode($data);

    }
}
