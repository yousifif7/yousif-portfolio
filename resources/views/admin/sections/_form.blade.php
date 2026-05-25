@csrf

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Title <span class="required">*</span></label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $section->title ?? '') }}" required>
        @error('title')<div class="form-error">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
        <label class="form-label">Subtitle</label>
        <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $section->subtitle ?? '') }}">
    </div>
</div>

<div class="form-group">
    <label class="form-label">Content <span class="required">*</span></label>
    <textarea name="content" class="form-control" rows="10" required>{{ old('content', $section->content ?? '') }}</textarea>
    <div class="form-text">Plain text. Line breaks are preserved.</div>
    @error('content')<div class="form-error">{{ $message }}</div>@enderror
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Icon (Font Awesome class)</label>
        <input type="text" name="icon" class="form-control" value="{{ old('icon', $section->icon ?? '') }}" placeholder="e.g. fas fa-star">
    </div>
    <div class="form-group">
        <label class="form-label">Sort Order</label>
        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $section->sort_order ?? 0) }}" min="0">
    </div>
</div>

<div class="form-group">
    <label class="form-label">Image</label>
    @if(isset($section) && $section->image)
        <div class="image-preview"><div class="img-thumb"><img src="{{ $section->image_url }}" alt=""></div></div>
    @endif
    <input type="file" name="image" class="form-control" accept="image/*">
    <div class="form-text">Optional. JPG, PNG, WebP. Max 4 MB.</div>
    @error('image')<div class="form-error">{{ $message }}</div>@enderror
</div>

<div class="form-check">
    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $section->is_active ?? true) ? 'checked' : '' }}>
    <label for="is_active">Active (visible on website)</label>
</div>

<div style="display:flex; gap: 0.5rem; padding-top: 1rem; margin-top: 1rem; border-top: 1px solid var(--gray-200);">
    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> {{ isset($section) ? 'Update' : 'Create' }} Section</button>
    <a href="{{ route('admin.sections.index') }}" class="btn btn-secondary">Cancel</a>
</div>
