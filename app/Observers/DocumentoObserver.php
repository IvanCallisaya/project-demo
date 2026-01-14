<?php

namespace App\Observers;

use App\Models\Documento;
use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Illuminate\Support\Facades\Log;

class DocumentoObserver
{
    public function created(Documento $documento): void
    {
        // 1. LÓGICA DE GOOGLE CALENDAR
        
        if ($documento->fecha_plazo_entrega) {
            $this->agendarEnGoogleCalendar($documento);
        }
    }

    private function agendarEnGoogleCalendar($documento)
    {
        try {
            $client = new Client();
            $client->setAuthConfig(storage_path('app/google/service-account.json'));
            $client->addScope(Calendar::CALENDAR);
            
            $service = new Calendar($client);
            $calendarId = 'ivanedcali@gmail.com'; // ID del calendario destino

            $producto = $documento->producto;
            $empresa = $producto->clienteEmpresa->nombre_comercial ?? 'Cliente';

            $event = new Event([
                'summary' => "Vencimiento: {$documento->nombre}",
                'description' => "Empresa: {$empresa}\nProducto: {$producto->nombre}\nURL Drive: {$documento->url}",
                'start' => ['date' => $documento->fecha_plazo_entrega],
                'end'   => ['date' => $documento->fecha_plazo_entrega],
                'reminders' => [
                    'useDefault' => false,
                    'overrides' => [
                        ['method' => 'email', 'minutes' => 24 * 60], // 1 día antes
                        ['method' => 'popup', 'minutes' => 60],      // 1 hora antes
                    ],
                ],
            ]);

            $service->events->insert($calendarId, $event);
            Log::info("Evento agendado en Google Calendar para Documento ID: {$documento->id}");

        } catch (\Exception $e) {
            Log::error("Error en Google Calendar: " . $e->getMessage());
        }
    }
}