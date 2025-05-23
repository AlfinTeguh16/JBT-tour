<nav x-data id="navigation" class="bg-white shadow md:static lg:w-64 lg:min-w-64 transform transition-transform duration-300 ease-in-out hidden md:hidden lg:block fixed top-0 left-0 w-full h-[100%] lg:h-auto z-50"
     :class="{ 'translate-x-0': $store.menu.open, '-translate-x-full': !$store.menu.open, 'md:translate-x-0': true }">

  <div :class="{ 'block': $store.menu.open, 'hidden': !$store.menu.open }" class="md:block px-4 py-6">
    <div class="flex items-center justify-between mb-4">
      <h2 class=" text-2xl font-bold text-blue-600">CV. Cipta Arya</h2>
      <button id="close-menu" class="lg:hidden text-white active:bg-gray-500 hover:bg-gray-500 px-2 py-1 rounded-lg bg-gray-400"><i class="ph-bold ph-x"></i></button>
    </div>
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

            <x-nav-link href="{{ route('transaksi-draft-pekerjaan.index') }}" :active="request()->routeIs('transaksi-draft-pekerjaan.*')">
              <p class="mr-2" /> Transaksi Draft Pekerjaan </p>
            </x-nav-link>

            <x-nav-link href="{{ route('data-neraca.index') }}" :active="request()->routeIs('data-neraca.*')">
              <p class="mr-2" /> Data Neraca </p>
            </x-nav-link>

            <x-nav-link href="{{ route('laporan-keuangan.index') }}" :active="request()->routeIs('laporan-keuangan.*')">
              <p class="mr-2" /> Laporan Keuangan </p>
            </x-nav-link>

            <x-nav-link href="{{ route('arus-kas.index') }}" :active="request()->routeIs('arus-kas.*')">
              <p class="mr-2" /> Arus Kas </p>
            </x-nav-link>

            <x-nav-link href="{{ route('laba-rugi.index') }}" :active="request()->routeIs('laba-rugi.*')">
              <p class="mr-2" /> Laba Rugi </p>
            </x-nav-link>

            <x-nav-link href="{{ route('jurnal-umum.index') }}" :active="request()->routeIs('jurnal-umum.*')">
              <p class="mr-2" /> Jurnal Umum</p>
            </x-nav-link>

            <x-nav-link href="{{ route('perubahan-modal.index') }}" :active="request()->routeIs('perubahan-modal.*')">
              <p class="mr-2" /> Perubahan Modal</p>
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

            <x-nav-link href="{{ route('draft-pekerjaan.index') }}" :active="request()->routeIs('draft-pekerjaan.*')">
              <p class="mr-2" /> Draft Pekerjaan </p>
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



