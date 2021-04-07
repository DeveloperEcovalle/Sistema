<?php

namespace App\Http\Controllers\Mantenimiento\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mantenimiento\Empresa\Empresa;
use App\Mantenimiento\Ubigeo\Departamento;
use App\Mantenimiento\Ubigeo\Provincia;
use App\Mantenimiento\Ubigeo\Distrito;
use DataTables;
use Carbon\Carbon;
use Session;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use DB;
use App\Mantenimiento\Empresa\Banco;
use App\Mantenimiento\Empresa\Facturacion;
use App\Mantenimiento\Empresa\Numeracion;

use App\Facturacion\Helpers\Certificate\GenerateCertificate;

use App\Events\FacturacionEmpresa;
use App\Events\EmpresaModificada;


class EmpresaController extends Controller
{
    public function index()
    {
        return view('mantenimiento.empresas.index');
    }

    public function getBusiness(){
        return datatables()->query(
            DB::table('empresas')
            ->select('empresas.*')->where('empresas.estado','ACTIVO')->orderBy('empresas.id', 'desc')
        )->toJson();
    }

    public function obtenerNumeracion($id){

        $numeraciones = Numeracion::where('empresa_id',$id)->where('estado','!=','ANULADO')->get();
        $coleccion = collect([]);
        foreach($numeraciones as $numeracion){
            $coleccion->push([
                'id' => $numeracion->id,
                'tipo_id' => $numeracion->tipo_comprobante,
                'serie' => $numeracion->serie,
                'tipo_comprobante' => $numeracion->comprobanteDescripcion(),
                'numero_iniciar' => $numeracion->numero_iniciar,
                'emision' => $numeracion->emision_iniciada,
            ]);
        }
        return DataTables::of($coleccion)->toJson();
    }

    public function create()
    {
        $departamentos = Departamento::all();
        $provincias = Provincia::all();
        $distritos = Distrito::all();
        $bancos = bancos();
        $monedas = tipos_moneda();

        return view('mantenimiento.empresas.create',[
            'departamentos' => $departamentos,
            'provincias' => $provincias,
            'distritos' => $distritos,
            'bancos' => $bancos,
            'monedas' => $monedas,
        ]);
    }

    public function store(Request $request){
        $data = $request->all();

        $rules = [
            'ruc' => ['required','numeric','min:11', Rule::unique('empresas','ruc')->where(function ($query) {
                        $query->whereIn('estado',["ACTIVO"]);
                    })],
            'estado' => 'required',
            'razon_social' => 'required',
            'direccion_fiscal' => 'required',
            'direccion_llegada' => 'required',
            'dni_representante' => 'required|min:8|numeric',
            'nombre_representante' => 'required',
            'num_asiento' => 'required',
            'num_partida' => 'required',
            'telefono' => 'nullable|numeric',
            'celular' => 'nullable|numeric',
            'correo' => 'nullable',
            'web' => 'nullable',
            'facebook' => 'nullable',
            'instagram' => 'nullable',
            'estado_fe' => 'nullable',
            'logo' => 'image|mimetypes:image/jpeg,image/png,image/jpg|max:40000|required_if:estado_fe,==,on',
            'certificado_base' => 'required_if:estado_fe,==,on',
            'soap_usuario' => 'required_if:estado_fe,==,on',
            'soap_password' => 'required_if:estado_fe,==,on',
        ];
        
        $message = [
            'ruc.required' => 'El campo Ruc es obligatorio.',
            'ruc.unique'=> 'El campo Ruc debe de ser campo único.',
            'ruc.numeric'=> 'El campo Ruc debe se numérico.',
            'ruc.min'=>'El campo Ruc debe tener 11 dígitos.',
            'razon_social.required' => 'El campo Razón Social es obligatorio.',
            'direccion_fiscal.required' => 'El campo Direccion Fiscal es obligatorio.',
            'direccion_llegada.required' => 'El campo Direccion Planta es obligatorio.',
            'logo.image' => 'El campo Logo no contiene el formato imagen.',
            'logo.max' => 'El tamaño máximo del Logo para cargar es de 40 MB.',
            'dni_representante.required' => 'El campo Dni es obligatorio.',
            'dni_representante.min' => 'El campo Dni debe tener 8 dígitos.',
            'dni_representante.numeric' => 'El campo Dni debe ser numérico.',
            'nombre_representante.required' => 'El campo Nombre es obligatorio.',
            'num_asiento.required' => 'El campo N° Asiento es obligatorio.',
            'num_partida.required' => 'El campo N° Partida es obligatorio.',
            'telefono.numeric' => 'El campo Teléfono es obligatorio.',
            'estado.required' => 'El campo Estado es obligatorio.',
            'soap_usuario.required_if' => 'El campo Soap Usuario es obligatorio.',
            'soap_password.required_if' => 'El campo Soap Contraseña es obligatorio.',
        ];

        Validator::make($data, $rules, $message)->validate();

        $empresa = new Empresa();
        $empresa->ruc = $request->get('ruc');
        $empresa->razon_social = $request->get('razon_social');
        $empresa->razon_social_abreviada = $request->get('razon_social_abreviada');
        $empresa->direccion_fiscal = $request->get('direccion_fiscal');
        $empresa->direccion_llegada = $request->get('direccion_llegada');
        $empresa->telefono = $request->get('telefono');
        $empresa->celular = $request->get('celular');
        $empresa->ubigeo = $request->get('ubigeo_empresa');

        if($request->hasFile('logo')){                
            $file = $request->file('logo');
            $name = $file->getClientOriginalName();
            $empresa->nombre_logo = $name;
            $empresa->ruta_logo = $request->file('logo')->store('public/empresas/logos');
            $empresa->base64_logo = base64_encode( file_get_contents($request->file('logo')));
        }
        
        $empresa->dni_representante = $request->get('dni_representante');
        $empresa->nombre_representante = $request->get('nombre_representante');
        $empresa->estado_dni_representante = $request->get('estado_dni_representante');

        $empresa->num_partida = $request->get('num_partida');
        $empresa->num_asiento = $request->get('num_asiento');
        $empresa->estado_ruc = $request->get('estado');
        $empresa->web = $request->get('web');
        $empresa->facebook = $request->get('facebook');
        $empresa->instagram = $request->get('instagram');

        if ($request->get('estado_fe') == 'on'){
            $empresa->estado_fe = '1';
        }else{
            $empresa->estado_fe = '0';
        }
        $empresa->save();


        if ($request->get('estado_fe') == 'on'){

            $contenidoImagen = file_get_contents($request->file('logo'));

            $empresa_facturacion = array(
                "plan" => "free", 
                "environment" => "beta",
                "sol_user" => $request->get('soap_usuario'),
                "sol_pass" => $request->get('soap_password'),
                "ruc" =>  $empresa->ruc,
                "razon_social" => $empresa->razon_social,
                "direccion" => $empresa->direccion_fiscal,
                "certificado" => $request->get('certificado_base'),
                "logo" => $empresa->base64_logo,
            );

            $json_empresa = json_encode($empresa_facturacion);
            //AGREGAR EMPRESA "FACTURACION ELECTRONICA"
            $facturado = json_decode((agregarEmpresaapi($json_empresa)));
            //Facturacion Electronica (GUARDAR DATOS INGRESADO POR API)
            $facturacion = new Facturacion();
            $facturacion->empresa_id = $empresa->id; //RELACION CON LA EMPRESA
            $facturacion->fe_id = $facturado->id; //ID EMPRESA API
            $facturacion->sol_user = $facturado->sol_user;
            $facturacion->sol_pass = $facturado->sol_pass;
            $facturacion->plan = $facturado->plan->nombre;
            $facturacion->ambiente = $facturado->environment->nombre;
            $facturacion->ruta_certificado_pem = $facturado->certificado;
            $facturacion->certificado =  $request->get('certificado_base');
            $facturacion->save();

            //REGISTRAR NUMERACION DE FACTURACION DE LA EMPRESA
            event(new FacturacionEmpresa(
                $empresa,
                $data['numeracion_tabla'])
            );

        }

        //Llenado de Bancos
        $entidadesJSON = $request->get('entidades_tabla');
        $entidadtabla = json_decode($entidadesJSON[0]);

        if ($entidadtabla) {
            foreach ($entidadtabla as $entidad) {
                Banco::create([
                    'empresa_id' => $empresa->id,
                    'descripcion' => $entidad->entidad,
                    'tipo_moneda' => $entidad->moneda,
                    'num_cuenta' => $entidad->cuenta,
                    'cci' => $entidad->cci,
                    'itf' => $entidad->itf,
                ]);
            }
        }

        //Registro de actividad
        $descripcion = "SE AGREGÓ LA EMPRESA: ". $empresa->razon_social.' con el RUC '.$empresa->ruc;
        $gestion = "EMPRESAS";
        crearRegistro($empresa, $descripcion , $gestion);

        Session::flash('success','Empresa creada.');
        return redirect()->route('mantenimiento.empresas.index')->with('guardar', 'success');
    }

    public function destroy($id)
    {
        
        $empresa = Empresa::findOrFail($id);
        $empresa->estado = 'ANULADO';
        $empresa->update();

        //Registro de actividad
        $descripcion = "SE ELIMINÓ LA EMPRESA: ". $empresa->razon_social.' con el RUC '.$empresa->ruc;
        $gestion = "EMPRESAS";
        eliminarRegistro($empresa, $descripcion , $gestion);

        $facturacion = Facturacion::where('empresa_id',$empresa->id)->where('estado','ACTIVO')->first();
       
        if ($facturacion) {
            
            $estado = borrarEmpresaapi($facturacion->fe_id);

            $facturacion->estado = 'ANULADO';
            $facturacion->update();
    
            if ($estado != '200') {
                // Session::flash('success','Empresa eliminado (Error en eliminacion del certificado)');
                // return redirect()->route('mantenimiento.empresas.index')->with('eliminar', 'success');

                return [
                    'success' => true,
                    'eliminar' => 'success',
                    'mensaje' => 'Empresa eliminada (Error en eliminacion del certificado)'
                ];

            }else{
                // Session::flash('success','Empresa eliminado.');
                // return redirect()->route('mantenimiento.empresas.index')->with('eliminar', 'success');
                
                return [
                    'success' => true,
                    'eliminar' => 'success',
                    'mensaje' => 'Empresa eliminada.'
                ];
            }



        }else{

            // Session::flash('success','Empresa eliminado.');
            // return redirect()->route('mantenimiento.empresas.index')->with('eliminar', 'success');
            return [
                'success' => true,
                'eliminar' => 'success',
                'mensaje' => 'Empresa eliminada'
            ];

        }


    }

    public function show($id)
    {
        $empresa = Empresa::findOrFail($id);
        $banco = Banco::where('empresa_id',$id)
        ->where('estado','ACTIVO')
        ->get();
        return view('mantenimiento.empresas.show', [
            'empresa' => $empresa ,
            'banco' => $banco
        ]);

    }

    public function edit($id)
    {
        $empresa = Empresa::findOrFail($id);
        
        $numeraciones = Numeracion::where('empresa_id',$id)->where('estado','ACTIVO')->get();
        $facturacion = Facturacion::where('empresa_id',$empresa->id)->where('estado','ACTIVO')->first();
        $banco = Banco::where('empresa_id',$id)->where('estado','ACTIVO')->get();
        $bancos = bancos();
        $monedas = tipos_moneda();
        return view('mantenimiento.empresas.edit', [
            'empresa' => $empresa ,
            'bancos' => $bancos, 
            'monedas' => $monedas,
            'banco' => $banco,
            'facturacion' => $facturacion,
            'numeraciones' => $numeraciones,
        ]);

    }

    public function update(Request $request, $id){
        $data = $request->all();
        $rules = [
            'ruc' => ['required','numeric','min:11', Rule::unique('empresas','ruc')->where(function ($query) {
                        $query->whereIn('estado',["ACTIVO"]);
                    })->ignore($id)],
            'razon_social' => 'required',
            'estado' => 'required',
            'direccion_fiscal' => 'required',
            'direccion_llegada' => 'required',
            'dni_representante' => 'required|min:8|numeric',
            'nombre_representante' => 'required',
            'num_asiento' => 'required',
            'num_partida' => 'required',
            'telefono' => 'nullable|numeric',
            'celular' => 'nullable|numeric',
            'web' => 'nullable',
            'facebook' => 'nullable',
            'instagram' => 'nullable',
            'estado_fe' => 'nullable',
            'logo' => 'image|mimetypes:image/jpeg,image/png,image/jpg|max:40000',
            'certificado_base' => 'required_if:estado_fe,==,1',
            'soap_usuario' => 'required_if:estado_fe,==,1',
            'soap_password' => 'required_if:estado_fe,==,1',

        ];
        
        $message = [
            'ruc.required' => 'El campo Ruc es obligatorio.',
            'ruc.unique'=> 'El campo Ruc debe de ser campo único.',
            'ruc.min'=>'El campo Ruc debe tener 11 dígitos.',
            'ruc.numeric'=> 'El campo Ruc debe se numérico.',
            'razon_social.required' => 'El campo Razón Social es obligatorio.',
            'direccion_fiscal.required' => 'El campo Direccion Fiscal es obligatorio.',
            'direccion_llegada.required' => 'El campo Direccion Planta es obligatorio.',
            'logo.image' => 'El campo Logo no contiene el formato imagen.',
            'logo.max' => 'El tamaño máximo del Logo para cargar es de 40 MB.',
            'dni_representante.required' => 'El campo Dni es obligatorio.',
            'dni_representante.min' => 'El campo Dni debe tener 8 dígitos.',
            'dni_representante.numeric' => 'El campo Dni debe ser numérico.',
            'nombre_representante.required' => 'El campo Nombre es obligatorio.',
            'num_asiento.required' => 'El campo N° Asiento es obligatorio.',
            'num_partida.required' => 'El campo N° Partida es obligatorio.',
            'telefono.numeric' => 'El campo Teléfono es obligatorio.',
            'celular.numeric' => 'El campo Celular es obligatorio.',
            'estado.required' => 'El campo Estado es obligatorio.',
            'soap_usuario.required_if' => 'El campo Soap Usuario es obligatorio.',
            'soap_password.required_if' => 'El campo Soap Contraseña es obligatorio.',

        ];

        Validator::make($data, $rules, $message)->validate();

        $empresa = Empresa::findOrFail($id);
        $empresa->ruc = $request->get('ruc');
        $empresa->razon_social = $request->get('razon_social');
        $empresa->razon_social_abreviada = $request->get('razon_social_abreviada');
        $empresa->direccion_fiscal = $request->get('direccion_fiscal');
        $empresa->direccion_llegada = $request->get('direccion_llegada');
        $empresa->telefono = $request->get('telefono');
        $empresa->celular = $request->get('celular');

       
        if($request->hasFile('logo')){
            //Eliminar Archivo anterior
            Storage::delete($empresa->ruta_logo);               
            //Agregar nuevo archivo
            $file = $request->file('logo');
            $name = $file->getClientOriginalName();
            $empresa->nombre_logo = $name;
            $empresa->ruta_logo = $request->file('logo')->store('public/empresas/logos');
            $empresa->base64_logo = base64_encode( file_get_contents($request->file('logo')));
        }else{
            if ($request->get('ruta_logo') == null ) {
                $empresa->nombre_logo = "";
                $empresa->ruta_logo = "";
                $empresa->base64_logo = "";
            }
        }

        $empresa->dni_representante = $request->get('dni_representante');
        $empresa->nombre_representante = $request->get('nombre_representante');
        $empresa->estado_dni_representante = $request->get('estado_dni_representante');

        $empresa->num_partida = $request->get('num_partida');
        $empresa->num_asiento = $request->get('num_asiento');
        $empresa->estado_ruc = $request->get('estado');
        $empresa->web = $request->get('web');
        $empresa->facebook = $request->get('facebook');
        $empresa->instagram = $request->get('instagram');
        $empresa->ubigeo = $request->get('ubigeo_empresa');

        if ($request->get('estado_fe') == '1'){
            $empresa->estado_fe = '1';
        }else{
            $empresa->estado_fe = '0';
        }

        $empresa->update();



        if ($request->get('estado_fe') == '1' ){

            if($request->hasFile('logo')){
                $contenidoImagen = file_get_contents($request->file('logo'));
                $empresa_facturacion = array(
                    "plan" => "free", 
                    "environment" => "beta",
                    "sol_user" => $request->get('soap_usuario'),
                    "sol_pass" => $request->get('soap_password'),
                    "ruc" =>  $empresa->ruc,
                    "razon_social" => $empresa->razon_social,
                    "direccion" => $empresa->direccion_fiscal,
                    "certificado" => $request->get('certificado_base'),
                    "logo" => base64_encode($contenidoImagen),
                );
            }else{
                if ($request->get('ruta_logo') != null ) {
                    $empresa_facturacion = array(
                        "plan" => "free", 
                        "environment" => "beta",
                        "sol_user" => $request->get('soap_usuario'),
                        "sol_pass" => $request->get('soap_password'),
                        "ruc" =>  $empresa->ruc,
                        "razon_social" => $empresa->razon_social,
                        "direccion" => $empresa->direccion_fiscal,
                        "certificado" => $request->get('certificado_base'),
                        "logo" => $empresa->base64_logo,
                    );
                }else{
                    $empresa_facturacion = array(
                        "plan" => "free", 
                        "environment" => "beta",
                        "sol_user" => $request->get('soap_usuario'),
                        "sol_pass" => $request->get('soap_password'),
                        "ruc" =>  $empresa->ruc,
                        "razon_social" => $empresa->razon_social,
                        "direccion" => $empresa->direccion_fiscal,
                        "certificado" => $request->get('certificado_base'),
                    );
                }
            }
        

            $json_empresa = json_encode($empresa_facturacion);
            //ENCONTRAR ID DE LA API
            $facturacion = Facturacion::where('empresa_id',$empresa->id)->where('estado','ACTIVO')->first();
           
            if ($facturacion) {
                
                //MODIFICAR EMPRESA "FACTURACION ELECTRONICA"
                $facturado = json_decode((modificarEmpresaapi($json_empresa,$facturacion->fe_id)));

                //Facturacion Electronica (GUARDAR DATOS INGRESADO POR API)
                $facturacion = Facturacion::findOrFail($facturacion->id);
                $facturacion->empresa_id = $empresa->id; //RELACION CON LA EMPRESA
                // $facturacion->fe_id = $facturado->id; //ID EMPRESA API
                $facturacion->sol_user = $facturado->sol_user;
                $facturacion->sol_pass = $facturado->sol_pass;
                $facturacion->plan = $facturado->plan->nombre;
                $facturacion->ambiente = $facturado->environment->nombre;
                // $facturacion->logo =  base64_encode($contenidoImagen);
                $facturacion->ruta_certificado_pem = $facturado->certificado;
                $facturacion->certificado =  $request->get('certificado_base');
                $facturacion->update();
            
            }else{
              
                //AGREGAR EMPRESA "FACTURACION ELECTRONICA"
                $agregar_api = json_decode((agregarEmpresaapi($json_empresa)));
                //Facturacion Electronica (GUARDAR DATOS INGRESADO POR API)
                $nueva_factura = new Facturacion();
                $nueva_factura->empresa_id = $empresa->id; //RELACION CON LA EMPRESA
                $nueva_factura->fe_id = $agregar_api->id; //ID EMPRESA API
                $nueva_factura->sol_user = $agregar_api->sol_user;
                $nueva_factura->sol_pass = $agregar_api->sol_pass;
                $nueva_factura->plan = $agregar_api->plan->nombre;
                $nueva_factura->ambiente = $agregar_api->environment->nombre;
                // $nueva_factura->logo =  base64_encode($contenidoImagen);
                $nueva_factura->ruta_certificado_pem = $agregar_api->certificado;
                $nueva_factura->certificado =  $request->get('certificado_base');
                $nueva_factura->save();
            }



        }
        
        // else{

        //     dd('sasa');
        //     //ENCONTRAR SI EXISTE REGISTRA DE LA FACTURACION
        //     $facturacion = Facturacion::where('empresa_id',$empresa->id)->where('estado','ACTIVO')->first();

        //     if ($facturacion) {
        //         //BORRAR REGISTRO DE LA API
        //         $estado = borrarEmpresaapi($facturacion->fe_id);
        //         $facturacion->estado = "ANULADO";
        //         $facturacion->update();

        //     }

        // }

        //MODIFICAR NUMERACION DE FACTURACION DE LA EMPRESA
        event(new EmpresaModificada(
            $empresa,
            $data['numeracion_tabla'])
        );

        $entidadesJSON = $request->get('entidades_tabla');
        $entidadtabla = json_decode($entidadesJSON[0]);

        if ($entidadtabla) {

            $bancos = Banco::where('empresa_id', $empresa->id)->get();
            foreach ($bancos as $banco) {
                $banco->estado= "ANULADO";
                $banco->update();
            }
            foreach ($entidadtabla as $entidad) {
                Banco::create([
                    'empresa_id' => $empresa->id,
                    'descripcion' => $entidad->entidad,
                    'tipo_moneda' => $entidad->moneda,
                    'num_cuenta' => $entidad->cuenta,
                    'cci' => $entidad->cci,
                    'itf' => $entidad->itf,
                ]);
            }

        }else{
            $bancos = Banco::where('empresa_id', $empresa->id)->get();
            foreach ($bancos as $banco) {
                $banco->estado= "ANULADO";
                $banco->update();
            }
        }

        //Registro de actividad
        $descripcion = "SE MODIFICÓ LA EMPRESA: ". $empresa->razon_social.' con el RUC '.$empresa->ruc;
        $gestion = "EMPRESAS";
        modificarRegistro($empresa, $descripcion , $gestion);

        Session::flash('success','Empresa modificada.');
        return redirect()->route('mantenimiento.empresas.index')->with('modificar', 'success');

    }

    public function certificate(Request $request)
    {
        if ($request->hasFile('certificado')) {
            try {

                $file = $request->file('certificado');
                $contra = $request->get('contra_certificado');
                $pfx = file_get_contents($file);
                $pem = GenerateCertificate::typePEM($pfx, $contra);
                $certificado64 = base64_encode($pem);

                return [
                    'success' => true,
                    'certificado' => $certificado64,
                ];
            } catch (Exception $e) {
                return [
                    'success' => false,
                    'message' =>  $e->getMessage()
                ];
            }
        }
        return [
            'success' => false,
            'message' =>  'Error',
        ];
    }
    
    public function serie($id)
    {
        $tipos = tipos_venta();
        foreach ($tipos as $tipo) {
            if ($id== '133' || $id== '134' ) {
                if ($tipo->id == $id ) {
                    $empresas_numeracion = Numeracion::where('tipo_comprobante', $id)->where('estado','ACTIVO')->get();
                    $serie = $tipo->parametro.'0'.(count($empresas_numeracion)+1);
                    return $serie;
                }
            }else{
                if ($tipo->id == $id ) {
                    $empresas_numeracion = Numeracion::where('tipo_comprobante', $id)->where('estado','ACTIVO')->get();
                    $serie = $tipo->parametro.'00'.(count($empresas_numeracion)+1);
                    return $serie;
                } 
            }

        }
    }

}
