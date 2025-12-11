<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\LaboratorioProducto; // Asegúrate de importar el modelo

class DocumentoController extends Controller
{

    /**
     * Sube un documento a Google Drive y registra la URL en la base de datos.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $laboratorioProductoId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function subirDocumento(Request $request, $laboratorioProductoId)
    {
        // 1. Validar la solicitud y el archivo
        $request->validate([
            'documento' => 'required|file|max:20480', // Máx 20MB
        ]);

        // 2. Obtener los nombres de las entidades
        try {
            // Carga la relación LaboratorioProducto con sus relaciones anidadas
            $relacion = LaboratorioProducto::with('laboratorio.cliente', 'producto')
                ->findOrFail($laboratorioProductoId);

            $nombreCliente = $relacion->laboratorio->cliente->nombre;
            $nombreLaboratorio = $relacion->laboratorio->nombre;
            $nombreProducto = $relacion->producto->nombre;

            $file = $request->file('documento');
        } catch (\Exception $e) {
            // Manejar si la relación o la entidad no se encuentran
            return back()->with('error', 'No se pudo encontrar el laboratorio o producto asociado.');
        }

        // 3. Enviar el archivo a la API de Node.js/Express (Ruta y host modificados)
        try {
            // NOTA: Revisa la URL. Se asume que ahora usa /api/upload-document y el puerto 3000
            $response = Http::asMultipart()->post('http://109.199.102.106:3000/api/upload-document', [
                'cliente' => $nombreCliente,
                'laboratorio' => $nombreLaboratorio,
                'producto' => $nombreProducto,
                'archivo' => [
                    'name' => 'archivo', // Nombre del campo en Multer
                    'contents' => fopen($file->path(), 'r'),
                    'filename' => $file->getClientOriginalName(),
                ],
            ]);
        } catch (\Exception $e) {
            // Error de conexión, timeout, etc.
            return back()->with('error', 'Error de conexión con el servicio de subida de archivos (Node.js).');
        }


        // 4. Manejar la respuesta JSON de la API
        if ($response->successful()) {
            $data = $response->json(); // Decodificar la respuesta JSON
            // 5. Guardar en el modelo Documento
            try {
                $relacion->laboratorio->cliente->update(['url_carpeta_drive' => $data['url_carpeta_cliente']]);
                Documento::create([
                    'laboratorio_producto_id' => $laboratorioProductoId,
                    'nombre' => $data['nombre_archivo'], // Nombre del archivo de la respuesta JSON
                    'url' => $data['url_drive'],       // URL de Drive de la respuesta JSON
                    'fecha_plazo_entrega' => $request->input('fecha_plazo_entrega'),
                    'fecha_recojo' => $request->input('fecha_recojo'),
                ]);
            } catch (\Exception $e) {
                // Manejar error de base de datos
                return back()->with('error', 'Documento subido a Drive, pero falló al guardar el registro en la base de datos.');
            }

            // Respuesta final
            return back()->with('success', '✅ Documento subido a Google Drive y registrado exitosamente. URL: ' . $data['url_drive']);
        } else {
            // La API de Node.js falló. Obtenemos el mensaje de error del JSON (si existe)
            $errorData = $response->json();
            $errorMessage = $errorData['error'] ?? $response->body();

            return back()->with('error', "❌ Error al subir a Drive. Código: {$response->status()}. Respuesta: {$errorMessage}");
        }
    }
}
