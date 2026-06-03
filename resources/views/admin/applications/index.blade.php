@extends('layouts.admin')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-file-alt mr-2 text-blue-600"></i> Job Applications
                        </h2>
                        <p class="text-sm text-gray-500">Review and manage applicant requests</p>
                    </div>
                    <div class="flex gap-2">
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm">
                            Pending: {{ $applications->where('status', 'pending')->count() }}
                        </span>
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm">
                            Approved: {{ $applications->where('status', 'approved')->count() }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="p-6">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if($applications->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Position</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($applications as $app)
                                    <tr class="border-t hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm">#{{ $app->id }}</td>
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $app->name }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs rounded-full 
                                                @if($app->position == 'rider') bg-green-100 text-green-700
                                                @else bg-blue-100 text-blue-700
                                                @endif">
                                                {{ ucfirst($app->position) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <div>{{ $app->email }}</div>
                                            <div class="text-xs text-gray-500">{{ $app->phone }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs rounded-full 
                                                @if($app->status == 'pending') bg-yellow-100 text-yellow-700
                                                @elseif($app->status == 'approved') bg-green-100 text-green-700
                                                @else bg-red-100 text-red-700
                                                @endif">
                                                {{ ucfirst($app->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $app->created_at->format('M d, Y') }}</td>
                                        <td class="px-4 py-3">
                                            <a href="{{ route('admin.applications.show', $app) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                                View Details
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $applications->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-inbox text-5xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">No job applications yet</p>
                        <p class="text-sm text-gray-400">Applications will appear here when someone applies</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection