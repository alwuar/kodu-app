<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HomeSettingsController extends Controller
{
    public function edit(): View
    {
        return view('admin.home.edit', [
            'video' => Setting::get('hero_video'),
            'poster' => Setting::get('hero_poster'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'video' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm', 'max:51200'],
            'poster' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_video' => ['nullable', 'boolean'],
            'remove_poster' => ['nullable', 'boolean'],
        ], [
            'video.mimetypes' => 'El video debe ser MP4 o WebM.',
            'video.max' => 'El video no puede pesar más de 50 MB. Para que el sitio cargue rápido, lo ideal es menos de 10 MB.',
        ]);

        $this->replace($request, 'video', 'hero_video', 'inicio');
        $this->replace($request, 'poster', 'hero_poster', 'inicio');

        return back()->with('status', 'Banner del inicio actualizado.');
    }

    protected function replace(Request $request, string $field, string $key, string $folder): void
    {
        if (! $request->boolean('remove_'.$field) && ! $request->hasFile($field)) {
            return;
        }

        if ($old = Setting::get($key)) {
            Storage::disk('public')->delete($old);
        }

        Setting::put($key, $request->hasFile($field) ? $request->file($field)->store($folder, 'public') : null);
    }
}
