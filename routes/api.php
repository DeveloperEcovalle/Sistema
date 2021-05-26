    <?php

    use App\UbicacionCliente;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\DB;
    use Yajra\DataTables\Facades\DataTables;
    use Illuminate\Support\Facades\Log;
    /*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });

    //CLIENTES
    Route::get('clientes', function () {
        $clientes = DB::table('clientes')
            ->select('clientes.*')->distinct()->get();

        return response()->json([
            'clientes' => $clientes,
        ]);
    });

    //TIENDAS
    Route::get('register/clientes', function () {
        $clientes = DB::table('clientes')
            ->select('clientes.*')->distinct()->get();

        foreach ($clientes as $cliente) {
            $cliente->tiendas = DB::table('cliente_tiendas')->where('cliente_tiendas.cliente_id', $cliente->id)
                ->select(
                    'cliente_tiendas.id',
                    'cliente_tiendas.nombre',
                    'cliente_tiendas.tipo_tienda',
                    'cliente_tiendas.tipo_negocio',
                    'cliente_tiendas.direccion'
                )
                ->get();
        }

        return response()->json([
            'clientes' => $clientes
        ]);
    });

    //CLIENTES REALIZARON UNA VENTA PARAMETROS
    // [
    //     ZONA => CENTRO,
    //     DEPARTAMENTO => ANCASH,
    //     MONTO => 10000.00 VALORES MAYORES A ESA VENTA
    //     fecha_fin =>
    //     fecha_inicio =>
    //     comparacion =>
    // ]

    // Route::get('clientes/parametros',function (Request $request)
    // {

    //         $clientes = DB::table('clientes')
    //                     ->when($request->get('zona'), function ($query, $request) {
    //                         return $query->where('clientes.zona', $request);
    //                     })
    //                     ->when($request->get('departamento'), function ($query, $request) {
    //                         return $query->where('clientes.departamento_id', $request);
    //                     });

    //         $clientes = $clientes->get();
    //         $coleccion = collect([]);

    //         foreach ($clientes as $cliente) {
    //             $venta_monto = DB::table('cotizacion_documento')

    //                         ->where('cotizacion_documento.cliente_id',$cliente->id)

    //                         ->when($request->get('fecha_inicio'), function ($query, $request) {
    //                             return $query->where('cotizacion_documento.fecha_documento','>=',$request);
    //                         })

    //                         ->when($request->get('fecha_fin'), function ($query, $request) {
    //                             return $query->where('cotizacion_documento.fecha_documento','>=',$request);
    //                         })

    //                         ->when($request->all(), function ($query, $request) {
    //                             if ( !empty($request['comparacion'])  &&  !empty($request['monto'])  ) {
    //                                 return $query->where('cotizacion_documento.total', $request['comparacion'], $request['monto']);
    //                             }
    //                         })
    //                         ->sum('cotizacion_documento.total');

    //             $coleccion->push([
    //                 'cliente' => $cliente,
    //                 'total' => $venta_monto
    //                 ]);
    //         }

    //         return response()->json($coleccion);
    // });


    //COLABORADORES
    Route::get('colaboradores', function () {
        $colaboradores = DB::table('colaboradores')
            ->join('personas', 'colaboradores.persona_id', 'personas.id')
            ->select('colaboradores.*', 'personas.*')->distinct()
            ->get();

        return response()->json([
            'colaboradores' => $colaboradores,
        ]);
    });

    //TABLAS GENERALES
    Route::get('tabla/detalles/{id}', function ($id) {
        $detalles = DB::table('tabladetalles')
            ->select('tabladetalles.id', 'tabladetalles.descripcion', 'tabladetalles.simbolo')
            ->where('tabladetalles.tabla_id', $id)
            ->where('tabladetalles.estado', 'ACTIVO')
            ->get();

        return response()->json([
            'detalles' => $detalles,
        ]);
    });

    //DEPARTAMENTO 'ZONA'
    Route::get('departamentos/{zona}', function ($zona) {
        $departamentos = DB::table('departamentos')
            ->select('departamentos.id', 'departamentos.nombre')
            ->where('departamentos.zona', $zona)
            ->get();

        return response()->json([
            'departamentos' => $departamentos,
        ]);
    });

    //PRODUCTOS TERMINADOS
    Route::get('productos/terminados', function () {
        return datatables()->query(
            DB::table('productos')
                ->join('familias', 'productos.familia_id', '=', 'familias.id')
                ->join('subfamilias', 'productos.sub_familia_id', '=', 'subfamilias.id')
                ->join('tabladetalles as lineas_comerciales', 'productos.linea_comercial', '=', 'lineas_comerciales.id')
                ->join('tabladetalles as medidas', 'productos.medida', '=', 'medidas.id')
                ->select(
                    'productos.*',
                    'familias.familia as categoria_pt',
                    'subfamilias.descripcion as sub_categoria_pt',
                    'lineas_comerciales.descripcion as lineaComercialDescripcion',
                    'medidas.descripcion as medidaDescripcion',
                    'medidas.simbolo as medidaSimbolo'
                )
                ->where('productos.estado', 'ACTIVO')
        )
            ->toJson();
    });

    // CLIENTES PARAMETROS PARAMETROS
    // [
    //     tipoCliente => id (tabladetalles_id),
    //     tipo => FISICA / VIRTUAL, (cadena)
    //     NEGOCIO => MARKET / BIOTIENDA
    //     COD_PRODUCTO => 204820
    //     ZONA => CENTRO,
    //     DEPARTAMENTO => ANCASH,
    //     MONTO => 10000.00 VALORES MAYORES A ESA VENTA
    //     fecha_fin =>
    //     fecha_inicio =>
    //     comparacion =>
    // ]
    Route::get('clientes/venta/parametros', function (Request $request) {

        $clientes = DB::table('clientes')
            ->when($request->get('tipoCliente'), function ($query, $request) {
                return $query->where('clientes.tabladetalles_id', $request);
            })
            ->leftJoin('cliente_tiendas', 'cliente_tiendas.cliente_id', '=', 'clientes.id')
            ->when($request->get('tipo'), function ($query, $request) {
                return $query->where('cliente_tiendas.tipo_tienda', $request);
            })
            ->when($request->get('negocio'), function ($query, $request) {
                return $query->where('cliente_tiendas.tipo_negocio', $request);
            })
            ->when($request->get('zona'), function ($query, $request) {
                return $query->where('clientes.zona', $request);
            })
            ->when($request->get('departamento'), function ($query, $request) {
                return $query->where('clientes.departamento_id', $request);
            })
            ->select(
                'clientes.id',
                'clientes.tipo_documento',
                'clientes.documento',
                'clientes.correo_electronico',
                'clientes.telefono_movil',
                'clientes.nombre',
                'cliente_tiendas.tipo_tienda',
                'cliente_tiendas.tipo_negocio',
                'clientes.facebook',
                'clientes.instagram',
                'clientes.web',
                'clientes.tabladetalles_id as tipo_cliente_id',
                'clientes.departamento_id as departamento_id',
                'clientes.provincia_id as provincia_id',
                'clientes.distrito_id as distrito_id',
                'clientes.zona'
            );

        $clientes = $clientes->get();
        $coleccion = collect([]);

        foreach ($clientes as $cliente) {
            $ventas = DB::table('cotizacion_documento')
                ->join('cotizacion_documento_detalles', 'cotizacion_documento.id', '=', 'cotizacion_documento_detalles.id')
                ->select('cotizacion_documento_detalles.codigo_producto')->distinct()
                ->where('cotizacion_documento.cliente_id', $cliente->id)
                ->where('cotizacion_documento_detalles.estado', 'ACTIVO')
                ->when($request->get('producto'), function ($query, $request) {
                    return $query->where('cotizacion_documento_detalles.codigo_producto', $request);
                })->get();

            $venta_monto = DB::table('cotizacion_documento')

                ->where('cotizacion_documento.cliente_id', $cliente->id)
                ->when($request->get('fecha_inicio'), function ($query, $request) {
                    return $query->where('cotizacion_documento.fecha_documento', '>=', $request);
                })
                ->when($request->get('fecha_fin'), function ($query, $request) {
                    return $query->where('cotizacion_documento.fecha_documento', '<=', $request);
                })
                ->when($request->all(), function ($query, $request) {
                    if (!empty($request['comparacion'])  &&  !empty($request['monto'])) {
                        return $query->where('cotizacion_documento.total', $request['comparacion'], $request['monto']);
                    }
                })
                ->sum('cotizacion_documento.total');

            $coleccion->push([
                'cliente' => $cliente,
                'producto' => count($ventas) > 0 ? 'SI' : 'NO',
                'total' => $venta_monto
            ]);
        }

        return DataTables::collection($coleccion)->toJson();
    });

    //TIPO DE DOCUMENTO Y SE ENVIA SUS TIENDAS
    // [
    //     tipoDocumento => RUC (CADENA),
    //     documento => DNI O RUC cliente
    // ]
    Route::get('clientes/tienda', function (Request $request) {
        $clientes = DB::table('clientes')
            ->when($request->get('tipoDocumento'), function ($query, $request) {
                return $query->where('clientes.tipo_documento', $request);
            })
            // ->leftJoin('cliente_tiendas', 'cliente_tiendas.cliente_id', '=', 'clientes.id')
            ->when($request->get('documento'), function ($query, $request) {
                return $query->where('clientes.documento', $request);
            })
            ->select(
                'clientes.id',
                'clientes.tipo_documento',
                'clientes.documento',
                'clientes.nombre',
                'clientes.correo_electronico',
                'clientes.telefono_movil',
                'clientes.facebook',
                'clientes.instagram',
                'clientes.zona',
                'clientes.departamento_id',
                'clientes.provincia_id',
                'clientes.tabladetalles_id as tipo_cliente_id',
                'clientes.distrito_id',
                'clientes.web'
            );

        $clientes = $clientes->get();
        $coleccion = collect([]);

        foreach ($clientes as $cliente) {
            $tiendas = DB::table('cliente_tiendas')
                ->select(
                    'cliente_tiendas.id',
                    'cliente_tiendas.nombre',
                    'cliente_tiendas.tipo_tienda',
                    'cliente_tiendas.tipo_negocio',
                    'cliente_tiendas.direccion'
                )
                ->where('cliente_tiendas.cliente_id', $cliente->id)
                ->where('cliente_tiendas.estado', 'ACTIVO')
                ->get();

            $coleccion->push([
                'cliente' => $cliente,
                'tiendas' => $tiendas,
            ]);
        }

        return DataTables::collection($coleccion)->toJson();
    });

    //BUSQUEDA CLIENTE Y DOCUMENTO
    // [
    //     cliente => nombre
    // ]
    Route::get('clientes/documentos', function (Request $request) {
        $clientes = DB::table('clientes')
            ->when($request->get('cliente'), function ($query, $request) {
                return $query->where('nombre', 'like', '%' . $request . '%');
            })
            ->where('clientes.estado', 'ACTIVO')
            ->select(
                'clientes.id',
                'clientes.nombre',
                'clientes.tipo_documento',
                'clientes.documento'
            )->get();

        return DataTables::collection($clientes)->toJson();
    });


    // BUESQUEDA DE ZONAS
    Route::get('zonas/departamentos', function () {
        $departamentos = DB::table('departamentos')
            ->select(
                'departamentos.id',
                'departamentos.nombre'
            )->get();

        return DataTables::collection($departamentos)->toJson();
    });

    // [
    //     departamento => 01
    // ]

    Route::get('zonas/provincias', function (Request $request) {
        $provincias = DB::table('provincias')
            ->when($request->get('departamento'), function ($query, $request) {
                return $query->where('provincias.departamento_id', $request);
            })
            ->select(
                'provincias.id',
                'provincias.nombre'
            )->get();

        return DataTables::collection($provincias)->toJson();
    });

    // [
    //     provincia => 01
    // ]
    Route::get('zonas/distritos', function (Request $request) {
        $distritos = DB::table('distritos')
            ->when($request->get('provincia'), function ($query, $request) {
                return $query->where('distritos.provincia_id', $request);
            })
            ->select(
                'distritos.id',
                'distritos.nombre',
                'distritos.nombre_legal'
            )->get();

        return DataTables::collection($distritos)->toJson();
    });

    //Crm---
    Route::get('campanas/eficiencia', function (Request $request) {

        $ventas = DB::table('productos as p')
            ->join('productos_clientes as pc', 'pc.producto_id', '=', 'p.id')
            ->join('clientes as c', 'c.id', '=', 'pc.cliente')
            ->select(DB::raw('p.nombre,count(p.nombre) as VU'))
            ->where('p.estado', 'ACTIVO')
            ->whereRaw('month(pc.created_at)=?', $request->mes)
            ->whereRaw('year(pc.created_at)=?', $request->year)
            ->groupBy('p.nombre')
            ->get();


        return DataTables::collection($ventas)->toJson();
    });

    Route::get('tiponegocio/ventas', function (Request $request) {

        $ventas = DB::table('productos as p')
            ->join('productos_clientes as pc', 'pc.producto_id', '=', 'p.id')
            ->join('clientes as c', 'c.id', '=', 'pc.cliente')
            ->where('p.estado', 'ACTIVO')
            ->whereRaw('month(pc.created_at)=?', $request->mes)
            ->whereRaw('year(pc.created_at)=?', $request->year)
            ->groupBy('p.nombre')
            ->get();


        return DataTables::collection($ventas)->toJson();
    });
    Route::get('tiponegocio/clientes', function (Request $request) {
        $fecha_actual = Carbon::now();

        $meses_aux = array();
        for ($i = 0; $i < 13; $i++) {
            $f_old = (string)date("d-m-Y", strtotime($fecha_actual . "- " . $i . " month"));
            $m = array("ENE", "FEB", "MAR", "ABR", "MAY", "JUN", "JUL", "AGO", "SEP", "OCT", "NOV", "DIC");
            $f_old = Carbon::parse($f_old);
            $mes = $m[($f_old->format('n')) - 1];
            $nombre = $mes . ' ' . $f_old->format('Y');
            $ob = new stdClass();
            $ob->fecha = date("d-m-Y", strtotime($fecha_actual . "- " . $i . " month"));
            $ob->nombre = $nombre;
            $ob->anio = date("Y", strtotime($fecha_actual . "- " . $i . " month"));
            $ob->mes = date("m", strtotime($fecha_actual . "- " . $i . " month"));
            array_push($meses_aux, $ob);
        }

        $tipos_negocio = DB::table('tabladetalles')
            ->select('id', 'descripcion')
            ->where('tabla_id', 23)
            ->get();

        $cont = 0;
        while ($cont < count($tipos_negocio)) {
            $meses = array();
            for ($j = 0; $j < count($meses_aux); $j++) {
                $vtn = DB::table('clientes as c')
                    ->join('cliente_tiendas as ct', 'ct.cliente_id', '=', 'c.id')
                    ->when($request->get('zona'), function ($query, $request) {
                        return $query->where('c.zona', $request);
                    })
                    ->when($request->get('departamento'), function ($query, $request) {
                        return $query->where('c.departamento_id', $request);
                    })
                    ->when($request->get('provincia'), function ($query, $request) {
                        return $query->where('c.provincia_id', $request);
                    })
                    ->where('ct.estado', 'ACTIVO')
                    ->whereMonth('ct.created_at', $meses_aux[$j]->mes)
                    ->whereYear('ct.created_at', $meses_aux[$j]->anio)
                    ->where('ct.tipo_negocio', $tipos_negocio[$cont]->descripcion)
                    ->select('ct.tipo_negocio')
                    ->get();
                array_push($meses, array("nombre" => $meses_aux[$j]->nombre, "clientes" => count($vtn)));
            }

            $tipos_negocio[$cont]->meses = $meses;
            $cont = $cont + 1;
        }
        return DataTables::collection($tipos_negocio)->toJson();
    });
    Route::get('mapa/peru', function () {
        $file = database_path("data/mapa/peru.json");
        $json = file_get_contents($file);

        return json_decode($json, true);
    });
    Route::get('mapa/peru/departamentos', function () {
        $file = database_path("data/mapa/departamentos.json");
        $json = file_get_contents($file);

        return json_decode($json, true);
    });
    Route::get('clientes/direccion', function () {
        return DB::table('ubicacion_cliente as c')->select('c.direccion', 'c.nombre', 'c.ver', 'c.latitud', 'c.longitud')->get();
    });
    Route::post('posiciciones/clientes', function (Request $request) {
        //Log::info(json_decode($request->lista));
        $datos = json_decode($request->lista);
        for ($i = 0; $i < count($datos); $i++) {
            $consulta = DB::table('ubicacion_cliente')->where('nombre', $datos[$i]->nombre);
            if ($consulta->count() == 0) {
                $ubicacion_cliente = new UbicacionCliente();
                $ubicacion_cliente->nombre = $datos[$i]->nombre;
                $ubicacion_cliente->latitud = $datos[$i]->lat;
                $ubicacion_cliente->longitud = $datos[$i]->lng;
                $ubicacion_cliente->direccion = $datos[$i]->direccion;
                if ($datos[$i]->checked) {
                    $ubicacion_cliente->ver = 1;
                } else {
                    $ubicacion_cliente->ver = 0;
                }

                $ubicacion_cliente->save();
            } else {
                $ubicacion_cliente = UbicacionCliente::findOrFail($consulta->first()->id);
                $ubicacion_cliente->nombre = $datos[$i]->nombre;
                $ubicacion_cliente->latitud = $datos[$i]->lat;
                $ubicacion_cliente->longitud = $datos[$i]->lng;
                $ubicacion_cliente->direccion = $datos[$i]->direccion;
                if ($datos[$i]->checked) {

                    $ubicacion_cliente->ver = 1;
                } else {
                    $ubicacion_cliente->ver = 0;
                }

                $ubicacion_cliente->save();
            }
        }
        /*
        return "exito";*/
    });
