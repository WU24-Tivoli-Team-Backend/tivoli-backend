@extends('admin.layout')

@section('title', 'Victory Points')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6">Victory Points</h2>

            <div class="mt-8">
                <h3 class="text-xl font-semibold mb-4">User Stamps</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b border-r text-left">ID</th>
                                    <th class="py-2 px-4 border-b border-r text-left">User</th>
                                    <th class="py-2 px-4 border-b border-r text-left">Count</th>
                                    <th class="py-2 px-4 border-b text-left">Stamps</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($userStamps as $userStamp)
                            <tr>
                                <td class="py-2 px-4 border-b border-r">{{ $userStamp->user_id }}</td>
                                <td class="py-2 px-4 border-b border-r">{{ $userStamp->user_name }}</td>
                                <td class="py-2 px-4 border-b border-r">{{ $userStamp->stamp_count }}</td>
                                <td class="py-2 px-4 border-b">
                                    <ul class="list-disc list-inside">
                                    @foreach($userStamp->stamps as $stamp)
                                        <li>{{ $stamp }}</li>
                                    @endforeach
                                    </ul>
                                </td>
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