<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CategoriaSubCategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            $data = [
                'ALIMENTOS' => [
                    ['nombre' => 'Aditivos', 'codigo' => 'A01'],
                    ['nombre' => 'Alimentos balanceados', 'codigo' => 'A02'],
                    ['nombre' => 'Premezclas', 'codigo' => 'A03'],
                    ['nombre' => 'Sales minerales', 'codigo' => 'A04'],
                    ['nombre' => 'Suplemento alimenticio', 'codigo' => 'A05'],
                ],
                'BIOLOGICO' => [
                    ['nombre' => 'Kit de diagnostico', 'codigo' => 'B01'],
                    ['nombre' => 'Vacunas', 'codigo' => 'B02'],
                    ['nombre' => 'Probioticos', 'codigo' => 'B03'],
                    ['nombre' => 'Suero Hiperinmunes', 'codigo' => 'B04'],
                ],
                'FARMACOLOGICOS' => [
                    ['nombre' => 'Alimentos balanceados medicados', 'codigo' => null],
                    ['nombre' => 'Aminoacidos', 'codigo' => null],
                    ['nombre' => 'Analgesicos', 'codigo' => null],
                    ['nombre' => 'Anestesicos', 'codigo' => null],
                    ['nombre' => 'Antialergicos', 'codigo' => null],
                    ['nombre' => 'Antianemicos', 'codigo' => null],
                    ['nombre' => 'Antibioticos', 'codigo' => null],
                    ['nombre' => 'Anticoccidiano', 'codigo' => null],
                    ['nombre' => 'Antidiarreico', 'codigo' => null],
                    ['nombre' => 'Antinemetico', 'codigo' => null],
                    ['nombre' => 'Antiflamatorio', 'codigo' => null],
                    ['nombre' => 'Antimicotico', 'codigo' => null],
                    ['nombre' => 'Antipapilomatoso', 'codigo' => null],
                    ['nombre' => 'Antiparasitario ectoparasitida', 'codigo' => null],
                    ['nombre' => 'Antiparasitario endectocida', 'codigo' => null],
                    ['nombre' => 'Anitparasitario endoparaticida', 'codigo' => null],
                    ['nombre' => 'Antipiretico', 'codigo' => null],
                    ['nombre' => 'Antiprotozoarios', 'codigo' => null],
                    ['nombre' => 'Antisepticos', 'codigo' => null],
                    ['nombre' => 'Antitoxicos', 'codigo' => null],
                    ['nombre' => 'Broncodilatador/mucolitico/expectorante', 'codigo' => null],
                    ['nombre' => 'Cicatrizante', 'codigo' => null],
                    ['nombre' => 'Colirio', 'codigo' => null],
                    ['nombre' => 'Corticoide', 'codigo' => null],
                    ['nombre' => 'Diuretico', 'codigo' => null],
                    ['nombre' => 'Hormonas y anabolicos', 'codigo' => null],
                    ['nombre' => 'Mineralizantes', 'codigo' => null],
                    ['nombre' => 'Modificadores organicos', 'codigo' => null],
                    ['nombre' => 'Prebiotico', 'codigo' => null],
                    ['nombre' => 'Promotor de crecimiento', 'codigo' => null],
                    ['nombre' => 'Purgantes', 'codigo' => null],
                    ['nombre' => 'Sueros reconstituyente', 'codigo' => null],
                    ['nombre' => 'Tranquilizantes', 'codigo' => null],
                    ['nombre' => 'Vasodilatadores', 'codigo' => null],
                    ['nombre' => 'Vitaminas/minerales/aminoacidos', 'codigo' => null],
                    ['nombre' => 'Vitaminico/mineralizante', 'codigo' => null],
                    ['nombre' => 'Vitaminas', 'codigo' => null],
                ],
                // NUEVAS CATEGORÍAS
                'HOMEOPEATICOS Y OTROS' => [
                    ['nombre' => 'Absorbentes', 'codigo' => null],
                    ['nombre' => 'Acidificantes', 'codigo' => null],
                    ['nombre' => 'Antiquiropteros', 'codigo' => null],
                    ['nombre' => 'Colonias y perfumes', 'codigo' => null],
                    ['nombre' => 'Desinfectantes', 'codigo' => null],
                    ['nombre' => 'Detergentes', 'codigo' => null],
                    ['nombre' => 'Homeopaticos', 'codigo' => null],
                    ['nombre' => 'Inhinidores de crecimiento', 'codigo' => null],
                    ['nombre' => 'Insecticidas', 'codigo' => null],
                    ['nombre' => 'Jabones neutros', 'codigo' => null],
                    ['nombre' => 'Larvacidas', 'codigo' => null],
                    ['nombre' => 'Locion terapeutica', 'codigo' => null],
                    ['nombre' => 'Rodenticida', 'codigo' => null],
                    ['nombre' => 'Secuestrante de micotoxinas', 'codigo' => null],
                    ['nombre' => 'Shampoo neutro', 'codigo' => null],
                    ['nombre' => 'Talco neutro', 'codigo' => null],
                ],
                'INSUMOS PARA PRODUCCION PECUARIA' => [
                    ['nombre' => 'Aditivos', 'codigo' => null],
                    ['nombre' => 'Premezclas', 'codigo' => null],
                    ['nombre' => 'Sales minerales', 'codigo' => null],
                ],
            ];

            // 1. Insertar Categorías y obtener IDs
            $categoriaIds = [];
            foreach (array_keys($data) as $categoriaNombre) {
                // Primero, verifica si la categoría ya existe para evitar errores
                $existingCategory = DB::table('categoria')->where('nombre', $categoriaNombre)->first();

                if ($existingCategory) {
                    $categoriaIds[$categoriaNombre] = $existingCategory->id;
                } else {
                    $codigo = Str::upper(Str::substr(Str::slug($categoriaNombre, ''), 0, 4));

                    $id = DB::table('categoria')->insertGetId([
                        'nombre' => $categoriaNombre,
                        'codigo' => $codigo,
                        'empresa_id' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $categoriaIds[$categoriaNombre] = $id;
                }
            }

            // 2. Insertar Subcategorías usando los IDs
            $subcategoriasToInsert = [];
            foreach ($data as $categoriaNombre => $subcategorias) {
                $categoriaId = $categoriaIds[$categoriaNombre];

                foreach ($subcategorias as $subcategoria) {
                    // Evitar duplicados de subcategoría dentro de la misma categoría
                    $exists = DB::table('subcategoria')
                        ->where('categoria_id', $categoriaId)
                        ->where('nombre', $subcategoria['nombre'])
                        ->exists();

                    if (!$exists) {
                        $subcategoriasToInsert[] = [
                            'categoria_id' => $categoriaId,
                            'nombre' => $subcategoria['nombre'],
                            'codigo' => $subcategoria['codigo'], // Mantenemos el código original o null
                            'empresa_id' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }

            // Insertar todas las subcategorías en un solo lote
            if (!empty($subcategoriasToInsert)) {
                DB::table('subcategoria')->insert($subcategoriasToInsert);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error al insertar Categorías/Subcategorías: ' . $e->getMessage());
            throw $e;
        }
    }
}
