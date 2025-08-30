<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>@yield('title') | PT. JBT INDONESIA</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.svg') }}">
    @vite('resources/css/app.css')
    {{-- <link
      rel="stylesheet"
      type="text/css"
      href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css"
    />
    <link
      rel="stylesheet"
      type="text/css"
      href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css"
    /> --}}
</head>
<body class="min-h-screen bg-gray-50 flex">

    @include('layouts.navigation')

    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 3000)"
        x-show="show"
        x-transition
        class="fixed top-4 right-4 z-50 space-y-4 w-80">

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg shadow p-4">
                <strong class="block text-sm font-medium">Success</strong>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('failed'))
            <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg shadow p-4">
                <strong class="block text-sm font-medium">Error</strong>
                <p class="text-sm">{{ session('failed') }}</p>
            </div>
        @endif

        @if ($errors->has('duplicate'))
            <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg shadow p-4">
                {{ $errors->first('duplicate') }}
            </div>
        @endif

    </div>




    <div class="flex-1 flex flex-col">

        @include('layouts.header')

        <main class="p-6">

            @yield('content')

        </main>
    </div>

    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const openMenuBtn = document.getElementById('open-menu');
            const navigation = document.getElementById('navigation');
            const closeMenuBtn = document.getElementById('close-menu');

            openMenuBtn.addEventListener('click', function () {
                navigation.classList.remove('hidden');
                setTimeout(() => {
                    navigation.classList.remove('-translate-x-full');
                    navigation.classList.add('translate-x-0');
                }, 10);
            });

            closeMenuBtn.addEventListener('click', function () {
                navigation.classList.remove('translate-x-0');
                navigation.classList.add('-translate-x-full');

                setTimeout(() => {
                    navigation.classList.add('hidden');
                }, 300);
            });
        });
    </script>
    <script>
  document.addEventListener('alpine:init', () => {
    Alpine.store('menu', {
      open: false
    });
  });
</script>

</body>
</html>
