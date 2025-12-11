<?php

use App\Http\Controllers\ClienteEmpresaController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\LaboratorioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubCategoriaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('cliente_empresa', ClienteEmpresaController::class);
Route::resource('laboratorio', LaboratorioController::class);
Route::resource('producto', ProductoController::class);
Route::resource('subcategoria', SubCategoriaController::class);

Route::prefix('laboratorio/{laboratorio}/producto')->group(function () {

    // POST: Adjuntar (Se mantiene igual, solo necesita {laboratorio} y 'producto_id' en el Request)
    Route::post('attach', [LaboratorioController::class, 'attachProducto'])->name('laboratorio.producto.attach');

    // DELETE: Remover (Ahora recibe el ID de la fila pivot)
    Route::delete('detach/{pivotRecord}', [LaboratorioController::class, 'detachProducto'])->name('laboratorio.producto.detach');

    // GET: Mostrar el formulario para editar el pivot específico.
    Route::get('{pivotRecord}/edit-pivot', [LaboratorioController::class, 'editPivot'])->name('laboratorio.producto.edit_pivot');

    // PUT/PATCH: Actualizar el pivot específico.
    Route::put('{pivotRecord}/update-pivot', [LaboratorioController::class, 'updatePivot'])->name('laboratorio.producto.update_pivot');
});

Route::prefix('cliente_empresa/{clienteEmpresa}')->name('cliente.')->group(function () {
    // 3. Laboratorios
    Route::get('laboratorios', [ClienteEmpresaController::class, 'laboratoriosIndex'])->name('laboratorios.index');
    // 5. Documentos
    Route::get('documentos', [ClienteEmpresaController::class, 'documentosIndex'])->name('documentos.index');

    // Puedes agregar más rutas aquí (ej: documentos, proyectos, etc.)
});
Route::post('/productos/{laboratorioProductoId}/documento/upload', [DocumentoController::class, 'subirDocumento'])
    ->name('documento.subir');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('users', \App\Http\Controllers\UserController::class)
    ->middleware(['auth', 'role:admin']);


require __DIR__ . '/auth.php';
