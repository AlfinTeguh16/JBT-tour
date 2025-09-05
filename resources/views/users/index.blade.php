@extends('layouts.master')

@section('title', 'Users')

@section('content')
<div class="flex items-center justify-between mb-6">
  <h1 class="text-2xl font-bold">Users</h1>
  <x-button variant="primary" onclick="window.location.href='{{ route('users.create') }}'" class="px-4 py-2 bg-emerald-600 text-white rounded">Tambah User</x-button>
</div>

<div class="overflow-x-auto bg-white p-5">
  <table class="min-w-full text-sm">
    <thead class="border-b text-gray-600">
      <tr>
        <th class="text-left p-3">Nama</th>
        <th class="text-left p-3">Email</th>
        <th class="text-left p-3">Role</th>
        <th class="p-3">Aksi</th>
      </tr>
    </thead>
    <tbody class="divide-y">
      @foreach($users as $user)
        <tr>
          <td class="p-3">{{ $user->name }}</td>
          <td class="p-3">{{ $user->email }}</td>
          <td class="p-3">{{ ucfirst($user->role) }}</td>
          <td class="p-3 text-right">
            <x-button variant="edit" onclick="window.location.href='{{ route('users.edit', $user) }}'">Edit</x-button>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

<div class="mt-4">{{ $users->links() }}</div>
@endsection
