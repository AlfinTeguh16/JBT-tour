<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  @vite('resources/css/app.css')
  <title>PT. JTB INDONESIA - Login</title>
</head>
<body>

<div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 px-4">
    <div>
        <img src="{{ asset('img/JTB_logo.png') }}" alt="PT. JTB INDONESIA" class="max-w-[200px]">
    </div>
    <x-card class="w-full max-w-md">
        <div class="w-full flex justify-center">
          <h1 class="font-bold text-3xl">Login</h1>
        </div>
        <form action="{{ route('auth.login.post') }}" method="POST" class="space-y-6 flex flex-col">
            @csrf

            <input
                type="text"
                name="name"
                label="Name"
                placeholder="Jhon Doe"
                required
                autofocus
                class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

            <input
                type="password"
                name="password"
                label="Password"
                placeholder="••••••••"
                required
                autofocus
                class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

            <x-button variant="primary" class="w-full">
                Login
            </x-button>
        </input>
    </x-card>
</div>

</body>
</html>
