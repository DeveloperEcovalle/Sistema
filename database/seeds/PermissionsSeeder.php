<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       	// Lista de permisos
		$permission = Permission::create(['name' => 'crud_actividad']);
		$permission = Permission::create(['name' => 'crud_almacen']);
		$permission = Permission::create(['name' => 'crud_articulo']);
		$permission = Permission::create(['name' => 'crud_categoria']);
		$permission = Permission::create(['name' => 'crud_cliente']);
		$permission = Permission::create(['name' => 'crud_composicion_producto']);
		$permission = Permission::create(['name' => 'crud_cotizacion']);
		$permission = Permission::create(['name' => 'crud_doccompra']);
		$permission = Permission::create(['name' => 'crud_docventa']);
		$permission = Permission::create(['name' => 'crud_colaborador']);
		$permission = Permission::create(['name' => 'crud_empresa']);
		$permission = Permission::create(['name' => 'crud_familia']);
		$permission = Permission::create(['name' => 'crud_guia_interna']);
		$permission = Permission::create(['name' => 'crud_linea_produccion']);
		$permission = Permission::create(['name' => 'crud_maquinaria_equipo']);
		$permission = Permission::create(['name' => 'crud_orden']);
		$permission = Permission::create(['name' => 'crud_caja_chica']);
		$permission = Permission::create(['name' => 'crud_producto']);
		$permission = Permission::create(['name' => 'crud_programacion_produccion']);
		$permission = Permission::create(['name' => 'crud_prototipo']);
		$permission = Permission::create(['name' => 'crud_proveedor']);
		$permission = Permission::create(['name' => 'crud_registro_sanitario']);
		$permission = Permission::create(['name' => 'crud_rol']);
		$permission = Permission::create(['name' => 'crud_subfamilia']);
		$permission = Permission::create(['name' => 'crud_general']);
		$permission = Permission::create(['name' => 'crud_talonario']);
		$permission = Permission::create(['name' => 'crud_usuario']);
		$permission = Permission::create(['name' => 'crud_vendedor']);
		$permission = Permission::create(['name' => 'crud_permiso']);

		// Lista de roles
		$admin = Role::create(['name' => 'Admin']);

		$admin->givePermissionTo([
			 'crud_actividad',
			 'crud_almacen',
			 'crud_articulo',
			 'crud_categoria',
			 'crud_cliente',
			 'crud_composicion_producto',
			 'crud_cotizacion',
			 'crud_doccompra',
			 'crud_docventa',
			 'crud_colaborador',
			 'crud_empresa',
			 'crud_familia',
			 'crud_guia_interna',
			 'crud_linea_produccion',
			 'crud_maquinaria_equipo',
			 'crud_orden',
			 'crud_caja_chica',
			 'crud_producto',
			 'crud_programacion_produccion',
			 'crud_prototipo',
			 'crud_proveedor',
			 'crud_registro_sanitario',
			 'crud_rol',
			 'crud_subfamilia',
			 'crud_general',
			 'crud_talonario',
			 'crud_usuario',
			 'crud_vendedor',
			 'crud_permiso',
		]);

		// Asignamos roles a los usuarios
		$user = User::find(1);
		$user->assignRole('Admin');
    }
}
