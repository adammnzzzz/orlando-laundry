<?php

namespace App\Http\Controllers;

use App\Models\TypeOfService;
use Illuminate\Http\Request;

class TypeOfServiceController extends Controller
{
    public function index()
    {
        $services = TypeOfService::latest()->get();
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);
        TypeOfService::create($validated);
        return redirect()->route('services.index')->with('success', 'Service ditambahkan');
    }

    public function edit($id)
    {
        $service = TypeOfService::findOrFail($id);
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $service = TypeOfService::findOrFail($id);
        $validated = $request->validate([
            'service_name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);
        $service->update($validated);
        return redirect()->route('services.index')->with('success', 'Service diperbarui');
    }

    public function destroy($id)
    {
        $service = TypeOfService::findOrFail($id);
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Service dihapus');
    }
}
