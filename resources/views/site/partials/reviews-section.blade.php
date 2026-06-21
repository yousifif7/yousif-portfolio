<section class="section section-alt" id="reviews">
    <div class="container">
        <div class="section-header">
            <div class="section-eyebrow">Testimonials</div>
            <h2 class="section-title">Client Reviews</h2>
            <p class="section-subtitle">What clients say about working with me on Laravel projects.</p>
        </div>

        @if(session('review_success'))
            <div class="alert alert-success" style="max-width: 700px; margin: 0 auto 2rem;">
                <i class="fas fa-check-circle"></i> {{ session('review_success') }}
            </div>
        @endif

        @if($reviews->isNotEmpty())
            <div class="reviews-grid">
                @foreach($reviews as $review)
                    <div class="review-card">
                        <div class="review-stars" aria-label="{{ $review->rating }} out of 5 stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $review->rating ? '' : ' empty' }}"></i>
                            @endfor
                        </div>
                        <blockquote class="review-content">"{{ $review->content }}"</blockquote>
                        <div class="review-author">
                            <div class="review-avatar">{{ $review->initials }}</div>
                            <div>
                                <div class="review-name">{{ $review->name }}</div>
                                @if($review->role || $review->company)
                                    <div class="review-meta">
                                        @if($review->role){{ $review->role }}@endif
                                        @if($review->role && $review->company) · @endif
                                        @if($review->company){{ $review->company }}@endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="review-form-wrap">
            <div class="review-form-card">
                <h3>Share Your Experience</h3>
                <p>Worked with me before? Leave a review — it will appear after approval.</p>

                @if($errors->has('name') || $errors->has('email') || $errors->has('rating') || $errors->has('content'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>Please fix the errors below.</div>
                    </div>
                @endif

                <form method="POST" action="{{ route('reviews.store') }}" novalidate>
                    @csrf

                    <div class="honeypot" aria-hidden="true">
                        <label>Website</label>
                        <input type="text" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label for="review_name">Your Name <span class="required-mark">*</span></label>
                            <input type="text" id="review_name" name="name" class="form-control" value="{{ old('name') }}" required>
                            @error('name')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="review_email">Your Email <span class="required-mark">*</span></label>
                            <input type="email" id="review_email" name="email" class="form-control" value="{{ old('email') }}" required>
                            @error('email')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label for="review_role">Your Role <span class="optional-mark">(optional)</span></label>
                            <input type="text" id="review_role" name="role" class="form-control" value="{{ old('role') }}" placeholder="e.g. Project Manager">
                        </div>
                        <div class="form-group">
                            <label for="review_company">Company <span class="optional-mark">(optional)</span></label>
                            <input type="text" id="review_company" name="company" class="form-control" value="{{ old('company') }}" placeholder="e.g. Acme Corp">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Rating <span class="required-mark">*</span></label>
                        <div class="star-rating-input" id="starRating">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}"
                                    {{ (int) old('rating', 5) === $i ? 'checked' : '' }} required>
                                <label for="star{{ $i }}" title="{{ $i }} stars"><i class="fas fa-star"></i></label>
                            @endfor
                        </div>
                        @error('rating')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="review_content">Your Review <span class="required-mark">*</span></label>
                        <textarea id="review_content" name="content" class="form-control" rows="4"
                            placeholder="Share your experience working together..." required>{{ old('content') }}</textarea>
                        @error('content')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    @include('site.partials.form-antispam')

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-star"></i> Submit Review
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
