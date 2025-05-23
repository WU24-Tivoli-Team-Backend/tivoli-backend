@extends('admin.layout')

@section('title', 'User Management')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">User Management</h2>
        <a href="{{ route('admin.users.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Add New User
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b border-r text-left">ID</th>
                    <th class="py-2 px-4 border-b border-r text-left">Name</th>
                    <th class="py-2 px-4 border-b border-r text-left">Email</th>
                    <th class="py-2 px-4 border-b border-r text-left">Group</th>
                    <th class="py-2 px-4 border-b border-r text-left">Balance</th>
                    <th class="py-2 px-4 border-b text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="py-2 px-4 border-b border-r">{{ $user->id }}</td>
                    <td class="py-2 px-4 border-b border-r">{{ $user->name }}</td>
                    <td class="py-2 px-4 border-b border-r">{{ $user->email }}</td>
                    <td class="py-2 px-4 border-b border-r">{{ $user->group_id }}</td>
                    <td class="py-2 px-4 border-b border-r">€{{ number_format($user->balance, 2) }}</td>
                    <td class="py-2 px-4 border-b text-center">
                        <div class="flex justify-center space-x-2">
                            <a href="{{ route('admin.users.edit', $user) }}" 
                               class="bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-3 rounded text-sm">
                                Edit
                            </a>
                            
                            @if(!in_array($user->email, ['rune@yrgobanken.vip', 'hans.2.andersson@educ.goteborg.se']))
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded text-sm"
                                            onclick="return confirm('Are you sure you want to delete this user?');">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-4 px-4 border-b text-center text-gray-500">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection