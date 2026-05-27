@csrf
@php $experience = $experience ?? null; @endphp

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Position / Title <span class="required">*</span></label>
        <input type="text" name="position" class="form-control" value="{{ old('position', $experience?->position ?? '') }}" required>
        @error('position')<div class="form-error">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
        <label class="form-label">Company <span class="required">*</span></label>
        <input type="text" name="company" class="form-control" value="{{ old('company', $experience?->company ?? '') }}" required>
        @error('company')<div class="form-error">{{ $message }}</div>@enderror
    </div>
</div>

<div class="form-group">
    <label class="form-label">Location</label>
    <input type="text" name="location" class="form-control" value="{{ old('location', $experience?->location ?? '') }}" placeholder="e.g. Gaza, Remote">
</div>

<div class="form-group">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="4">{{ old('description', $experience?->description ?? '') }}</textarea>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Start Date <span class="required">*</span></label>
        <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $experience?->start_date?->format('Y-m-d') ?? '') }}" required>
        @error('start_date')<div class="form-error">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
        <label class="form-label">End Date</label>
        <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $experience?->end_date?->format('Y-m-d') ?? '') }}">
        <div class="form-text">Leave empty if this is your current position.</div>
        @error('end_date')<div class="form-error">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
        <label class="form-label">Sort Order</label>
        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $experience?->sort_order ?? 0) }}" min="0">
    </div>
</div>

<div class="form-check">
    <input type="checkbox" id="is_current" name="is_current" value="1" {{ old('is_current', $experience?->is_current ?? false) ? 'checked' : '' }}>
    <label for="is_current">I currently work here</label>
</div>

<div style="display:flex; gap: 0.5rem; padding-top: 1rem; margin-top: 1rem; border-top: 1px solid var(--gray-200);">
    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> {{ isset($experience) ? 'Update' : 'Create' }} Experience</button>
    <a href="{{ route('admin.experiences.index') }}" class="btn btn-secondary">Cancel</a>
</div>
