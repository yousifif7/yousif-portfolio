@csrf

@if(isset($post) && ($post->views > 0 || $post->unique_views > 0))
<div class="form-text" style="margin-bottom: 1rem;">
    <i class="fas fa-eye"></i> {{ number_format($post->views) }} total views
    · {{ number_format($post->unique_views) }} unique
</div>
@endif

<div class="form-row">
    <div class="form-group" style="grid-column: 1 / -1;">
        <label class="form-label">Title <span class="required">*</span></label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $post->title ?? '') }}" required>
        @error('title')<div class="form-error">{{ $message }}</div>@enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Slug</label>
        <input type="text" name="slug" class="form-control" value="{{ old('slug', $post->slug ?? '') }}" placeholder="Auto-generated if blank">
        @error('slug')<div class="form-error">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
        <label class="form-label">Publish Date</label>
        <input type="datetime-local" name="published_at" class="form-control"
            value="{{ old('published_at', isset($post) && $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}">
        <div class="form-text">Leave blank to set automatically when you publish.</div>
        @error('published_at')<div class="form-error">{{ $message }}</div>@enderror
    </div>
</div>

<div class="form-group">
    <label class="form-label">Excerpt <span class="required">*</span></label>
    <textarea name="excerpt" class="form-control" rows="2" maxlength="500" required placeholder="Short summary shown on the blog listing">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
    @error('excerpt')<div class="form-error">{{ $message }}</div>@enderror
</div>

<div class="form-group">
    <label class="form-label">Body <span class="required">*</span></label>
    <textarea name="body" id="post_body" class="form-control sr-only-editor" rows="14">{{ old('body', $post->body ?? '') }}</textarea>
    <div id="post_editor" class="quill-editor-wrap"></div>
    <div class="form-text">Use the toolbar for headings, bold, lists, text color, highlight, links, and quotes.</div>
    @error('body')<div class="form-error">{{ $message }}</div>@enderror
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css">
<style>
    .sr-only-editor {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }
    .quill-editor-wrap {
        background: #fff;
        border-radius: 8px;
    }
    .quill-editor-wrap .ql-toolbar {
        border-radius: 8px 8px 0 0;
        border-color: var(--gray-300, #d1d5db);
        background: #f9fafb;
    }
    .quill-editor-wrap .ql-container {
        min-height: 320px;
        font-size: 0.95rem;
        border-radius: 0 0 8px 8px;
        border-color: var(--gray-300, #d1d5db);
        font-family: inherit;
        background: #fff;
    }
    .quill-editor-wrap .ql-editor {
        min-height: 320px;
        line-height: 1.7;
        color: #1f2937 !important;
        background: #fff;
    }
    .quill-editor-wrap .ql-editor p,
    .quill-editor-wrap .ql-editor li,
    .quill-editor-wrap .ql-editor h2,
    .quill-editor-wrap .ql-editor h3,
    .quill-editor-wrap .ql-editor h4,
    .quill-editor-wrap .ql-editor blockquote,
    .quill-editor-wrap .ql-editor ol,
    .quill-editor-wrap .ql-editor ul {
        color: inherit;
    }
    .quill-editor-wrap .ql-editor.ql-blank::before {
        color: #9ca3af;
        font-style: normal;
    }
    .quill-editor-wrap .ql-editor .ql-syntax {
        color: #1f2937;
        background: #f3f4f6;
    }
    .quill-editor-wrap .ql-toolbar .ql-picker.ql-color .ql-picker-label,
    .quill-editor-wrap .ql-toolbar .ql-picker.ql-background .ql-picker-label {
        width: 28px;
    }
    .quill-editor-wrap .ql-toolbar .ql-color .ql-picker-options,
    .quill-editor-wrap .ql-toolbar .ql-background .ql-picker-options {
        padding: 6px;
        width: 196px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var textarea = document.getElementById('post_body');
    var editorEl = document.getElementById('post_editor');
    var form = document.getElementById('post-form');

    if (!textarea || !editorEl || typeof Quill === 'undefined') {
        return;
    }

    var editorColors = [
        '#1e293b', '#4f46e5', '#6366f1', '#0891b2', '#059669',
        '#d97706', '#dc2626', '#7c3aed', '#be185d', false
    ];

    var editorBackgrounds = [
        '#fef9c3', '#dbeafe', '#dcfce7', '#fee2e2', '#f3e8ff',
        '#ffedd5', '#f1f5f9', '#e2e8f0', false
    ];

    function normalizeFaintText(root) {
        root.querySelectorAll('[style*="color"]').forEach(function (el) {
            var rgb = el.style.color.match(/\d+/g);
            if (!rgb || rgb.length < 3) {
                return;
            }
            var brightness = (parseInt(rgb[0], 10) * 299 + parseInt(rgb[1], 10) * 587 + parseInt(rgb[2], 10) * 114) / 1000;
            if (brightness > 200) {
                el.style.removeProperty('color');
            }
        });
    }

    var ColorStyle = Quill.import('attributors/style/color');
    var BackgroundStyle = Quill.import('attributors/style/background');
    ColorStyle.whitelist = editorColors.filter(Boolean);
    BackgroundStyle.whitelist = editorBackgrounds.filter(Boolean);
    Quill.register(ColorStyle, true);
    Quill.register(BackgroundStyle, true);

    var quill = new Quill(editorEl, {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ header: [2, 3, false] }],
                ['bold', 'italic', 'underline'],
                [{ color: editorColors }, { background: editorBackgrounds }],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['blockquote', 'link'],
                ['clean']
            ]
        },
        placeholder: 'Write your post content here...'
    });

    if (textarea.value.trim()) {
        quill.clipboard.dangerouslyPasteHTML(0, textarea.value);
        normalizeFaintText(quill.root);
    }

    quill.root.addEventListener('paste', function () {
        setTimeout(function () {
            normalizeFaintText(quill.root);
        }, 0);
    });

    form.addEventListener('submit', function () {
        var html = quill.root.innerHTML;
        if (quill.getText().trim() === '') {
            textarea.value = '';
        } else {
            textarea.value = html;
        }
    });
});
</script>
@endpush

<div class="form-group">
    <label class="form-label">Cover Image</label>
    @if(isset($post) && $post->cover_image)
        <div class="image-preview">
            <div class="img-thumb"><img src="{{ $post->cover_image_url }}" alt=""></div>
        </div>
    @endif
    <input type="file" name="cover_image" class="form-control" accept="image/*">
    <div class="form-text">Optional. JPG, PNG, WebP. Max 4 MB.</div>
    @error('cover_image')<div class="form-error">{{ $message }}</div>@enderror
</div>

<div class="card" style="margin: 1.5rem 0; border: 1px dashed var(--gray-300);">
    <div class="card-header"><strong><i class="fas fa-search"></i> SEO (optional)</strong></div>
    <div class="card-body">
        <div class="form-group">
            <label class="form-label">Meta Title</label>
            <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $post->meta_title ?? '') }}" placeholder="Defaults to post title">
            @error('meta_title')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Meta Description</label>
            <textarea name="meta_description" class="form-control" rows="2" maxlength="300" placeholder="Defaults to excerpt">{{ old('meta_description', $post->meta_description ?? '') }}</textarea>
            @error('meta_description')<div class="form-error">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<div class="form-group">
    <div class="form-check">
        <input type="checkbox" id="is_published" name="is_published" value="1" {{ old('is_published', $post->is_published ?? false) ? 'checked' : '' }}>
        <label for="is_published">Published (visible on the website)</label>
    </div>
</div>

<div class="form-actions">
    <button type="submit" form="post-form" class="btn btn-primary"><i class="fas fa-save"></i> {{ isset($post) ? 'Update Post' : 'Create Post' }}</button>
    <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Cancel</a>
</div>
