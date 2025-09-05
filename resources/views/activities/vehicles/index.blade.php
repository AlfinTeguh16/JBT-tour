@extends('layouts.master')
@section('title','Vehicles')

@section('content')
<x-card>
  <div name="header">
    <div class="flex items-center justify-between">
      <div class="text-xl font-semibold">Vehicles</div>
        <x-button onclick="window.location='{{ route('vehicles.create') }}'">Tambah</x-button>
    </div>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="border-b text-gray-600">
        <tr>
          <th class="text-left p-3">Plat</th>
          <th class="text-left p-3">Brand/Model</th>
          <th class="text-left p-3">Kapasitas</th>
          <th class="text-left p-3">Status</th>
          <th class="p-3">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y">
        @forelse($vehicles as $v)
          <tr>
            <td class="p-3">
              <x-button onclick="window.location='{{ route('vehicles.show',$v) }}'" variant="secondary">{{ $v->plate_no }}</x-button>
            </td>
            <td class="p-3">{{ $v->brand }} {{ $v->model }}</td>
            <td class="p-3">{{ $v->capacity ?? '-' }}</td>
            <td class="p-3"><x-badge>{{ ucfirst(str_replace('_',' ',$v->status)) }}</x-badge></td>
            <td class="p-3 text-right">
              <x-button onclick="window.location='{{ route('vehicles.edit',$v) }}'" variant="edit">Edit</x-button>
              <x-form :action="route('vehicles.destroy',$v)" method="POST" class="inline">
                @csrf @method('DELETE')
                <x-button type="submit" variant="delete">Hapus</x-button>
              </x-form>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="p-3 text-gray-500">Belum ada data.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">{{ $vehicles->links() }}</div>
</x-card>
@endsection
