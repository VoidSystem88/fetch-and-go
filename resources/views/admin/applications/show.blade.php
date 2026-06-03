@extends('layouts.admin')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('admin.applications.index') }}" class="text-blue-600 hover:text-blue-800">
                ← Back to Applications
            </a>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b">
                <h2 class="text-xl font-semibold text-gray-800">
                    <i class="fas fa-user-check mr-2 text-blue-600"></i> Application #{{ $application->id }}
                </h2>
            </div>
            <div class="p-6">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Personal Information -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="font-semibold text-gray-800 mb-3">Personal Information</h3>
                        <div class="space-y-2">
                            <div>
                                <p class="text-xs text-gray-500">Full Name</p>
                                <p class="text-gray-800">{{ $application->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Email Address</p>
                                <p class="text-gray-800">{{ $application->email }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Phone Number</p>
                                <p class="text-gray-800">{{ $application->phone }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Applying for</p>
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($application->position == 'rider') bg-green-100 text-green-700
                                    @else bg-blue-100 text-blue-700
                                    @endif">
                                    {{ ucfirst($application->position) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Application Date</p>
                                <p class="text-gray-800">{{ $application->created_at->format('F d, Y h:i A') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="font-semibold text-gray-800 mb-3">Application Message</h3>
                        <div class="space-y-2">
                            <div>
                                <p class="text-xs text-gray-500">Why they want to join</p>
                                <p class="text-gray-800 mt-1">{{ $application->message ?: 'No message provided' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Status</p>
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($application->status == 'pending') bg-yellow-100 text-yellow-700
                                    @elseif($application->status == 'approved') bg-green-100 text-green-700
                                    @else bg-red-100 text-red-700
                                    @endif">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </div>
                            @if($application->reviewed_at)
                            <div>
                                <p class="text-xs text-gray-500">Reviewed on</p>
                                <p class="text-gray-800">{{ $application->reviewed_at->format('F d, Y h:i A') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Admin Notes -->
                @if($application->admin_notes)
                <div class="mt-4 bg-yellow-50 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-2">Admin Notes</h3>
                    <p class="text-gray-700">{{ $application->admin_notes }}</p>
                </div>
                @endif

                <!-- Actions -->
                @if($application->status == 'pending')
                <div class="mt-6 border-t pt-4">
                    <h3 class="font-semibold text-gray-800 mb-3">Review Application</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Approve Form -->
                        <div class="bg-green-50 rounded-lg p-4">
                            <h4 class="font-semibold text-green-800 mb-2">Approve Application</h4>
                            <p class="text-sm text-gray-600 mb-3">This will create a user account for the applicant.</p>
                            <form method="POST" action="{{ route('admin.applications.approve', $application) }}">
                                @csrf
                                <textarea name="notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg mb-2" placeholder="Optional notes..."></textarea>
                                <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition">
                                    <i class="fas fa-check-circle mr-1"></i> Approve & Create Account
                                </button>
                            </form>
                        </div>

                        <!-- Reject Form -->
                        <div class="bg-red-50 rounded-lg p-4">
                            <h4 class="font-semibold text-red-800 mb-2">Reject Application</h4>
                            <p class="text-sm text-gray-600 mb-3">Reject this application.</p>
                            <form method="POST" action="{{ route('admin.applications.reject', $application) }}">
                                @csrf
                                <textarea name="notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg mb-2" placeholder="Reason for rejection..."></textarea>
                                <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition">
                                    <i class="fas fa-times-circle mr-1"></i> Reject Application
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Approved Info -->
                @if($application->status == 'approved')
                <div class="mt-6 bg-green-50 rounded-lg p-4">
                    <h3 class="font-semibold text-green-800 mb-2">✓ Application Approved</h3>
                    <p class="text-gray-700">This application has been approved. A user account has been created.</p>
                    @if($application->reviewer)
                        <p class="text-xs text-gray-500 mt-2">Reviewed by: {{ $application->reviewer->name }}</p>
                    @endif
                </div>
                @endif

                <!-- Rejected Info -->
                @if($application->status == 'rejected')
                <div class="mt-6 bg-red-50 rounded-lg p-4">
                    <h3 class="font-semibold text-red-800 mb-2">✗ Application Rejected</h3>
                    <p class="text-gray-700">This application has been rejected.</p>
                    @if($application->reviewer)
                        <p class="text-xs text-gray-500 mt-2">Reviewed by: {{ $application->reviewer->name }}</p>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection