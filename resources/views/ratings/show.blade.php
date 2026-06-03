@extends('layouts.customer')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Rider Profile</h1>
                <p class="text-gray-600">Delivery performance and customer feedback</p>
            </div>

            <!-- Rider Info Card -->
            <div class="bg-gray-50 rounded-lg p-6 mb-8 text-center">
                <div class="w-20 h-20 rounded-full bg-blue-600 flex items-center justify-center text-white text-3xl font-bold mx-auto mb-4">
                    {{ substr($rider->user->name, 0, 1) }}
                </div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $rider->user->name }}</h2>
                <p class="text-gray-500">{{ $rider->user->email }}</p>
                
                <div class="flex justify-center items-center gap-6 mt-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-yellow-500">{{ number_format($averageRating, 1) }}</div>
                        <div class="text-sm text-gray-500">Average Rating</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900">{{ $rider->total_deliveries }}</div>
                        <div class="text-sm text-gray-500">Total Deliveries</div>
                    </div>
                </div>
                
                <div class="text-2xl text-yellow-500 mt-3">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($averageRating))
                            ★
                        @elseif($i - 0.5 <= $averageRating)
                            ½
                        @else
                            ☆
                        @endif
                    @endfor
                </div>
            </div>

            <!-- Reviews Section -->
            <h2 class="text-xl font-bold text-gray-900 mb-4">Customer Reviews</h2>
            
            @if($ratings->count() > 0)
                <div class="space-y-4">
                    @foreach($ratings as $rating)
                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="text-yellow-500 mb-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            {{ $i <= $rating->rating ? '★' : '☆' }}
                                        @endfor
                                    </div>
                                    <p class="font-medium text-gray-900">{{ $rating->customer->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $rating->created_at->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">{{ $rating->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            @if($rating->review)
                                <p class="mt-3 text-gray-700 italic">"{{ $rating->review }}"</p>
                            @else
                                <p class="mt-3 text-gray-400 italic">No written review</p>
                            @endif
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-6">
                    {{ $ratings->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-star text-5xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">No reviews yet for this rider</p>
                    <p class="text-sm text-gray-400">Reviews will appear here once customers rate their deliveries</p>
                </div>
            @endif
            
            <div class="mt-6">
                <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800">
                    ← Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
