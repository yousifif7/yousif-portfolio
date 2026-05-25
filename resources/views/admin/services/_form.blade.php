@csrf

<div class="form-group">
    <label class="form-label">Title <span class="required">*</span></label>
    <input type="text" name="title" class="form-control" value="{{ old('title', $service->title ?? '') }}" required>
    @error('title')<div class="form-error">{{ $message }}</div>@enderror
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Icon (Font Awesome class)</label>
        <input type="text" name="icon" class="form-control" value="{{ old('icon', $service->icon ?? '') }}" placeholder="e.g. fas fa-server">
        <div class="form-text">Browse icons at <a href="https://fontawesome.com/icons" target="_blank">fontawesome.com/icons</a></div>
    </div>
    <div class="form-group">
        <label class="form-label">Sort Order</label>
        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $service->sort_order ?? 0) }}" min="0">
    </div>
</div>

<div class="form-group">
    <label class="form-label">Description <span class="required">*</span></label>
    <textarea name="description" class="form-control" rows="5" required>{{ old('description', $service->description ?? '') }}</textarea>
    @error('description')<div class="form-error">{{ $message }}</div>@enderror
</div>

<div class="form-check">
    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $service->is_active ?? true) ? 'checked' : '' }}>
    <label for="is_active">Active (visible on website)</label>
</div>

<div style="display:flex; gap: 0.5rem; padding-top: 1rem; margin-top: 1rem; border-top: 1px solid var(--gray-200);">
    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> {{ isset($service) ? 'Update' : 'Create' }} Service</button>
    <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">Cancel</a>
</div>
