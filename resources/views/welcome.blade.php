<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our Application</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex items-center justify-center h-screen bg-gray-100 text-gray-600">
    <div class="text-center">
        <div class="text-4xl font-bold mb-5">
            Welcome to Our Application
        </div>

        <div class="flex justify-center space-x-6">
            <a href="{{ route('login') }}" class="text-gray-600 text-lg font-semibold uppercase hover:text-blue-500 transition duration-200">Login</a>
            <a href="{{ route('dashboard') }}" class="text-gray-600 text-lg font-semibold uppercase hover:text-blue-500 transition duration-200">Dashboard</a>
        </div>
    </div>
</body>
</html>
