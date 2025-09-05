<header class="bg-white shadow px-4 py-3 flex items-center justify-between">
  <!-- Kiri: Tombol menu di mobile -->
  <div class="flex items-center">
    <button id="open-menu" @click="$store.menu.toggle()" class="md:hidden bg-blue-600 text-white px-3 py-2 rounded-md flex items-center">
      <i class="ph ph-list text-2xl"></i>
    </button>
  </div>

  <!-- Tengah: Judul -->
  <div class="text-center flex-1 md:flex-none md:ml-4">
    <h1 class="text-lg md:text-xl font-semibold text-gray-800">@yield('title-section')</h1>
  </div>


  <!-- Kanan: Logout -->
  <div class="flex items-center space-x-2">
    <form method="POST" action="{{ route('auth.logout') }}">
      @csrf
      <button type="submit" class="px-4 py-2 rounded-md bg-red-500 hover:bg-red-600 text-white text-sm">
        Logout
      </button>
    </form>
  </div>
</header>
