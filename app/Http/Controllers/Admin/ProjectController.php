<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $projects = Project::with('skills')
            ->when($q, function ($query) use ($q) {
                $query->where('title', 'like', "%$q%")
                      ->orWhere('client', 'like', "%$q%")
                      ->orWhere('category', 'like', "%$q%");
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $skills = Skill::active()->ordered()->get();
        return view('admin.projects.create', compact('skills'));
    }

    public function store(ProjectRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('projects/covers', 'public');
        }

        $project = Project::create($data);

        if ($request->filled('skills')) {
            $project->skills()->sync($request->input('skills'));
        }

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $i => $file) {
                $path = $file->store('projects/gallery', 'public');
                $project->images()->create([
                    'image_path' => $path,
                    'sort_order' => $i,
                ]);
            }
        }

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project created successfully.');
    }

    public function edit(Project $project)
    {
        $skills = Skill::active()->ordered()->get();
        $project->load('images', 'skills');
        return view('admin.projects.edit', compact('project', 'skills'));
    }

    public function update(ProjectRequest $request, Project $project)
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            if ($project->cover_image) {
                Storage::disk('public')->delete($project->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('projects/covers', 'public');
        }

        $project->update($data);

        $project->skills()->sync($request->input('skills', []));

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $i => $file) {
                $path = $file->store('projects/gallery', 'public');
                $project->images()->create([
                    'image_path' => $path,
                    'sort_order' => $project->images()->count() + $i,
                ]);
            }
        }

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        if ($project->cover_image) {
            Storage::disk('public')->delete($project->cover_image);
        }
        foreach ($project->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        $project->delete();

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    public function deleteImage(ProjectImage $image)
    {
        Storage::disk('public')->delete($image->image_path);
        $image->delete();
        return back()->with('success', 'Image deleted.');
    }
}
