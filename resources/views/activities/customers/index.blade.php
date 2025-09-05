@extends('layouts.master')
@section('title','Customers')

@section('content')
<x-card>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <div class="text-xl font-semibold">Customers</div>
      <x-button onclick="window.location.href='{{ route('customers.create') }}'" variant="primary">Tambah</x-button>
    </div>
  </x-slot>

  @includeWhen(session('success') || session('error') || $errors->any(), 'partials.flash')

  <form action="{{ route('customers.index') }}" method="get" class="mb-4 flex gap-2">
    <input type="text" name="q" value="{{ $q ?? '' }}" class="rounded border-gray-300 w-full px-3 py-2" placeholder="Cari nama/phone/email">
    <x-button type="submit" variant="secondary">Cari</x-button>
  </form>

  <div class="overflow-x-auto bg-white">
    <table class="min-w-full text-sm">
      <thead class="border-b text-gray-600">
        <tr>
          <th class="text-left p-3">Nama</th>
          <th class="text-left p-3">Kontak</th>
          <th class="text-left p-3">Alamat</th>
          <th class="p-3">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y">
        @forelse($customers as $c)
          <tr>
            <td class="p-3"><a class="text-blue-600 hover:underline" href="{{ route('customers.show', $c) }}">{{ $c->name }}</a></td>
            <td class="p-3">{{ $c->phone }} • {{ $c->email }}</td>
            <td class="p-3">{{ $c->address }}</td>
            <td class="p-3 text-right">
              <x-button onclick="window.location.href='{{ route('customers.edit', $c) }}'" variant="edit">Edit</x-button>
              <x-form :action="route('customers.destroy', $c)" method="POST" class="inline" onsubmit="return confirm('Hapus customer ini?')">
                @csrf
                @method('DELETE')
                <x-button type="submit" variant="delete">Hapus</x-button>
              </x-form>
            </td>
          </tr>
        @empty
          <tr><td colspan="4" class="p-3 text-gray-500">Belum ada data.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">{{ $customers->links() }}</div>
</x-card>
@endsection
