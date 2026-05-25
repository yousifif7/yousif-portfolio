<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function index()
    {
        $experiences = Experience::orderByDesc('start_date')->paginate(15);
        return view('admin.experiences.index', compact('experiences'));
    }

    public function create()
    {
        return view('admin.experiences.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        Experience::create($data);
        return redirect()->route('admin.experiences.index')
            ->with('success', 'Experience added successfully.');
    }

    public function edit(Experience $experience)
    {
        return view('admin.experiences.edit', compact('experience'));
    }

    public function update(Request $request, Experience $experience)
    {
        $data = $this->validateData($request);
        $experience->update($data);
        return redirect()->route('admin.experiences.index')
            ->with('success', 'Experience updated.');
    }

    public function destroy(Experience $experience)
    {
        $experience->delete();
        return redirect()->route('admin.experiences.index')
            ->with('success', 'Experience deleted.');
    }

    protected function validateData(Request $request): array
    {
        $request->merge(['is_current' => $request->boolean('is_current')]);

        return $request->validate([
            'position' => ['required', 'string', 'max:191'],
            'company' => ['required', 'string', 'max:191'],
            'location' => ['nullable', 'string', 'max:191'],
            'description' => ['nullable', 'string', 'max:5000'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_current' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
    }
}
