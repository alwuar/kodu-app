<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DealerRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DealerRequestController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('estado');

        return view('admin.dealers.index', [
            'dealers' => DealerRequest::query()
                ->when($status, fn ($q) => $q->where('status', $status))
                ->latest()
                ->paginate(25)
                ->withQueryString(),
            'status' => $status,
        ]);
    }

    public function show(DealerRequest $dealer): View
    {
        return view('admin.dealers.show', ['dealer' => $dealer]);
    }

    public function update(Request $request, DealerRequest $dealer): RedirectResponse
    {
        $dealer->update($request->validate([
            'status' => ['required', Rule::in(array_keys(DealerRequest::STATUSES))],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]));

        return back()->with('status', 'Solicitud actualizada.');
    }

    public function destroy(DealerRequest $dealer): RedirectResponse
    {
        $dealer->delete();

        return redirect()->route('admin.dealers.index')->with('status', 'Solicitud eliminada.');
    }
}
