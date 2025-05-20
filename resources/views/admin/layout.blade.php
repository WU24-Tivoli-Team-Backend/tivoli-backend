<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tivoli Admin - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <header class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Tivoli Admin Panel</h1>
            
            @if(Session::has('admin_logged_in'))
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="hover:underline">Dashboard</a>
                    <a href="{{ route('admin.logout') }}" 
                       class="bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded">Logout</a>
                </div>
            @endif
        </div>
    </header>
    
    <main class="container mx-auto my-8 px-4">
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
        
        @yield('content')
    </main>
    
    <footer class="bg-gray-200 p-4 mt-8">
        <div class="container mx-auto text-center text-gray-600">
            <p>Team Backend's Admin Panel for the Backend</p>
        </div>
    </footer>
</body>
</html>