<?php

namespace App\Http\Controllers;

use App\Mail\DealerRequestReceived;
use App\Models\DealerRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class DealerController extends Controller
{
    public function show(): View
    {
        return view('pages.dealers');
    }

    public function store(Request $request): RedirectResponse
    {
        // Bolsa de errores propia para que no se mezcle con el formulario de contacto
        $data = $request->validateWithBag('dealer', [
            'nombre' => ['required', 'string', 'max:80'],
            'apellido' => ['required', 'string', 'max:80'],
            'ciudad' => ['required', 'string', 'max:80'],
            'telefono' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:120'],
            'empresa' => ['nullable', 'string', 'max:120'],
            'origen' => ['nullable', 'in:modal,pagina'],
        ]);

        $dealer = DealerRequest::query()->create([
            'first_name' => $data['nombre'],
            'last_name' => $data['apellido'],
            'city' => $data['ciudad'],
            'phone' => $data['telefono'],
            'email' => $data['email'],
            'company' => $data['empresa'] ?? null,
            'source' => $data['origen'] ?? 'pagina',
        ]);

        try {
            Mail::to(config('kodu.notify_email'))->send(new DealerRequestReceived($dealer));
        } catch (Throwable $e) {
            Log::error('No se pudo enviar el aviso de distribuidor', ['error' => $e->getMessage()]);
        }

        return back()
            ->with('dealer_status', 'Gracias, '.$data['nombre'].'. Recibimos tu solicitud; un asesor te contactará en menos de 48 horas hábiles.')
            ->with('dealer_origin', $data['origen'] ?? 'pagina');
    }
}
