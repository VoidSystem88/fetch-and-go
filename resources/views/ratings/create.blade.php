@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-4">Rate Your Delivery</h1>
            <p class="mb-4">Order #{{ $order->id }}</p>
            
            <form method="POST" action="{{ route('ratings.store', $order) }}">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Rating</label>
                    <div class="flex gap-2 text-4xl" id="star-rating">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="cursor-pointer hover:scale-110 transition" data-value="{{ $i }}">☆</span>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-value" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Review (Optional)</label>
                    <textarea name="review" rows="4" class="w-full border-gray-300 rounded-md shadow-sm" 
                              placeholder="Share your experience with the rider..."></textarea>
                </div>
                
                <div class="flex justify-end">
                    <a href="{{ route('dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Skip</a>
                    <button type="submit" class="bg-yellow-500 text-white px-6 py-2 rounded hover:bg-yellow-600">
                        Submit Rating
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const stars = document.querySelectorAll('#star-rating span');
    const ratingInput = document.getElementById('rating-value');
    
    stars.forEach(star => {
        star.addEventListener('click', () => {
            const value = star.dataset.value;
            ratingInput.value = value;
            
            stars.forEach((s, index) => {
                if (index < value) {
                    s.innerHTML = '★';
                    s.classList.add('text-yellow-500');
                } else {
                    s.innerHTML = '☆';
                    s.classList.remove('text-yellow-500');
                }
            });
        });
    });
</script>
@endsection