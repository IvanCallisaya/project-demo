<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\LaboratorioProducto; // Asegúrate de importar el modelo
use App\Models\Producto;
use Illuminate\Support\Facades\Log;

class DocumentoController extends Controller
{

    public function index(Request $request)
    {

        $documentos = Documento::with([
            'laboratorioProducto.laboratorio',
            'laboratorioProducto.producto',
        ]);

        $searchQuery = $request->get('q');
        if ($searchQuery) {
            $documentos->where('nombre', 'LIKE', '%' . $searchQuery . '%');
            $documentos->whereHas('laboratorioProducto.laboratorio.cliente', function ($q) use ($searchQuery) {
                $q->where('nombre', 'LIKE', '%' . $searchQuery . '%');
            });
        }

        // 3. Obtener y Paginar los resultados
        $docs = $documentos->paginate(10);
        Log::info(json_encode($docs));

        // 4. Devolver la vista
        return view('configuracion.documento.index', compact('docs'));
    }

    /**
     * Sube un documento a Google Drive y registra la URL en la base de datos.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $laboratorioProductoId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function subirDocumento(Request $request, $productoId)
    {
        try {
            $request->validate([
                'documento' => 'required|file|max:20480',
                // Es buena práctica validar también las fechas si las esperas
                'fecha_plazo_entrega' => 'nullable|date',
                'fecha_recojo' => 'nullable|date',
            ]);

            $producto = Producto::with(['clienteEmpresa', 'sucursal'])
                ->findOrFail($productoId);

            $file = $request->file('documento');

            // MODIFICACIÓN AQUÍ: Usamos attach para el archivo y post para los campos
            $response = Http::asMultipart()
                ->attach(
                    'archivo',                       // Nombre del campo que espera tu API Node
                    fopen($file->path(), 'r'),      // Contenido del archivo
                    $file->getClientOriginalName()  // Nombre original
                )
                ->post('http://109.199.102.106:3000/api/upload-document', [
                    'cliente'     => $producto->clienteEmpresa->nombre_comercial ?? $producto->clienteEmpresa->nombre,
                    'laboratorio' => $producto->sucursal->nombre,
                    'producto'    => $producto->nombre,
                ]);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error en API Node',
                    'error'   => $response->body(),
                ], 500);
            }

            $data = $response->json();

            // Actualizamos la URL de la carpeta en el cliente si la API la devuelve
            $producto->clienteEmpresa->update([
                'url_carpeta_drive' => $data['url_carpeta_cliente'] ?? $producto->clienteEmpresa->url_carpeta_drive,
            ]);

            // Creamos el registro del documento
            // Esto disparará automáticamente el Observer para Google Calendar
            Documento::create([
                'producto_id'         => $productoId,
                'nombre'              => $data['nombre_archivo'] ?? $file->getClientOriginalName(),
                'url'                 => $data['url_drive'] ?? null,
                'fecha_plazo_entrega' => $request->fecha_plazo_entrega,
                'fecha_recojo'        => $request->fecha_recojo,
                'empresa_id'          => auth()->user()->empresa_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => '✅ Documento subido y registrado correctamente.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error al subir el documento',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
