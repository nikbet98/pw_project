<div class="card card_review">
    <div class="card-body">
        <h5 class="card-title">{{ $review->title }}</h5>
        <p class="card-text">{{__('messages.from')}} <span class="font-weight-bold">{{ $review->user->firstname }}</span></p>
        <p class="card-text"><small class="text-muted">{{ $review->created_at }}</small></p>
        <p class="card-text">{{ $review->text }}</p>
        <p class="card-text">{{__('messages.vote')}}: <span class="font-weight-bold">{{ $review->stars }} {{__('messages.stars')}}</span></p>
    </div>
</div>