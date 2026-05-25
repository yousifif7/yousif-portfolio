@csrf

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Name <span class="required">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $skill->name ?? '') }}" required>
        @error('name')<div class="form-error">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
        <label class="form-label">Category</label>
        <input type="text" name="category" class="form-control" value="{{ old('category', $skill->category ?? '') }}" placeholder="e.g. Backend, Frontend, Database">
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Icon (Font Awesome class)</label>
        <input type="text" name="icon" class="form-control" value="{{ old('icon', $skill->icon ?? '') }}" placeholder="e.g. fab fa-laravel">
        <div class="form-text">Browse icons at <a href="https://fontawesome.com/icons" target="_blank">fontawesome.com/icons</a></div>
    </div>
    <div class="form-group">
        <label class="form-label">Color (hex)</label>
        <input type="text" name="color" class="form-control" value="{{ old('color', $skill->color ?? '#6366f1') }}" placeholder="#6366f1">
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Proficiency (%) <span class="required">*</span></label>
        <input type="number" name="proficiency" class="form-control" value="{{ old('proficiency', $skill->proficiency ?? 80) }}" min="0" max="100" required>
    </div>
    <div class="form-group">
        <label class="form-label">Sort Order</label>
        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $skill->sort_order ?? 0) }}" min="0">
    </div>
</div>

<div class="form-check">
    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $skill->is_active ?? true) ? 'checked' : '' }}>
    <label for="is_active">Active (visible on website)</label>
</div>

<div style="display:flex; gap: 0.5rem; padding-top: 1rem; margin-top: 1rem; border-top: 1px solid var(--gray-200);">
    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> {{ isset($skill) ? 'Update' : 'Create' }} Skill</button>
    <a href="{{ route('admin.skills.index') }}" class="btn btn-secondary">Cancel</a>
</div>
