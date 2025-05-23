@extends('admin.layout')

@section('title', 'Victory Points')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6">Victory Points</h2>

        <h3 class="text-xl font-semibold mb-4">Reset User Stamps</h3>
        <p class="mb-4">Click the button below to reset all user stamps. This action cannot be undone.</p>
        
        <form action="{{ route('admin.reset.stamps') }}" method="POST" class="mb-4">
            @csrf
            <button type="submit"
                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                onclick="return confirm('Are you sure you want to reset all user stamps?');">
                Reset All Stamps
            </button>
        </form>
        
            <div class="mt-8">
                <h3 class="text-xl font-semibold mb-4">User Stamps</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b border-r text-left">ID</th>
                                    <th class="py-2 px-4 border-b border-r text-left">User</th>
                                    <th class="py-2 px-4 border-b border-r text-left">Count</th>
                                    <th class="py-2 px-4 border-b border-r text-left">Stamps</th>
                                    <th class="py-2 px-4 border-b text-left">VP</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($userStamps as $userStamp)
                            <tr>
                                <td class="py-2 px-4 border-b border-r">{{ $userStamp->user_id }}</td>
                                <td class="py-2 px-4 border-b border-r">{{ $userStamp->user_name }}</td>
                                <td class="py-2 px-4 border-b border-r">{{ $userStamp->stamp_count }}</td>
                                <td class="py-2 px-4 border-b border-r">
                                    <ul class="list-disc list-inside">
                                    @foreach($userStamp->stamps as $stamp)
                                        <li>{{ $stamp }}</li>
                                    @endforeach
                                    </ul>
                                </td>
                                <td class="py-2 px-4 border-b">{{ $userStamp->victory_points }}</td>
                            </tr>
                                @empty
                            <tr>
                                <td colspan="3" class="py-4 px-4 border-b text-center text-gray-500">No users with stamps found.</td>
                            </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
            </div>
@endsection