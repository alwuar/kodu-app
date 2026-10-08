<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ContactController extends Controller
{
    public const SUBJECTS = [
        'general' => 'Información general',
        'distribuidor' => 'Quiero ser distribuidor',
        'refacciones' => 'Refacciones y servicio técnico',
        'cotizacion' => 'Cotización',
    ];

    public function show(Request $request): View
    {
        $subject = $request->query('asunto');

        return view('pages.contact', [
            'subject' => array_key_exists($subject, self::SUBJECTS) ? $subject : 'general',
            'subjects' => self::SUBJECTS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:80'],
            'apellido' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email', 'max:120'],
            'pais' => ['nullable', 'string', 'max:4'],
            'telefono' => ['required', 'string', 'max:30'],
            'empresa' => ['nullable', 'string', 'max:120'],
            'asunto' => ['nullable', 'in:'.implode(',', array_keys(self::SUBJECTS))],
        ]);

        Log::info('Nuevo contacto desde el sitio', $data);

        return redirect()
            ->route('contact', array_filter(['asunto' => $data['asunto'] ?? null]))
            ->with('status', 'Gracias, '.$data['nombre'].'. Recibimos tu mensaje y te contactaremos pronto.');
    }
}
