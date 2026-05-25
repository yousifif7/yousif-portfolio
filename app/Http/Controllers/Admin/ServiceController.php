<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('sort_order')->paginate(15);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        Service::create($this->validateData($request));
        return redirect()->route('admin.services.index')
            ->with('success', 'Service added.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $service->update($this->validateData($request));
        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted.');
    }

    protected function validateData(Request $request): array
    {
        $request->merge(['is_active' => $request->has('is_active') ? $request->boolean('is_active') : true]);

        return $request->validate([
            'title' => ['required', 'string', 'max:191'],
            'icon' => ['nullable', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:2000'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
    }
}
