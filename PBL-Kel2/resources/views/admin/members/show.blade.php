@extends('admin.layouts.app')

@section('main')
    <div class="p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('members.index') }}" class="text-gray-600 hover:text-gray-800 dark:text-gray-400">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Member Details</h1>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('members.edit', $member) }}"
                    class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                    <i data-lucide="edit" class="w-4 h-4"></i>
                    Edit
                </a>
                <form action="{{ route('members.toggle-verification', $member) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                        class="bg-{{ $member->is_verified ? 'orange' : 'green' }}-600 hover:bg-{{ $member->is_verified ? 'orange' : 'green' }}-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                        <i data-lucide="{{ $member->is_verified ? 'user-x' : 'user-check' }}" class="w-4 h-4"></i>
                        {{ $member->is_verified ? 'Remove Verification' : 'Verify Member' }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="text-center">
                        <div class="mb-4">
                            @if($member->foto)
                                <img class="h-32 w-32 rounded-full object-cover mx-auto"
                                    src="{{ asset('storage/' . $member->foto) }}" alt="{{ $member->name }}">
                            @else
                                <div
                                    class="h-32 w-32 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center mx-auto">
                                    <i data-lucide="user" class="w-16 h-16 text-gray-500"></i>
                                </div>
                            @endif
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $member->name }}</h2>
                        <p class="text-gray-500 dark:text-gray-400">{{ ucfirst($member->role) }}</p>

                        <!-- Status Badge -->
                        <div class="mt-4">
                            <span class="px-3 py-1 text-sm font-semibold rounded-full 
                                {{ $member->is_verified ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $member->is_verified ? 'Verified' : 'Unverified' }}
                            </span>
                        </div>

                        <!-- Join Date -->
                        <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                            <p>Member since {{ $member->created_at->format('F d, Y') }}</p>
                            <p>{{ $member->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Details Card -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Personal Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Contact Information -->
                            <div>
                                <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Contact Details</h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                                        <p class="text-gray-900 dark:text-white">{{ $member->email }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</label>
                                        <p class="text-gray-900 dark:text-white">{{ $member->phone ?: 'Not provided' }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</label>
                                        <p class="text-gray-900 dark:text-white">{{ $member->address ?: 'Not provided' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Personal Details -->
                            <div>
                                <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Personal Details</h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Gender</label>
                                        <p class="text-gray-900 dark:text-white">{{ $member->gender ?: 'Not specified' }}
                                        </p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Birth
                                            Date</label>
                                        <p class="text-gray-900 dark:text-white">
                                            {{ $member->birthdate ? \Carbon\Carbon::parse($member->birthdate)->format('F d, Y') : 'Not provided' }}
                                            @if($member->birthdate)
                                                <span
                                                    class="text-sm text-gray-500">({{ \Carbon\Carbon::parse($member->birthdate)->age }}
                                                    years old)</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Religion</label>
                                        <p class="text-gray-900 dark:text-white">{{ $member->agama ?: 'Not specified' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-6">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Account Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Verified</label>
                                <p class="text-gray-900 dark:text-white">
                                    {{ $member->is_verified ? 'Yes' : 'No' }}
                                    @if($member->email_verified_at)
                                        <span
                                            class="text-sm text-gray-500">({{ $member->email_verified_at->format('M d, Y H:i') }})</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</label>
                                <p class="text-gray-900 dark:text-white">{{ $member->updated_at->format('F d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection