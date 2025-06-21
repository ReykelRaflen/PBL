@extends('admin.layouts.app')

@section('main')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Members Management</h1>
        <div class="flex gap-2">
            <a href="{{ route('members.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Add Member
            </a>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6 p-4">
        <form action="{{ route('members.search') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-64">
                <input type="text" name="q" value="{{ request('q') }}" 
                       placeholder="Search by name, email, or phone..." 
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <select name="status" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                    <option value="">All Status</option>
                    <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Verified</option>
                    <option value="unverified" {{ request('status') === 'unverified' ? 'selected' : '' }}>Unverified</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md flex items-center gap-2">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    Search
                </button>
                <a href="{{ route('members.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-blue-600">{{ $totalMembers }}</div>
            <div class="text-sm text-gray-500">Total Members</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-green-600">{{ $verifiedMembers }}</div>
            <div class="text-sm text-gray-500">Verified</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-red-600">{{ $unverifiedMembers }}</div>
            <div class="text-sm text-gray-500">Unverified</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-purple-600">{{ $newMembersThisMonth }}</div>
            <div class="text-sm text-gray-500">New This Month</div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Members Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Personal Info</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($members as $member)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($member->foto)
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $member->foto) }}" alt="{{ $member->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                <i data-lucide="user" class="w-5 h-5 text-gray-500"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $member->name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-300">{{ ucfirst($member->role) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $member->email }}</div>
                                @if($member->phone)
                                    <div class="text-sm text-gray-500 dark:text-gray-300">{{ $member->phone }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    @if($member->gender)
                                        <div>{{ $member->gender }}</div>
                                    @endif
                                    @if($member->birthdate)
                                        <div class="text-gray-500 dark:text-gray-300">{{ \Carbon\Carbon::parse($member->birthdate)->format('d M Y') }}</div>
                                    @endif
                                    @if($member->agama)
                                        <div class="text-gray-500 dark:text-gray-300">{{ $member->agama }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col gap-1">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $member->is_verified ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $member->is_verified ? 'Verified' : 'Unverified' }}
                                    </span>
                                    @if($member->email_verified_at)
                                        <span class="text-xs text-gray-500">{{ $member->email_verified_at->format('M d, Y') }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $member->created_at->format('M d, Y') }}
                                <div class="text-xs text-gray-400">{{ $member->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('members.show', $member) }}" 
                                       class="text-blue-600 hover:text-blue-900 dark:text-blue-400" 
                                       title="View Details">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('members.edit', $member) }}" 
                                       class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400"
                                       title="Edit Member">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('members.toggle-verification', $member) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="text-{{ $member->is_verified ? 'orange' : 'green' }}-600 hover:text-{{ $member->is_verified ? 'orange' : 'green' }}-900"
                                                title="{{ $member->is_verified ? 'Remove Verification' : 'Verify Member' }}">
                                            <i data-lucide="{{ $member->is_verified ? 'user-x' : 'user-check' }}" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('members.destroy', $member) }}" method="POST" class="inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this member?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 dark:text-red-400"
                                                title="Delete Member">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-300">
                                <div class="flex flex-col items-center">
                                    <i data-lucide="users" class="w-12 h-12 text-gray-300 mb-2"></i>
                                    <p>No members found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($members->hasPages())
            <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $members->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
