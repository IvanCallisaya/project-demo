<?php

use App\Http\Controllers\ClienteEmpresaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\LaboratorioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProductoReporteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubCategoriaController;
use App\Http\Controllers\SucursalController;
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

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::prefix('/')->group(function () {
        Route::get('', [DashboardController::class, 'index'])->name('dashboard');
    });

    Route::resource('cliente_empresa', ClienteEmpresaController::class);
    Route::resource('laboratorio', LaboratorioController::class);
    Route::get('presolicitud', [ProductoController::class, 'preSolicitudIndex'])->name('presolicitud.index');
    Route::get('presolicitud/create', [ProductoController::class, 'preSolicitudCreate'])->name('presolicitud.create');
    Route::post('presolicitud', [ProductoController::class, 'preSolicitudStore'])->name('presolicitud.store');
    Route::patch('/producto/{id}/cambiar-estado', [ProductoController::class, 'cambiarEstado'])->name('producto.cambiarEstado');
    Route::resource('producto', ProductoController::class);
    Route::resource('subcategoria', SubCategoriaController::class);
    Route::resource('sucursal', SucursalController::class);
    Route::get('reporte/producto', [ProductoReporteController::class, 'index'])->name('reporte.producto');
    Route::get('reporte/presolicitud', [ProductoReporteController::class, 'indexPresolicitud'])->name('reporte.presolicitud');
    Route::get('/reportes/documentos', [ProductoReporteController::class, 'indexDocumento'])
        ->name('reporte.documento.index');
    Route::prefix('configuracion')->group(function () {
        Route::get('/documento', [DocumentoController::class, 'index'])->name('configuracion.documento');
    });
    Route::prefix('laboratorio/{laboratorio}/producto')->group(function () {});
    Route::post('/empresas/enviar-notificacion', [ClienteEmpresaController::class, 'enviarNotificacionRevision'])
        ->name('cliente_empresa.notificar');

    Route::prefix('cliente_empresa/{clienteEmpresa}')->name('cliente.')->group(function () {
        // 3. Laboratorios
        Route::get('laboratorios', [ClienteEmpresaController::class, 'laboratoriosIndex'])->name('laboratorios.index');
        Route::get('sucursales', [ClienteEmpresaController::class, 'sucursalesIndex'])->name('sucursales.index');
        // 5. Documentos
        Route::get('documentos', [ClienteEmpresaController::class, 'documentosIndex'])->name('documentos.index');
        Route::get('productos', [ClienteEmpresaController::class, 'productosIndex'])->name('productos.index');

        // Puedes agregar más rutas aquí (ej: documentos, proyectos, etc.)
    });
    Route::post('/productos/{laboratorioProductoId}/documento/upload', [DocumentoController::class, 'subirDocumento'])
        ->name('documento.subir');
});

Route::resource('users', \App\Http\Controllers\UserController::class)
    ->middleware(['auth', 'role:admin']);


require __DIR__ . '/auth.php';
