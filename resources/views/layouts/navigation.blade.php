<nav x-data id="navigation"
     class="bg-white shadow md:static lg:w-64 lg:min-w-64 transform transition-transform duration-300 ease-in-out hidden md:hidden lg:block fixed top-0 left-0 w-full h-[100%] lg:h-auto z-50"
     :class="{ 'translate-x-0': $store.menu.open, '-translate-x-full': !$store.menu.open, 'md:translate-x-0': true }">

  <div :class="{ 'block': $store.menu.open, 'hidden': !$store.menu.open }" class="md:block px-4 py-6">
    <div class="flex items-center justify-between mb-4">
      <div class="w-full flex justify-center">
        <img src="{{ asset('img/JTB_logo.png') }}" alt="PT. JTB INDONESIA" class="max-w-[100px]">
      </div>
      <button id="close-menu"
              class="lg:hidden text-white active:bg-gray-500 hover:bg-gray-500 px-2.5 py-1 rounded-lg bg-gray-400"
              @click="$store.menu.open = false">
        X
      </button>
    </div>

    <ul class="space-y-2">
      {{-- Dashboard (semua role) --}}
      <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard*')">
        <ph-icon name="house" class="mr-2" /> Dashboard
      </x-nav-link>

      @auth
        @php($role = Auth::user()->role)

        {{-- ===== ADMIN ===== --}}
        @if($role === 'admin')
          <x-nav-link href="{{ route('customers.index') }}" :active="request()->routeIs('customers.*')">
            <ph-icon name="users-three" class="mr-2" /> Customers
          </x-nav-link>

          <x-nav-link href="{{ route('vehicles.index') }}" :active="request()->routeIs('vehicles.*')">
            <ph-icon name="car" class="mr-2" /> Vehicles
          </x-nav-link>

          <x-nav-link href="{{ route('orders.index') }}" :active="request()->routeIs('orders.*')">
            <ph-icon name="clipboard-text" class="mr-2" /> Orders
          </x-nav-link>

          <x-nav-link href="{{ route('assignments.index') }}" :active="request()->routeIs('assignments.*')">
            <ph-icon name="calendar-check" class="mr-2" /> Assignments
          </x-nav-link>
        @endif

        {{-- ===== STAFF ===== --}}
        @if($role === 'staff')
          <x-nav-link href="{{ route('customers.index') }}" :active="request()->routeIs('customers.*')">
            <ph-icon name="users-three" class="mr-2" /> Customers
          </x-nav-link>

          <x-nav-link href="{{ route('vehicles.index') }}" :active="request()->routeIs('vehicles.*')">
            <ph-icon name="car" class="mr-2" /> Vehicles
          </x-nav-link>

          <x-nav-link href="{{ route('orders.index') }}" :active="request()->routeIs('orders.*')">
            <ph-icon name="clipboard-text" class="mr-2" /> Orders
          </x-nav-link>

          <x-nav-link href="{{ route('assignments.index') }}" :active="request()->routeIs('assignments.*')">
            <ph-icon name="calendar-check" class="mr-2" /> Assignments
          </x-nav-link>
        @endif

        {{-- ===== DRIVER ===== --}}
        @if($role === 'driver')
          <x-nav-link href="{{ route('assignments.index') }}" :active="request()->routeIs('assignments.*')">
            <ph-icon name="steering-wheel" class="mr-2" /> My Assignments
          </x-nav-link>

          <x-nav-link href="{{ route('work-sessions.index') }}" :active="request()->routeIs('work-sessions.*')">
            <ph-icon name="timer" class="mr-2" /> Work Sessions
          </x-nav-link>
        @endif

        {{-- ===== GUIDE ===== --}}
        @if($role === 'guide')
          <x-nav-link href="{{ route('assignments.index') }}" :active="request()->routeIs('assignments.*')">
            <ph-icon name="map-trifold" class="mr-2" /> My Assignments
          </x-nav-link>

          <x-nav-link href="{{ route('work-sessions.index') }}" :active="request()->routeIs('work-sessions.*')">
            <ph-icon name="timer" class="mr-2" /> Work Sessions
          </x-nav-link>
        @endif

        {{-- ===== NOTIFICATIONS (semua role) ===== --}}
        <x-nav-link href="{{ route('notifications.index') }}" :active="request()->routeIs('notifications.*')">
          <ph-icon name="bell" class="mr-2" /> Notifications
        </x-nav-link>
      @endauth
    </ul>
  </div>
</nav>
