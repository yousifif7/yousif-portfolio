@if(isset($review) && $review)
    <div class="featured-review">
        <div class="featured-review-label">
            <i class="fas fa-quote-left"></i> Client testimonial
        </div>
        <div class="review-stars" aria-label="{{ $review->rating }} out of 5 stars">
            @for($i = 1; $i <= 5; $i++)
                <i class="fas fa-star{{ $i <= $review->rating ? '' : ' empty' }}"></i>
            @endfor
        </div>
        <blockquote class="featured-review-content">"{{ $review->content }}"</blockquote>
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
@endif
