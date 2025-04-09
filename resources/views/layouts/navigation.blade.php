<nav x-data="{ open: false }" class="bg-white shadow absolute md:static w-64 lg:min-w-64 transform md:translate-x-0 transition-transform">
    <div class="p-6 flex items-center justify-between md:hidden">
      <h1 class="text-xl font-bold text-blue-600">CV. Cipta Arya</h1>
      <button @click="open = !open" class="p-2 rounded-md focus:outline-none">
        <ph-icon name="list" size="24" />
      </button>
    </div>
  
    <div :class="open ? 'block' : 'hidden' " class="md:block px-4 py-6">
      <h2 class="text-2xl font-bold text-blue-600 mb-6">CV. Cipta Arya</h2>
      <ul class="space-y-2">
        <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard*')">
          <ph-icon name="house" class="mr-2" /> Dashboard
        </x-nav-link>
  
        @auth
          @if(Auth::user()->role === 'akuntan')
            <x-nav-link href="{{ route('karyawan.index') }}" :active="request()->routeIs('karyawan.*')">
              <p class="mr-2" /> Data Karyawan </p>
            </x-nav-link>
            
            <x-nav-link href="{{ route('draft-pekerjaan.index') }}" :active="request()->routeIs('draft-pekerjaan.*')">
              <p class="mr-2" /> Draft Pekerjaan </p>
            </x-nav-link>

            <x-nav-link href="{{ route('data-neraca.index') }}" :active="request()->routeIs('data-neraca.*')">
              <p class="mr-2" /> Data Neraca </p>
            </x-nav-link>

            <x-nav-link href="{{ route('laporan-keuangan.index') }}" :active="request()->routeIs('laporan-keuangan.*')">
              <p class="mr-2" /> Laporan Keuangan </p>
            </x-nav-link>
          @elseif(Auth::user()->role === 'admin')
            <x-nav-link href="{{ route('karyawan.index') }}" :active="request()->routeIs('karyawan.*')">
              <p class="mr-2" /> Data Karyawan </p>
            </x-nav-link>

          @elseif(Auth::user()->role === 'direktur')
            <x-nav-link href="{{ route('karyawan.index') }}" :active="request()->routeIs('karyawan.*')">
              <p class="mr-2" /> Data Karyawan </p>
            </x-nav-link>

            <x-nav-link href="{{ route('laporan-keuangan.index') }}" :active="request()->routeIs('laporan-keuangan.*')">
              <p class="mr-2" /> Laporan Keuangan </p>
            </x-nav-link>

          @elseif(Auth::user()->role === 'pengawas')
            <x-nav-link href="{{ route('draft-pekerjaan.index') }}" :active="request()->routeIs('draft-pekerjaan.*')">
              <p class="mr-2" /> Draft Pekerjaan </p>
            </x-nav-link>

          @endif
        @endauth


      </ul>
    </div>
  </nav>
  