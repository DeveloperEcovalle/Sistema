<?php

namespace App\Http\Controllers\Almacenes;

use App\Compras\Articulo;
use App\Http\Controllers\Controller;
use App\Almacenes\Familia;
use App\Almacenes\TipoCliente;
use App\Almacenes\Producto;
use App\Almacenes\ProductoDetalle;
use App\Almacenes\SubFamilia;
use Illuminate\Http\Request;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Session;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    public function index()
    {
        return view('almacenes.productos.index');
    }

    public function getTable()
    {
        $productos = Producto::where('estado','ACTIVO')->get();
        $coleccion = collect([]);
        foreach($productos as $producto) {
            $coleccion->push([
                'id' => $producto->id,
                'codigo' => $producto->codigo,
                'nombre' => $producto->nombre,
                'familia' => $producto->familia->familia,
                'stock' => $producto->stock
            ]);
        }
        return DataTables::of($coleccion)->toJson();
    }

    public function create()
    {
        $familias = Familia::where('estado', 'ACTIVO')->get();
        $articulos = Articulo::where('estado', 'ACTIVO')->get();

        return view('almacenes.productos.create', compact('familias', 'articulos'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $data = $request->all();

        $rules = [
            'codigo' => ['required','string', 'max:50', Rule::unique('productos','codigo')->where(function ($query) {
                $query->whereIn('estado',["ACTIVO"]);
            })],
            'codigo_barra' => ['nullable',Rule::unique('articulos','codigo_barra')->where(function ($query) {
                $query->whereIn('estado',["ACTIVO"]);
            })],
            'nombre' => 'required',
            'moneda' => 'required',
            'familia' => 'required',
            'sub_familia' => 'required',
            'presentacion' => 'required',
            'stock' => 'required|numeric',
            'stock_minimo' => 'required|numeric',
            'precio_venta_minimo' => 'required|numeric',
            'precio_venta_maximo' => 'required|numeric',
            'igv' => 'required|boolean',
            //'detalles' => 'required|string'
        ];

        $message = [
            'codigo.required' => 'El campo Código es obligatorio',
            'codigo_barra.unique' => 'El campo Código de Barra debe de ser único.',
            'moneda.required' => 'El campo Moneda es obligatorio',
            'codigo.unique' => 'El campo Código debe ser único',
            'codigo.max:50' => 'El campo Código debe tener como máximo 50 caracteres',
            'nombre.required' => 'El campo Nombre es obligatorio',
            'familia.required' => 'El campo Categoria es obligatorio',
            'sub_familia.required' => 'El campo Sub Categoria es obligatorio',
            'presentacion.required' => 'El campo Presentación completa es obligatorio',
            'stock.required' => 'El campo Stock es obligatorio',
            'stock.numeric' => 'El campo Stock debe ser numérico',
            'stock_minimo.required' => 'El campo Stock mínimo es obligatorio',
            'stock_minimo.numeric' => 'El campo Stock mínimo debe ser numérico',
            'precio_venta_minimo.required' => 'El campo Precio de venta mínimo es obligatorio',
            'precio_venta_minimo.numeric' => 'El campo Precio de venta mínimo debe ser numérico',
            'precio_venta_maximo.required' => 'El campo Precio de venta máximo es obligatorio',
            'precio_venta_máximo.numeric' => 'El campo Precio de venta máximo debe ser numérico',
            'igv.required' => 'El campo IGV es obligatorio',
            'igv.boolean' => 'El campo IGV debe ser SI o NO',
            'detalles.required' => 'Debe exitir al menos un detalle del producto',
            'detalles.string' => 'El formato de texto de los detalles es incorrecto',
        ];

        Validator::make($data, $rules, $message)->validate();

        DB::transaction(function () use ($request) {

            $producto = new Producto();
            $producto->codigo = $request->get('codigo');
            $producto->codigo_barra = $request->get('codigo_barra');
            $producto->moneda = $request->get('moneda');
            $producto->nombre = $request->get('nombre');
            $producto->familia_id = $request->get('familia');
            $producto->sub_familia_id = $request->get('sub_familia');
            $producto->presentacion = $request->get('presentacion');
            $producto->stock = $request->get('stock');
            $producto->stock_minimo = $request->get('stock_minimo');
            $producto->precio_venta_minimo = $request->get('precio_venta_minimo');
            $producto->precio_venta_maximo = $request->get('precio_venta_maximo');
            $producto->igv = $request->get('igv');
            $producto->save();

            //Llenado de los Clientes

            $clientesJSON = $request->get('clientes_tabla');
            $clientetabla = json_decode($clientesJSON[0]);

            foreach ($clientetabla as $cliente) {
                TipoCliente::create([
                    'producto_id' => $producto->id,
                    'cliente' => $cliente->cliente,
                    'monto' => $cliente->monto,
                ]);
            }
        

        });



        Session::flash('success','Producto creado.');
        return redirect()->route('almacenes.producto.index')->with('guardar', 'success');
    }

    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        $familias = Familia::where('estado', 'ACTIVO')->get();
        $sub_familias = SubFamilia::where('id', $producto->familia_id)->get();
        $articulos = Articulo::where('estado', 'ACTIVO')->get();
        $clientes = TipoCliente::where('estado','ACTIVO')->where('producto_id',$id)->get();
        $detalles = [];

        foreach ($producto->detalles->where('estado', 'ACTIVO') as $detalle) {
            $data = [
                'id' => (string)$detalle->id,
                'articulo_id' => (string)$detalle->articulo_id,
                'articulo' => $detalle->articulo->codigo_fabrica.'-'.$detalle->articulo->descripcion,
                'cantidad' => (int)$detalle->cantidad,
                'peso' => (float)$detalle->peso,
                'observacion' => $detalle->observacion
            ];
            array_push($detalles, $data);
        }

        return view('almacenes.productos.edit', [
            'producto' => $producto,
            'detalles' => json_encode($detalles),
            'familias' => $familias,
            'sub_familias' => $sub_familias,
            'articulos' => $articulos,
            'clientes' => $clientes
        ]);
    }

    public function update(Request $request, $id)
    {
    
        $data = $request->all();

        $rules = [
            'codigo' => ['required','string', 'max:50', Rule::unique('productos','codigo')->where(function ($query) {
                $query->whereIn('estado',["ACTIVO"]);
            })->ignore($id)],
            'codigo_barra' => ['nullable',Rule::unique('articulos','codigo_barra')->where(function ($query) {
                $query->whereIn('estado',["ACTIVO"]);
            })],
            'nombre' => 'required',
            'nombre' => 'required',
            'familia' => 'required',
            'sub_familia' => 'required',
            'presentacion' => 'required',
            'stock' => 'required|numeric',
            'stock_minimo' => 'required|numeric',
            'precio_venta_minimo' => 'required|numeric',
            'precio_venta_maximo' => 'required|numeric',
            'igv' => 'required|boolean',
            // 'detalles' => 'required|string'
        ];

        $message = [
            'codigo.required' => 'El campo Código es obligatorio',
            'codigo.unique' => 'El campo Código debe ser único',
            'codigo.max:50' => 'El campo Código debe tener como máximo 50 caracteres',
            'nombre.required' => 'El campo Nombre es obligatorio',
            'familia.required' => 'El campo Categoria es obligatorio',
            'sub_familia.required' => 'El campo Sub Categoria es obligatorio',
            'presentacion.required' => 'El campo Presentación completa es obligatorio',
            'stock.required' => 'El campo Stock es obligatorio',
            'stock.numeric' => 'El campo Stock debe ser numérico',
            'stock_minimo.required' => 'El campo Stock mínimo es obligatorio',
            'stock_minimo.numeric' => 'El campo Stock mínimo debe ser numérico',
            'precio_venta_minimo.required' => 'El campo Precio de venta mínimo es obligatorio',
            'precio_venta_minimo.numeric' => 'El campo Precio de venta mínimo debe ser numérico',
            'precio_venta_maximo.required' => 'El campo Precio de venta máximo es obligatorio',
            'precio_venta_máximo.numeric' => 'El campo Precio de venta máximo debe ser numérico',
            'igv.required' => 'El campo IGV es obligatorio',
            'igv.boolean' => 'El campo IGV debe ser SI o NO',
            'codigo_barra.unique' => 'El campo Código de Barra debe de ser único.',
            'moneda.required' => 'El campo Moneda es obligatorio',
            // 'detalles.required' => 'Debe exitir al menos un detalle del producto',
            // 'detalles.string' => 'El formato de texto de los detalles es incorrecto',
        ];

        Validator::make($data, $rules, $message)->validate();

        $producto = Producto::findOrFail($id);
        $producto->codigo = $request->get('codigo');
        $producto->nombre = $request->get('nombre');
        $producto->familia_id = $request->get('familia');
        $producto->sub_familia_id = $request->get('sub_familia');
        $producto->presentacion = $request->get('presentacion');
        $producto->stock = $request->get('stock');
        $producto->stock_minimo = $request->get('stock_minimo');
        $producto->precio_venta_minimo = $request->get('precio_venta_minimo');
        $producto->precio_venta_maximo = $request->get('precio_venta_maximo');
        $producto->igv = $request->get('igv');
        $producto->codigo_barra = $request->get('codigo_barra');
        $producto->moneda = $request->get('moneda');
        $producto->update();

        // Actualizamos o creamos los detalles según el valor 'id'
        // foreach (json_decode($request->get('detalles')) as $detalle) {
        //     if (!is_null($detalle->id)) {
        //         // Actualizamos
        //         $producto_detalle = $producto->detalles->firstWhere('id', $detalle->id);
        //         if ($producto_detalle) {
        //             $producto_detalle->articulo_id = $detalle->articulo_id;
        //             $producto_detalle->cantidad = $detalle->cantidad;
        //             $producto_detalle->peso = $detalle->peso;
        //             $producto_detalle->observacion = $detalle->observacion;
        //             $producto_detalle->update();
        //         }
        //     } else {
        //         // Creamos
        //         $producto_detalle = new ProductoDetalle();
        //         $producto_detalle->producto_id = $producto->id;
        //         $producto_detalle->articulo_id = $detalle->articulo_id;
        //         $producto_detalle->cantidad = $detalle->cantidad;
        //         $producto_detalle->peso = $detalle->peso;
        //         $producto_detalle->observacion = $detalle->observacion;
        //         $producto_detalle->save();
        //     }
        // }

        $clientesJSON = $request->get('clientes_tabla');
        $clientetabla = json_decode($clientesJSON[0]);

        
        if ($clientetabla) {
            $clientes = TipoCliente::where('producto_id', $id)->get();
            foreach ($clientes as $cliente) {
                $cliente->delete();
            }
            foreach ($clientetabla as $cliente) {
                TipoCliente::create([
                    'producto_id' => $producto->id,
                    'cliente' => $cliente->cliente,
                    'monto' => $cliente->monto,
                ]);
            }
        }
        



        Session::flash('success','Producto modificado.');
        return redirect()->route('almacenes.producto.index')->with('guardar', 'success');
    }

    public function show($id)
    {
        $producto = Producto::findOrFail($id);
        $clientes = TipoCliente::where('estado','ACTIVO')->where('producto_id',$id)->get();
        return view('almacenes.productos.show', [
            'producto' => $producto,
            'clientes' => $clientes,
        ]);
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->estado = 'ANULADO';
        $producto->update();

        $producto->detalles()->update(['estado'=> 'ANULADO']);

        Session::flash('success','Producto eliminado.');
        return redirect()->route('almacenes.producto.index')->with('eliminar', 'success');
    }

    public function destroyDetalle(Request $request)
    {
        $data = $request->all();

        $result = 0;
        if ($data['id']) {
            ProductoDetalle::destroy($data['id']);
            $result = 1;
        }

        $data = ['exito' => ($result === 1)];

        return response()->json($data);
    }

    public function getCodigo(Request $request)
    {
        $data = $request->all();
        $codigo = $data['codigo'];
        $id = $data['id'];
        $producto = null;

        if ($codigo && $id) { // edit
            $producto = Producto::where([
                                    ['codigo', $data['codigo']],
                                    ['id', '<>', $data['id']]
                                ])->first();
        } else if ($codigo && !$id) { // create
            $producto = Producto::where('codigo', $data['codigo'])->first();
        }

        $result = ['existe' => ($producto) ? true : false];

        return response()->json($result);
    }

    public function obtenerProducto($id)
    {
        $clientes = TipoCliente::where('estado','ACTIVO')->where('producto_id',$id)->get();
        return $clientes;
    }

    public function productoDescripcion($id)
    {
        $producto = Producto::findOrFail($id);        
        return $producto;
    }
}
