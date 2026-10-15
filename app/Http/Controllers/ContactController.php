<?php

namespace App\Http\Controllers;

use App\Mail\ContactReceived;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('pages.contact');
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
        ]);

        $dial = collect(config('kodu.phone_countries'))->firstWhere('code', $data['pais'] ?? 'MX')['dial'] ?? '';

        $message = ContactMessage::query()->create([
            'first_name' => $data['nombre'],
            'last_name' => $data['apellido'],
            'email' => $data['email'],
            'phone' => trim($dial.' '.$data['telefono']),
            'company' => $data['empresa'] ?? null,
        ]);

        try {
            Mail::to(config('kodu.notify_email'))->send(new ContactReceived($message));
        } catch (Throwable $e) {
            Log::error('No se pudo enviar el aviso de contacto', ['error' => $e->getMessage()]);
        }

        return redirect()->route('contact')
            ->with('status', 'Gracias, '.$data['nombre'].'. Recibimos tu mensaje y te contactaremos pronto.');
    }
}
