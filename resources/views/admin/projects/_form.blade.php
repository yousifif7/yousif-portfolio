@csrf

<div class="form-row">
    <div class="form-group" style="grid-column: 1 / -1;">
        <label class="form-label">Project Title <span class="required">*</span></label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $project->title ?? '') }}" required>
        @error('title')<div class="form-error">{{ $message }}</div>@enderror
    </div>
</div>

<div class="form-group">
    <label class="form-label">Short Description <span class="required">*</span></label>
    <input type="text" name="short_description" class="form-control" value="{{ old('short_description', $project->short_description ?? '') }}" maxlength="255" required>
    <div class="form-text">One-line summary shown in project cards.</div>
    @error('short_description')<div class="form-error">{{ $message }}</div>@enderror
</div>

<div class="form-group">
    <label class="form-label">Full Description <span class="required">*</span></label>
    <textarea name="description" class="form-control" rows="8" required>{{ old('description', $project->description ?? '') }}</textarea>
    @error('description')<div class="form-error">{{ $message }}</div>@enderror
</div>

<div class="card" style="margin: 1.5rem 0; border: 1px dashed var(--gray-300);">
    <div class="card-header"><strong><i class="fas fa-book-open"></i> Case Study (optional)</strong></div>
    <div class="card-body">
        <div class="form-text" style="margin-bottom: 1rem;">Structured Problem → Solution → Result blocks shown on the project page. Leave blank to hide.</div>

        <div class="form-group">
            <label class="form-label">The Problem</label>
            <textarea name="problem" class="form-control" rows="4" placeholder="What challenge did the client face?">{{ old('problem', $project->problem ?? '') }}</textarea>
            @error('problem')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">The Solution</label>
            <textarea name="solution" class="form-control" rows="4" placeholder="What did you build and how did you approach it?">{{ old('solution', $project->solution ?? '') }}</textarea>
            @error('solution')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">The Result</label>
            <textarea name="result" class="form-control" rows="4" placeholder="What outcomes were achieved? Metrics, improvements, etc.">{{ old('result', $project->result ?? '') }}</textarea>
            @error('result')<div class="form-error">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Client</label>
        <input type="text" name="client" class="form-control" value="{{ old('client', $project->client ?? '') }}">
    </div>
    <div class="form-group">
        <label class="form-label">Category</label>
        <input type="text" name="category" class="form-control" value="{{ old('category', $project->category ?? '') }}" placeholder="e.g. CRM, API, Web App">
    </div>
    <div class="form-group">
        <label class="form-label">Completion Date</label>
        <input type="date" name="completed_at" class="form-control" value="{{ old('completed_at', isset($project) ? $project->completed_at?->format('Y-m-d') : '') }}">
    </div>
    <div class="form-group">
        <label class="form-label">Sort Order</label>
        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $project->sort_order ?? 0) }}" min="0">
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Live URL</label>
        <input type="url" name="live_url" class="form-control" value="{{ old('live_url', $project->live_url ?? '') }}" placeholder="https://...">
        @error('live_url')<div class="form-error">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
        <label class="form-label">GitHub URL</label>
        <input type="url" name="github_url" class="form-control" value="{{ old('github_url', $project->github_url ?? '') }}" placeholder="https://github.com/...">
        @error('github_url')<div class="form-error">{{ $message }}</div>@enderror
    </div>
</div>

<div class="form-group">
    <label class="form-label">Skills / Languages used</label>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 0.5rem; max-height: 280px; overflow-y: auto; padding: 1rem; background: #f9fafb; border-radius: 8px;">
        @php $selectedSkillIds = old('skills', isset($project) ? $project->skills->pluck('id')->toArray() : []); @endphp
        @foreach($skills as $skill)
            <label style="display: flex; gap: 0.5rem; align-items: center; cursor: pointer;">
                <input type="checkbox" name="skills[]" value="{{ $skill->id }}" {{ in_array($skill->id, $selectedSkillIds) ? 'checked' : '' }}>
                <span style="font-size: 0.9rem;">@if($skill->icon)<i class="{{ $skill->icon }}"></i>@endif {{ $skill->name }}</span>
            </label>
        @endforeach
    </div>
    @if($skills->isEmpty())
        <div class="form-text">No skills available yet. <a href="{{ route('admin.skills.create') }}">Add some first</a>.</div>
    @endif
</div>

<div class="form-group">
    <label class="form-label">Cover Image</label>
    @if(isset($project) && $project->cover_image)
        <div class="image-preview">
            <div class="img-thumb"><img src="{{ $project->cover_image_url }}" alt=""></div>
        </div>
    @endif
    <input type="file" name="cover_image" class="form-control" accept="image/*">
    <div class="form-text">JPG, PNG, WebP. Max 4 MB. Recommended 1600×1000.</div>
    @error('cover_image')<div class="form-error">{{ $message }}</div>@enderror
</div>

<div class="form-group">
    <label class="form-label">Additional Gallery Images</label>
    @if(isset($project) && $project->images->isNotEmpty())
        <div class="image-preview">
            @foreach($project->images as $img)
                <div class="img-thumb">
                    <img src="{{ $img->url }}" alt="">
                    <button type="button" class="delete-img" title="Delete"
                            onclick="if(confirm('Delete this image?')) document.getElementById('delete-img-{{ $img->id }}').submit();">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endforeach
        </div>
    @endif
    <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>
    <div class="form-text">You can select multiple images to add to the gallery.</div>
</div>

<div class="form-row">
    <div class="form-group">
        <div class="form-check">
            <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $project->is_featured ?? false) ? 'checked' : '' }}>
            <label for="is_featured">Feature this project on home page</label>
        </div>
    </div>
    <div class="form-group">
        <div class="form-check">
            <input type="checkbox" id="is_published" name="is_published" value="1" {{ old('is_published', $project->is_published ?? true) ? 'checked' : '' }}>
            <label for="is_published">Published (visible on the website)</label>
        </div>
    </div>
</div>

<div class="form-actions">
    <button type="submit" form="project-form" class="btn btn-primary"><i class="fas fa-save"></i> {{ isset($project) ? 'Update Project' : 'Create Project' }}</button>
    <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">Cancel</a>
</div>
