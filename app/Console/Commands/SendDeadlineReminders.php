<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Documento;
use App\Models\Producto;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendDeadlineReminders extends Command
{
    protected $signature = 'mail:deadlines';
    protected $description = 'Envia correos para documentos que vencen hoy y productos que vencen en 120 dias via ZeptoMail';

    public function handle()
    {
        $this->info("Iniciando escaneo de vencimientos...");
        $this->procesarVencimientosDocumentos();
        $this->procesarVencimientosProductos();
        $this->info("Proceso finalizado.");
    }

    private function procesarVencimientosDocumentos()
    {
        $hoy = Carbon::today()->format('Y-m-d');
        $documentos = Documento::with(['producto.clienteEmpresa'])
            ->whereDate('fecha_plazo_entrega', $hoy)
            ->get();

        foreach ($documentos as $doc) {
            $emailDestino = $doc->producto->clienteEmpresa->email_principal ?? 'vanesalazartesoraire@gmail.com';
            $asunto = "Recordatorio de Plazo: " . $doc->nombre;
            $mensaje = "Hola, le recordamos que hoy vence el plazo para el documento: <b>{$doc->nombre}</b>. Relacionado al producto: {$doc->producto->nombre}.";

            $this->enviarEmail($emailDestino, $asunto, $mensaje, "Documento: " . $doc->nombre);
        }
    }

    private function procesarVencimientosProductos()
    {
        $fechaObjetivo = Carbon::today()->addDays(120)->format('Y-m-d');
        $productos = Producto::with(['clienteEmpresa'])
            ->whereDate('fecha_vencimiento', $fechaObjetivo)
            ->get();

        if ($productos->isEmpty()) {
            $this->info("No hay productos que venzan en 120 días ($fechaObjetivo).");
            return;
        }

        foreach ($productos as $prod) {
            $emailDestino = $prod->clienteEmpresa->email_principal ?? 'vanesalazartesoraire@gmail.com';
            $nombreEmpresa = $prod->clienteEmpresa->nombre ?? 'Cliente';
            $asunto = "AVISO: Vencimiento de Registro en 120 días";

            $mensaje = "Estimados {$nombreEmpresa}, les informamos que el producto <b>{$prod->nombre}</b> (Código: {$prod->codigo}) vencerá en 120 días (Fecha: " . Carbon::parse($prod->fecha_vencimiento)->format('d/m/Y') . "). Por favor, inicie los trámites de renovación.";

            $this->enviarEmail($emailDestino, $asunto, $mensaje, "Producto: " . $prod->nombre);
        }
    }

    /**
     * Adaptado para conectar con la API de Node.js + ZeptoMail
     */
    private function enviarEmail($destino, $asunto, $mensaje, $referencia)
    {
        $url = 'http://109.199.102.106:3000/api/zeptomail/send';

        // Creamos un diseño HTML profesional
        $htmlMensaje = "
    <div style='font-family: Arial, sans-serif; background-color: #f4f4f7; padding: 20px; color: #333;'>
        <div style='max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #e1e1e1;'>
            <div style='background-color: #6c2bd9; padding: 20px; text-align: center;'>
                <h1 style='color: #ffffff; margin: 0; font-size: 20px;'>Notificación de Sistema</h1>
            </div>
            <div style='padding: 30px; line-height: 1.6;'>
                <h2 style='color: #1a1a1a; margin-top: 0;'>Estimado Cliente,</h2>
                <p style='font-size: 16px; color: #555;'>Le enviamos este aviso automático relacionado con sus trámites:</p>
                <div style='background-color: #f9f9f9; border-left: 4px solid #6c2bd9; padding: 15px; margin: 20px 0;'>
                    <p style='margin: 0; font-size: 15px;'>$mensaje</p>
                </div>
                <p style='font-size: 14px; color: #888;'>Por favor, no responda a este correo. Si tiene alguna duda, contacte con el área encargada.</p>
                <div style='text-align: center; margin-top: 30px;'>
                    <a href='http://tusistema.com' style='background-color: #6c2bd9; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Acceder al Sistema</a>
                </div>
            </div>
            <div style='background-color: #f4f4f7; padding: 15px; text-align: center; font-size: 12px; color: #aaa;'>
                &copy; " . date('Y') . " Project Demo - Gestión de Calidad
            </div>
        </div>
    </div>";

        $response = Http::post($url, [
            'to'      => $destino,
            'subject' => $asunto,
            'message' => $htmlMensaje, // Enviamos el HTML completo
        ]);

        if ($response->successful()) {
            $this->info("✓ Enviado: $referencia");
            Log::info("Correo enviado exitosamente a $destino para $referencia.");
        } else {
            $this->error("✗ Error en $referencia: " . $response->body());
            Log::error("Error al enviar correo a $destino para $referencia: " . $response->body());
        }
    }
}
