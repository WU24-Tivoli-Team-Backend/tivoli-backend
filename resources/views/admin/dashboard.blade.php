@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6">Admin Dashboard</h2>
        
        <div class="mb-8">
            <h3 class="text-xl font-semibold mb-4">Reset User Votes</h3>
            <p class="mb-4">Click the button below to reset all user votes. This action cannot be undone.</p>
            
            <form action="{{ route('admin.reset.votes') }}" method="POST" class="mb-4">
                @csrf
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    onclick="return confirm('Are you sure you want to reset all votes?');">
                    Reset All Votes
                </button>
            </form>
            <div class="mt-8">
                <h3 class="text-xl font-semibold mb-4">Amusement Votes</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b border-r text-left">ID</th>
                                    <th class="py-2 px-4 border-b border-r text-left">Amusement</th>
                                    <th class="py-2 px-4 border-b text-left">Votes</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($amusementVotes as $amusement)
                            <tr>
                                <td class="py-2 px-4 border-b border-r">{{ $amusement->id }}</td>
                                <td class="py-2 px-4 border-b border-r">{{ $amusement->name }}</td>
                                <td class="py-2 px-4 border-b">{{ $amusement->vote_count }}</td>
                            </tr>
                                @empty
                            <tr>
                                <td colspan="3" class="py-4 px-4 border-b text-center text-gray-500">No amusements found.</td>
                            </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
            </div>

            <h3 class="text-xl font-semibold mb-4">Reset User Balances</h3>
            <p class="mb-4">Click the button below to reset all user balances to €25 (except for the test user Rune).</p>
            
            <form action="{{ route('admin.reset.balances') }}" method="POST" class="mb-4">
                @csrf
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        onclick="return confirm('Are you sure you want to reset all user balances to €25?');">
                    Reset All Balances to €25
                </button>
            </form>
        </div>
        
        <div>
            <h3 class="text-xl font-semibold mb-4">User List</h3>
            
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b border-r text-left">ID</th>
                            <th class="py-2 px-4 border-b border-r text-left">Name</th>
                            <th class="py-2 px-4 border-b border-r text-left">Email</th>
                            <th class="py-2 px-4 border-b border-r text-left">Group</th>
                            <th class="py-2 px-4 border-b text-left">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td class="py-2 px-4 border-b border-r">{{ $user->id }}</td>
                                <td class="py-2 px-4 border-b border-r">{{ $user->name }}</td>
                                <td class="py-2 px-4 border-b border-r">{{ $user->email }}</td>
                                <td class="py-2 px-4 border-b border-r">{{ $user->group_id }}</td>
                                <td class="py-2 px-4 border-b">€{{ number_format($user->balance, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 px-4 border-b text-center text-gray-500">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection