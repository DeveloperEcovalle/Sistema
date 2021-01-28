<?php

use Illuminate\Database\Seeder;
use App\Mantenimiento\Empresa\Empresa;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Agroensancha S.R.L
        $empresa = new Empresa();
        $empresa->ruc = '20482089594';
        $empresa->razon_social = 'AGROENSANCHA S.R.L.';
        $empresa->razon_social_abreviada = 'AGROENSANCHA S.R.L.';
        $empresa->direccion_fiscal = 'JR. JOSE MARTI NRO. 2184 OTR.  LA ESPERANZA  (A TRES CUADRAS DEL PUENTE CAPRICORNIO) - LA LIBERTAD - TRUJILLO - TRUJILLO';
        $empresa->direccion_llegada = 'TRUJILLO';
        $empresa->dni_representante = '70004110';
        $empresa->nombre_representante = 'AXEL GASTON KEVIN GUTIERREZ LOPEZ';
        $empresa->num_asiento = 'A00001';
        $empresa->num_partida = '11036086';
        $empresa->activo = '1';
        $empresa->save();
    }
}
