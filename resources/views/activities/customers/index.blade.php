@extends('layouts.master')
@section('title','Customers')

@section('content')
<div class="flex items-center justify-between mb-6">
  <h1 class="text-2xl font-bold">Customers</h1>
  <a href="{{ route('customers.create') }}" class="px-4 py-2 bg-emerald-600 text-white rounded">Tambah</a>
</div>

@includeWhen(session('success') || session('error') || $errors->any(), 'partials.flash')

<form action="{{ route('customers.index') }}" method="get" class="mb-4">
  <input type="text" name="q" value="{{ $q ?? '' }}" class="rounded border-gray-300" placeholder="Cari nama/phone/email">
  <button class="px-3 py-1 rounded border">Cari</button>
</form>

<div class="overflow-x-auto bg-white rounded border">
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
          <td class="p-3"><a class="text-blue-600 hover:underline" href="{{ route('customers.show',$c) }}">{{ $c->name }}</a></td>
          <td class="p-3">{{ $c->phone }} • {{ $c->email }}</td>
          <td class="p-3">{{ $c->address }}</td>
          <td class="p-3 text-right">
            <a href="{{ route('customers.edit',$c) }}" class="text-blue-600 hover:underline">Edit</a>
            <form action="{{ route('customers.destroy',$c) }}" method="post" class="inline"
                  onsubmit="return confirm('Hapus customer ini?')">
              @csrf @method('DELETE')
              <button class="text-red-600 ml-2">Hapus</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="4" class="p-3 text-gray-500">Belum ada data.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-4">{{ $customers->links() }}</div>
@endsection
