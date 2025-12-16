<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class UnidadMedidaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        
        $unidades = [
            // Unidades de Conteo/Inventario
            ['nombre' => 'Unidad', 'simbolo' => 'u', 'descripcion' => 'Unidad de conteo simple, elemento único.', 'empresa_id' => '1', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Caja', 'simbolo' => 'cj', 'descripcion' => 'Unidad de empaque o agrupación.', 'empresa_id' => '1', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Pieza', 'simbolo' => 'pz', 'descripcion' => 'Elemento individual o componente.', 'empresa_id' => '1', 'created_at' => $now, 'updated_at' => $now],
            
            // Unidades de Masa (Peso)
            ['nombre' => 'Kilogramo', 'simbolo' => 'kg', 'descripcion' => 'Unidad de masa del Sistema Internacional (SI).', 'empresa_id' => '1', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Gramo', 'simbolo' => 'g', 'descripcion' => 'Milésima parte de un kilogramo.', 'empresa_id' => '1', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Miligramo', 'simbolo' => 'mg', 'descripcion' => 'Milésima parte de un gramo.', 'empresa_id' => '1', 'created_at' => $now, 'updated_at' => $now],
            
            // Unidades de Volumen (Líquidos/Muestras)
            ['nombre' => 'Litro', 'simbolo' => 'L', 'descripcion' => 'Unidad de volumen.', 'empresa_id' => '1', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Mililitro', 'simbolo' => 'mL', 'descripcion' => 'Milésima parte de un litro.', 'empresa_id' => '1', 'created_at' => $now, 'updated_at' => $now],
        ];

        // Insertar los datos, ignorando duplicados (útil si la tabla ya tiene datos)
        DB::table('unidad_medida')->insertOrIgnore($unidades);
    }
}