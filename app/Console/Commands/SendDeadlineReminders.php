<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Documento;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class SendDeadlineReminders extends Command
{
    // Nombre del comando para ejecutarlo manualmente: php artisan mail:deadlines
    protected $signature = 'mail:deadlines';
    protected $description = 'Envia correos para los documentos que vencen hoy';

    public function handle()
    {
        // 1. Buscar documentos que vencen HOY
        $hoy = Carbon::today()->format('Y-m-d');
        
        $documentos = Documento::with(['producto.clienteEmpresa'])
            ->whereDate('fecha_plazo_entrega', $hoy)
            ->get();

        if ($documentos->isEmpty()) {
            $this->info("No hay vencimientos para hoy.");
            return;
        }

        foreach ($documentos as $doc) {
            $emailDestino = $doc->producto->clienteEmpresa->email_principal ?? 'vanesalazartesoraire@gmail.com';
            $nombreEmpresa = $doc->producto->clienteEmpresa->nombre ?? 'Cliente';
            
            $mensaje = "Hola {$nombreEmpresa}, le recordamos que hoy vence el plazo para el documento: {$doc->nombre}. Relacionado al producto: {$doc->producto->nombre}.";

            // 2. Consumir tu API interna de envío de mail
            // Usamos la URL completa. Si es local puede ser http://localhost/api/send-mail
            $response = Http::post(url('http://109.199.102.106:3000/api/send-mail'), [
                'destino' => $emailDestino,
                'mensaje' => $mensaje,
            ]);

            if ($response->successful()) {
                $this->info("Correo enviado para: " . $doc->nombre);
            } else {
                $this->error("Error enviando correo para: " . $doc->nombre);
            }
        }
    }
}