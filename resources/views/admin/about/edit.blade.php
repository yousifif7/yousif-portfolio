@extends('admin.layouts.app')
@section('title', 'Edit Profile')
@section('page_title', 'My Profile')

@section('content')

<form method="POST" action="{{ route('admin.about.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="card">
        <div class="card-header"><h2><i class="fas fa-user-circle"></i> Basic Information</h2></div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Full Name <span class="required">*</span></label>
                    <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $about->full_name) }}" required>
                    @error('full_name')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Job Title <span class="required">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $about->title) }}" placeholder="e.g. Backend Laravel Developer" required>
                    @error('title')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Hero Tagline</label>
                <input type="text" name="tagline" class="form-control" value="{{ old('tagline', $about->tagline) }}" placeholder="Short hero subtitle">
            </div>

            <div class="form-group">
                <label class="form-label">Short Bio <span class="required">*</span></label>
                <textarea name="short_bio" class="form-control" rows="3" required>{{ old('short_bio', $about->short_bio) }}</textarea>
                <div class="form-text">A brief introduction shown on the hero / about preview.</div>
                @error('short_bio')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Long Bio</label>
                <textarea name="long_bio" class="form-control" rows="8">{{ old('long_bio', $about->long_bio) }}</textarea>
                <div class="form-text">Detailed about-me content shown on the About page.</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h2><i class="fas fa-id-card"></i> Personal Details</h2></div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Years of Experience</label>
                    <input type="number" name="years_of_experience" class="form-control" value="{{ old('years_of_experience', $about->years_of_experience) }}" min="0" max="80">
                </div>
                <div class="form-group">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location', $about->location) }}" placeholder="e.g. Gaza Strip, Palestine">
                </div>
                <div class="form-group">
                    <label class="form-label">Nationality</label>
                    <input type="text" name="nationality" class="form-control" value="{{ old('nationality', $about->nationality) }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Education Degree</label>
                    <input type="text" name="education_degree" class="form-control" value="{{ old('education_degree', $about->education_degree) }}" placeholder="e.g. Bachelor's in Networks and Mobile Technology">
                </div>
                <div class="form-group">
                    <label class="form-label">University</label>
                    <input type="text" name="education_university" class="form-control" value="{{ old('education_university', $about->education_university) }}" placeholder="e.g. Al-Aqsa University">
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h2><i class="fas fa-address-book"></i> Contact Information</h2></div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $about->email) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $about->phone) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">WhatsApp</label>
                    <input type="text" name="whatsapp" class="form-control" value="{{ old('whatsapp', $about->whatsapp) }}" placeholder="With country code">
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h2><i class="fas fa-info-circle"></i> Social & freelance links</h2></div>
        <div class="card-body">
            <p style="margin:0;color:var(--gray-600);">
                Social and freelance platform links are now managed in
                <a href="{{ route('admin.settings.edit') }}" style="color:var(--primary);font-weight:600;">Site Settings</a>.
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h2><i class="fas fa-file"></i> Files</h2></div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Profile Photo / Avatar</label>
                    @if($about->avatar)
                        <div class="image-preview">
                            <div class="img-thumb"><img src="{{ $about->avatar_url }}" alt="Avatar"></div>
                        </div>
                    @endif
                    <input type="file" name="avatar" class="form-control" accept="image/*">
                    <div class="form-text">JPG, PNG, WebP. Max 4 MB.</div>
                    @error('avatar')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">CV / Resume</label>
                    @if($about->cv_file)
                        <div style="margin-bottom: 0.5rem;">
                            <a href="{{ $about->cv_url }}" target="_blank" class="btn btn-outline btn-sm"><i class="fas fa-file-pdf"></i> View current CV</a>
                        </div>
                    @endif
                    <input type="file" name="cv_file" class="form-control" accept=".pdf,.doc,.docx">
                    <div class="form-text">PDF, DOC, DOCX. Max 8 MB.</div>
                    @error('cv_file')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions" style="margin-bottom: 2rem;">
        <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save"></i> Save Changes</button>
        <a href="{{ route('home') }}" target="_blank" class="btn btn-outline"><i class="fas fa-eye"></i> Preview Site</a>
    </div>
</form>

@endsection
