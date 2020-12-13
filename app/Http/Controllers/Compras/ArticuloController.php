<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use Carbon\Carbon;
use App\Compras\Articulo;
use App\Compras\Categoria;
use App\Almacenes\Almacen;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ArticuloController extends Controller
{
    public function index()
    {
        return view('compras.articulos.index');
    }

    public function getArticle(){
        $articulos = Articulo::where('estado','ACTIVO')->get();
        $coleccion = collect([]);
        foreach($articulos as $articulo){
            $coleccion->push([
                'id' => $articulo->id,
                'descripcion' => $articulo->descripcion,
                'categoria' => $articulo->categoria->descripcion,
                'stock' => $articulo->stock,
                'stock_min' => $articulo->stock_min,
                'precio_compra'=> $articulo->precio_compra,
            ]);
        }
        return DataTables::of($coleccion)->toJson();
    }

    public function create()
    {
        $categorias = Categoria::where('estado','ACTIVO')->get();
        $almacenes = Almacen::where('estado','ACTIVO')->get();
        $presentaciones = presentaciones();
        
        return view('compras.articulos.create',[
            'categorias' => $categorias,
            'presentaciones' => $presentaciones,
            'almacenes' => $almacenes,
        ]);
    }

    public function edit($id)
    {
        $articulo = Articulo::findOrFail($id);
        $categorias = Categoria::where('estado','ACTIVO')->get();
        $almacenes = Almacen::where('estado','ACTIVO')->get();
        $presentaciones = presentaciones();
        
        return view('compras.articulos.edit',[
            'categorias' => $categorias,
            'presentaciones' => $presentaciones,
            'almacenes' => $almacenes,
            'articulo' => $articulo,
        ]);
    }

    public function store(Request $request){

        $data = $request->all();

        $rules = [
            'descripcion' => 'required',
            'categoria' => 'required',
            'presentacion' => 'required',
            'almacen' => 'required',
            'stock' => 'required|integer',
            'stock_min' => 'required|integer',
            'precio_compra' => 'required|numeric',

        ];
        
        $message = [
            'descripcion.required' => 'El campo Descripción es obligatorio.',
            'categoria.required'=> 'El campo Categoria es obligatorio.',
            'presentacion.required'=> 'El campo Presentación es obligatorio.',
            'almacen.required'=>'El campo Almacen es obligatorio.',
            'stock.integer' => 'El campo Stock debe ser Entero.',
            'stock.required' => 'El campo Stock es obligatorio.',

            'stock_min.integer' => 'El campo Stock Min. debe ser Entero.',
            'stock_min.required' => 'El campo Stock Min. es obligatorio.',

            'precio_compra.required' => 'El campo Precio de Compra es obligatorio.',
            'precio_compra.numeric' => 'El campo Precio de Compra debe ser Numeric.',
        ];

        Validator::make($data, $rules, $message)->validate();

        $articulo = new Articulo();
        $articulo->descripcion = $request->get('descripcion');
        $articulo->categoria_id = $request->get('categoria');
        $articulo->almacen_id = $request->get('almacen');
        $articulo->presentacion = $request->get('presentacion');
        $articulo->stock = $request->get('stock');
        $articulo->stock_min = $request->get('stock_min');
        $articulo->precio_compra = $request->get('precio_compra');
        $articulo->save();

        Session::flash('success','Artículo creada.');
        return redirect()->route('compras.articulo.index')->with('guardar', 'success');
    }

    public function update(Request $request, $id){
        $data = $request->all();

        $rules = [
            'descripcion' => 'required',
            'categoria' => 'required',
            'presentacion' => 'required',
            'almacen' => 'required',
            'stock' => 'required|integer',
            'stock_min' => 'required|integer',
            'precio_compra' => 'required|numeric',

        ];
        
        $message = [
            'descripcion.required' => 'El campo Descripción es obligatorio.',
            'categoria.required'=> 'El campo Categoria es obligatorio.',
            'presentacion.required'=> 'El campo Presentación es obligatorio.',
            'almacen.required'=>'El campo Almacen es obligatorio.',
            'stock.integer' => 'El campo Stock debe ser Entero.',
            'stock.required' => 'El campo Stock es obligatorio.',

            'stock_min.integer' => 'El campo Stock Min. debe ser Entero.',
            'stock_min.required' => 'El campo Stock Min. es obligatorio.',

            'precio_compra.required' => 'El campo Precio de Compra es obligatorio.',
            'precio_compra.numeric' => 'El campo Precio de Compra debe ser Numeric.',
        ];

        Validator::make($data, $rules, $message)->validate();
        
        $articulo = Articulo::findOrFail($id);
        $articulo->descripcion = $request->get('descripcion');
        $articulo->categoria_id = $request->get('categoria');
        $articulo->almacen_id = $request->get('almacen');
        $articulo->presentacion = $request->get('presentacion');
        $articulo->stock = $request->get('stock');
        $articulo->stock_min = $request->get('stock_min');
        $articulo->precio_compra = $request->get('precio_compra');
        $articulo->update();

        Session::flash('success','Artículo modificada.');
        return redirect()->route('compras.articulo.index')->with('modificar', 'success');

    }


    public function destroy($id)
    {
        
        $articulo = Articulo::findOrFail($id);
        $articulo->estado = 'ANULADO';
        $articulo->update();

        Session::flash('success','Artículo eliminado.');
        return redirect()->route('compras.articulo.index')->with('eliminar', 'success');

    }

    public function show($id)
    {
        $articulo = Articulo::findOrFail($id);
        return view('compras.articulos.show', [
            'articulo' => $articulo 
        ]);

    }



}
