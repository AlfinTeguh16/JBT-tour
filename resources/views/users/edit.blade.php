@extends('layouts.master')

@section('title', 'Edit User')

@section('content')
<x-card>
  <x-slot name="header">
    <h2 class="text-xl font-semibold">Edit User</h2>
  </x-slot>

  <form action="{{ route('users.update', $user) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="space-y-4">
      <div>
        <label for="name" class="text-sm text-gray-500">Nama</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded border-gray-300" required>
      </div>

      <div>
        <label for="email" class="text-sm text-gray-500">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded border-gray-300" required>
      </div>

      <div>
        <label for="password" class="text-sm text-gray-500">Password (Kosongkan jika tidak ingin mengubah)</label>
        <input type="password" name="password" class="w-full rounded border-gray-300">
      </div>

      <div>
        <label for="password_confirmation" class="text-sm text-gray-500">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="w-full rounded border-gray-300">
      </div>

      <div>
        <label for="role" class="text-sm text-gray-500">Role</label>
        <select name="role" class="w-full rounded border-gray-300" required>
          <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
          <option value="staff" {{ $user->role == 'staff' ? 'selected' : '' }}>Staff</option>
          <option value="driver" {{ $user->role == 'driver' ? 'selected' : '' }}>Driver</option>
          <option value="guide" {{ $user->role == 'guide' ? 'selected' : '' }}>Guide</option>
        </select>
      </div>

      <x-button type="submit" variant="primary">Update User</x-button>
    </div>
  </form>
</x-card>
@endsection
