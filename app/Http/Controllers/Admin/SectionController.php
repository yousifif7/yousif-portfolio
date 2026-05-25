<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SectionController extends Controller
{
    public function index()
    {
        $sections = Section::orderBy('sort_order')->paginate(15);
        return view('admin.sections.index', compact('sections'));
    }

    public function create()
    {
        return view('admin.sections.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('sections', 'public');
        }

        Section::create($data);
        return redirect()->route('admin.sections.index')
            ->with('success', 'Section created.');
    }

    public function edit(Section $section)
    {
        return view('admin.sections.edit', compact('section'));
    }

    public function update(Request $request, Section $section)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('image')) {
            if ($section->image) {
                Storage::disk('public')->delete($section->image);
            }
            $data['image'] = $request->file('image')->store('sections', 'public');
        }

        $section->update($data);
        return redirect()->route('admin.sections.index')
            ->with('success', 'Section updated.');
    }

    public function destroy(Section $section)
    {
        if ($section->image) {
            Storage::disk('public')->delete($section->image);
        }
        $section->delete();
        return redirect()->route('admin.sections.index')
            ->with('success', 'Section deleted.');
    }

    protected function validateData(Request $request): array
    {
        $request->merge(['is_active' => $request->has('is_active') ? $request->boolean('is_active') : true]);

        return $request->validate([
            'title' => ['required', 'string', 'max:191'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:20000'],
            'icon' => ['nullable', 'string', 'max:100'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
    }
}
