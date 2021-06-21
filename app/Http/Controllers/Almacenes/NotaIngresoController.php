<?php

namespace App\Http\Controllers\Almacenes;

use App\Almacenes\DetalleNotaIngreso;
use App\Almacenes\LoteProducto;
use App\Almacenes\MovimientoNota;
use App\Http\Controllers\Controller;
use App\Mantenimiento\Parametro\Parametro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Carbon;
use App\Mantenimiento\Tabla\General;
use App\User;
use App\Almacenes\Producto;
use App\Almacenes\NotaIngreso;
use App\Exports\ErrorExcel;
use App\Exports\ModeloExport;
use App\Exports\ProductosExport;
use App\Exports\UsersExport;
use App\Imports\DataExcel;
use App\Imports\NotaIngreso as ImportsNotaIngreso;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Symfony\Component\VarDumper\Cloner\Data;

class NotaIngresoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('almacenes.nota_ingresos.index');
    }
    public function gettable()
    {
        $data = DB::table("nota_ingreso as n")
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
        $lotes = DB::table('lote_productos')->get();
        $ngenerado = $fecha . (DB::table('nota_ingreso')->count() + 1);
        $usuarios = User::get();
        $productos = Producto::where('estado', 'ACTIVO')->get();
        return view('almacenes.nota_ingresos.create', [
            "fecha_hoy" => $fecha_hoy,
            "origenes" => $origenes, 'destinos' => $destinos,
            'ngenerado' => $ngenerado, 'usuarios' => $usuarios,
            'productos' => $productos, 'lotes' => $lotes
        ]);
    }
    public function getProductos(Request $request)
    {
        $data = DB::table('lote_productos')->where('id', $request->lote_id)->get();
        return json_encode($data);
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
            'notadetalle_tabla.required' => 'No hay dispositivos',
        ];

        Validator::make($data, $rules, $message)->validate();


        //$registro_sanitario = new RegistroSanitario();
        $notaingreso = new NotaIngreso();
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
            $loteid = 0;
            if (DB::table("lote_productos")->where('codigo', $fila->lote)->where('producto_id', $fila->producto_id)->count() != 0) {
                $loteid = DB::table("lote_productos")->where('codigo', $fila->lote)->where('producto_id', $fila->producto_id)->first()->id;
                $lote_producto = LoteProducto::findOrFail($loteid);
                $lote_productocantidad = $lote_producto->cantidad + $fila->cantidad;
                $lote_productocantidad_logica = $lote_producto->cantidad_logica + $fila->cantidad;
                DB::update('update lote_productos set cantidad= ?,cantidad_logica = ? where id = ?', [$lote_productocantidad, $lote_productocantidad_logica, $loteid]);
            } else {
                $producto = Producto::findOrFail($fila->producto_id);
                DB::insert('insert into lote_productos(codigo,orden_id,producto_id,
                codigo_producto,descripcion_producto,cantidad,cantidad_logica,fecha_vencimiento,
                fecha_entrega,observacion,confor_almacen,confor_produccion,estado) values (?,?,?,?,?,?,?,?,?,?,?,?,?)', [
                    $fila->lote, 0, $fila->producto_id,
                    $producto->codigo, $producto->nombre, $fila->cantidad, $fila->cantidad, $fila->fechavencimiento, $request->get('fecha'),
                    "notaingreso:" . $origen->descripcion . "-" . $destino->descripcion, 1, 1, "1"
                ]);
                $loteid = DB::table("lote_productos")->where('codigo', $fila->lote)->first()->id;
            }
            DetalleNotaIngreso::create([
                'nota_ingreso_id' => $notaingreso->id,
                'lote' => $loteid,
                'cantidad' => $fila->cantidad,
                'producto_id' => $fila->producto_id,
                'fecha_vencimiento' => $fila->fechavencimiento
            ]);
            $producto = DB::table('productos')->where('id', $fila->producto_id)->first();
            MovimientoNota::create([
                'cantidad' => $fila->cantidad,
                'observacion' => $producto->nombre,
                'movimiento' => "INGRESO",
                'lote_id' => $loteid,
                'usuario_id' => Auth()->user()->id,
                'nota_id' => $notaingreso->id,
                'producto_id' => $fila->producto_id,
            ]);

            $productoU = Producto::findOrFail($fila->producto_id);
            $productoU->stock = $productoU->stock + $fila->cantidad;
            $productoU->update();
        }

        //Registro de actividad
        $descripcion = "SE AGREGÓ LA NOTA DE INGRESO ";
        $gestion = "ALMACEN / NOTA INGRESO";
        crearRegistro($notaingreso, $descripcion, $gestion);


        Session::flash('success', 'NOTA DE INGRESO');
        return redirect()->route('almacenes.nota_ingreso.index')->with('guardar', 'success');
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
        $notaingreso = NotaIngreso::findOrFail($id);
        $data = array();
        $detallenotaingreso = DB::table('detalle_nota_ingreso')->where('nota_ingreso_id', $notaingreso->id)->get();
        foreach ($detallenotaingreso as $fila) {
            $lote = DB::table('lote_productos')->where('id', $fila->lote)->first();
            $producto = DB::table('productos')->where('id', $fila->producto_id)->first();
            array_push($data, array(
                'producto_id' => $fila->producto_id,
                'cantidad' => $fila->cantidad,
                'lote' => $lote->codigo,
                'producto' => $producto->nombre,
                'fechavencimiento' => $fila->fecha_vencimiento,
            ));
        }
        $origenes =  General::find(28)->detalles;
        $destinos =  General::find(29)->detalles;
        $lotes = DB::table('lote_productos')->get();
        $usuarios = User::get();
        $productos = Producto::where('estado', 'ACTIVO')->get();
        return view('almacenes.nota_ingresos.edit', [
            "origenes" => $origenes, 'destinos' => $destinos,
            'usuarios' => $usuarios,
            'productos' => $productos, 'lotes' => $lotes, 'notaingreso' => $notaingreso, 'detalle' => json_encode($data)
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
        //   return $request;
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
        $notaingreso = NotaIngreso::findOrFail($id);
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
            DetalleNotaIngreso::where('nota_ingreso_id', $notaingreso->id)->delete();
            foreach ($notatabla as $fila) {
                $loteid = 0;
                $consulta=DB::table("lote_productos")->where('codigo', $fila->lote)->where('producto_id', $fila->producto_id);
                if ($consulta->count() != 0) {
                    $loteid = $consulta->first()->id;
                    $lote_producto = LoteProducto::findOrFail($loteid);
                    $consultaM=DB::table("movimiento_nota")->where('lote_id', $loteid)->where('producto_id', $fila->producto_id)->where('nota_id', $id)->where('movimiento', 'INGRESO');
                    if ($consultaM->count() != 0) {
                        $cantidadmovimiento =$consultaM->first()->cantidad;
                        $lote_productocantidad = $lote_producto->cantidad - $cantidadmovimiento;
                        $lote_productocantidad_logica = $lote_producto->cantidad - $cantidadmovimiento;
                        $lote_productocantidad = $lote_productocantidad + $fila->cantidad;
                        $lote_productocantidad_logica = $lote_productocantidad_logica + $fila->cantidad;
                        DB::update('update lote_productos set cantidad= ?,cantidad_logica = ? where id = ?', [$lote_productocantidad, $lote_productocantidad_logica, $loteid]);
                        $productoU = Producto::findOrFail($fila->producto_id);
                        $productoU->stock = ($productoU->stock -$cantidadmovimiento) + $fila->cantidad;
                        $productoU->update();

                    } else {
                        $lote_productocantidad = $lote_producto->cantidad + $fila->cantidad;
                        $lote_productocantidad_logica = $lote_producto->cantidad_logica + $fila->cantidad;
                        DB::update('update lote_productos set cantidad= ?,cantidad_logica = ? where id = ?', [$lote_productocantidad, $lote_productocantidad_logica, $loteid]);
                        $productoU = Producto::findOrFail($fila->producto_id);
                        $productoU->stock = $productoU->stock + $fila->cantidad;
                        $productoU->update();
                    }
                } else {
                    $producto = Producto::findOrFail($fila->producto_id);
                    DB::insert('insert into lote_productos(codigo,orden_id,producto_id,
                    codigo_producto,descripcion_producto,cantidad,cantidad_logica,fecha_vencimiento,
                    fecha_entrega,observacion,confor_almacen,confor_produccion,estado) values (?,?,?,?,?,?,?,?,?,?,?,?,?)', [
                        $fila->lote, 0, $fila->producto_id,
                        $producto->codigo, $producto->nombre, $fila->cantidad, $fila->cantidad, $fila->fechavencimiento, $request->get('fecha'),
                        "notaingreso:" . $origen->descripcion . "-" . $destino->descripcion, 1, 1, "1"
                    ]);
                    $loteid = DB::table("lote_productos")->where('codigo', $fila->lote)->first()->id;
                    $productoU = Producto::findOrFail($fila->producto_id);
                    $productoU->stock = $productoU->stock + $fila->cantidad;
                    $productoU->update();
                }
                DetalleNotaIngreso::create([
                    'nota_ingreso_id' => $id,
                    'lote' => $loteid,
                    'cantidad' => $fila->cantidad,
                    'producto_id' => $fila->producto_id,
                    'fecha_vencimiento' => $fila->fechavencimiento
                ]);




                MovimientoNota::where('lote_id', $loteid)->where('producto_id', $fila->producto_id)->where('nota_id', $id)->where('movimiento', 'INGRESO')->delete();
                $producto = DB::table('productos')->where('id', $fila->producto_id)->first();
                MovimientoNota::create([
                    'cantidad' => $fila->cantidad,
                    'observacion' => $producto->nombre,
                    'movimiento' => "INGRESO",
                    'lote_id' => $loteid,
                    'usuario_id' => $request->usuario,
                    'producto_id' => $fila->producto_id,
                    'nota_id' => $id,
                ]);
            }
        }






        //Registro de actividad
        $descripcion = "SE ACTUALIZO NOTA DE INGRESO ";
        $gestion = "ALMACEN / NOTA INGRESO";
        crearRegistro($notaingreso, $descripcion, $gestion);


        Session::flash('success', 'NOTA DE INGRESO');
        return redirect()->route('almacenes.nota_ingreso.index')->with('guardar', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $notaingreso = NotaIngreso::findOrFail($id);
        $notaingreso->estado = "ANULADO";
        $notaingreso->save();
        Session::flash('success', 'NOTA DE INGRESO');
        return redirect()->route('almacenes.nota_ingreso.index')->with('guardar', 'success');
    }
    public function uploadnotaingreso(Request $request)
    {
        $data = array();
        $file = $request->file();
        $archivo = $file['files'][0];
        $objeto = new DataExcel();
        Excel::import($objeto, $archivo);

        $datos = $objeto->data;

        try {
            Excel::import(new ImportsNotaIngreso, $archivo);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {

            $failures = $e->failures();

            foreach ($failures as $failure) {
                array_push($data, array(
                    "fila" => $failure->row(),
                    "atributo" => $failure->attribute(),
                    "error" => $failure->errors()
                ));
            }
            array_push($data, array("excel" => $datos));
        } catch (Exception $er) {
            //Log::info($er);
        }

        return json_encode($data);





        //return "hola";
    }
    public function getDownload()
    {
        ob_end_clean(); // this
        ob_start();
        return  Excel::download(new ModeloExport, 'modelo.xlsx');
    }
    public function getProductosExcel()
    {
        ob_end_clean(); // this
        ob_start();
        return  Excel::download(new ProductosExport, 'productos.xlsx');
    }
    public function getErrorExcel(Request $request)
    {
        ob_end_clean(); // this
        ob_start();
        $errores = array();
        $datos = json_decode(($request->arregloerrores));
        for ($i = 0; $i < count($datos) - 1; $i++) {
            array_push($errores, array(
                "fila" => $datos[$i]->fila,
                "atributo" => $datos[$i]->atributo,
                "error" => $datos[$i]->error
            ));
        }
        $data = $datos[count($datos) - 1]->excel;

        return  Excel::download(new ErrorExcel($data, $errores), 'excel_error.xlsx');
    }
}
