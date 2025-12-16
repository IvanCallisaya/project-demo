<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Asegúrate de importar tu modelo User
use Illuminate\Support\Facades\Hash; // Importar el Facade Hash
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            // Datos del primer usuario (Administrador)
            $userData = [
                'name' => 'Admin AgilizaTech',
                'email' => 'admin@agilizatech.com',
                'last_name' => 'Tech',
                'password' => Hash::make('12345678'),
                'empresa_id' => 1,
            ];

            $permission = new User();
            $permission->id = 1;
            $permission->name = 'Admin AgilizaTech';
            $permission->email = 'admin@agilizatech.com';
            $permission->last_name = 'Tech';
            $permission->password = Hash::make('12345678');
            $permission->empresa_id = 1;
            $permission->save();

            $permission = new User();
            $permission->id = 2;
            $permission->name = 'Admin Empresa2';
            $permission->email = 'admin@empresa2.com';
            $permission->last_name = '2';
            $permission->password = Hash::make('12345678');
            $permission->empresa_id = 2;
            $permission->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error al crear usuario en el seeder: ' . $e->getMessage());
            $this->command->error('Fallo la creación del usuario.');
            throw $e;
        }
    }
}
