<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ config('app.name', 'iGuppy') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">

    <!-- Centered container -->
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-md space-y-8">
            {{ $slot }}
        </div>
    </div>

</body>
</html>
