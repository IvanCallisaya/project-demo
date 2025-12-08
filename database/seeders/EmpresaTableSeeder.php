<?php

namespace Database\Seeders;

use App\Models\Empresa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmpresaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            $permission = new Empresa();
            $permission->id = 1;
            $permission->nombre_comercial = 'Agiliza Tech';
            $permission->razon_social = 'Agiliza Technologies SRL';
            $permission->nit = '900123456';
            $permission->direccion = 'Avenida 4to Anillo, Nro. 4180, Barrio Hamacas, Plan 12, UV: 65, Mza: 22, entre Av. Banzer y Av. Beni';
            $permission->telefono = '+59173194745';
            $permission->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error al crear empresa: ' . $e->getMessage());
        }
    }
}
