@extends('admin.layout')

@section('title', 'Login')

@section('content')
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-8">
            <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">Admin Login</h2>
            
            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf
                
                <div class="mb-6">
                    <label for="api_key" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" name="api_key" id="api_key" placeholder="Enter password here..."
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('api_key') border-red-500 @enderror"
                           required>
                    
                    @error('api_key')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center justify-center">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="text-center mt-6">
        <a href="/" class="text-blue-600 hover:underline">Back to Documentation</a>
    </div>
@endsection