<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DevelopmentOffering;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DevelopmentOfferingController extends Controller
{
    public function index()
    {
        $offerings = DevelopmentOffering::orderBy('sort_order')->paginate(15);

        return view('admin.offerings.index', compact('offerings'));
    }

    public function create()
    {
        return view('admin.offerings.create');
    }

    public function store(Request $request)
    {
        DevelopmentOffering::create($this->validateData($request));

        return redirect()->route('admin.offerings.index')
            ->with('success', 'Development offering added.');
    }

    public function edit(DevelopmentOffering $offering)
    {
        return view('admin.offerings.edit', compact('offering'));
    }

    public function update(Request $request, DevelopmentOffering $offering)
    {
        $offering->update($this->validateData($request));

        return redirect()->route('admin.offerings.index')
            ->with('success', 'Development offering updated.');
    }

    public function destroy(DevelopmentOffering $offering)
    {
        $offering->delete();

        return redirect()->route('admin.offerings.index')
            ->with('success', 'Development offering deleted.');
    }

    protected function validateData(Request $request): array
    {
        $request->merge(['is_active' => $request->boolean('is_active')]);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:191'],
            'slug' => ['nullable', 'string', 'max:191'],
            'icon' => ['nullable', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:2000'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        return $data;
    }
}
