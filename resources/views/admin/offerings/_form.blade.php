@csrf

<div class="form-group">
    <label class="form-label">Title <span class="required">*</span></label>
    <input type="text" name="title" class="form-control" value="{{ old('title', $offering->title ?? '') }}" required>
    @error('title')<div class="form-error">{{ $message }}</div>@enderror
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Icon (Font Awesome class)</label>
        <input type="text" name="icon" class="form-control" value="{{ old('icon', $offering->icon ?? '') }}" placeholder="e.g. fas fa-database">
    </div>
    <div class="form-group">
        <label class="form-label">Sort Order</label>
        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $offering->sort_order ?? 0) }}" min="0">
    </div>
</div>

<div class="form-group">
    <label class="form-label">Slug</label>
    <input type="text" name="slug" class="form-control" value="{{ old('slug', $offering->slug ?? '') }}" placeholder="auto-generated from title">
</div>

<div class="form-group">
    <label class="form-label">Description <span class="required">*</span></label>
    <textarea name="description" class="form-control" rows="4" required>{{ old('description', $offering->description ?? '') }}</textarea>
    @error('description')<div class="form-error">{{ $message }}</div>@enderror
</div>

<div class="form-check">
    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $offering->is_active ?? true) ? 'checked' : '' }}>
    <label for="is_active">Active (visible on Hire page)</label>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> {{ isset($offering) && $offering->exists ? 'Update' : 'Create' }} Offering</button>
    <a href="{{ route('admin.offerings.index') }}" class="btn btn-secondary">Cancel</a>
</div>
