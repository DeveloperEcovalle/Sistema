<?php

namespace App\Http\Controllers\Produccion;

use App\Compras\Articulo;
use App\Http\Controllers\Controller;
use App\Produccion\Familia;
use App\Produccion\Producto;
use Illuminate\Http\Request;
use DataTables;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    public function index()
    {
        return view('produccion.productos.index');
    }

    public function getTable()
    {
        $productos = Producto::where('estado','ACTIVO')->get();
        $coleccion = collect([]);
        foreach($productos as $producto) {
            $coleccion->push([
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'familia' => $producto->familia->familia,
                //'sub_familia' => $producto->sub_familia->nombre,
                'unidad_medida' => $producto->unidad_medida,
                'stock' => $producto->stock
            ]);
        }
        return DataTables::of($coleccion)->toJson();
    }

    public function create()
    {
        $familias = Familia::where('estado', 'ACTIVO')->get();
        $articulos = Articulo::where('estado', 'ACTIVO')->get();

        return view('produccion.productos.create', compact('familias', 'articulos'));
    }

    public function store(Request $request)
    {

    }

    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        return view('produccion.productos.edit', [
            'producto' => $producto
        ]);
    }

    public function update(Request $request, $id)
    {

    }

    public function show($id)
    {
        $producto = Producto::findOrFail($id);
        return view('produccion.productos.show', [
            'producto' => $producto
        ]);
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->estado = 'ANULADO';
        $producto->update();

        $producto->detalles()->update(['estado'=> 'ANULADO']);

        Session::flash('success','Producto eliminado.');
        return redirect()->route('produccion.producto.index')->with('eliminar', 'success');
    }
}
