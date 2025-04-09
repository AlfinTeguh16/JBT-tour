<header class="bg-white shadow p-4 flex justify-between items-center">
  <div>
    <h1 class="text-xl font-semibold">@yield('title-section')</h1>
  </div>

  <div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
      <img src="{{ auth()->user()->profile_picture ? asset(auth()->user()->profile_picture) : asset('assets/img/user-10.jpg') }}"
            class="w-10 h-10 rounded-full" alt="User" />
      <ph-icon name="caret-down" />
    </button>

    <ul x-show="open" @click.away="open = false"
        class="absolute right-0 mt-2 bg-white border rounded-lg shadow-lg w-48">
      {{-- <li><a href="{{ route('user.profile') }}" class="block px-4 py-2 hover:bg-gray-100">My Profile</a></li> --}}
      <li>
        <form method="POST" action="{{ route('auth.logout') }}">
          @csrf
          <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">Logout</button>
        </form>
      </li>
    </ul>
  </div>
</header>
