<header class="bg-white shadow p-4 flex justify-between items-center">
  <div>
    <h1 class="text-xl font-semibold">@yield('title-section')</h1>
  </div>

  <div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
      
    </button>

    <form method="POST" action="{{ route('auth.logout') }}">
      @csrf
      <button type="submit" class="w-full text-left px-4 py-2 rounded-lg bg-red-400 hover:bg-red-500 text-white">Logout</button>
    </form>

  </div>
</header>
