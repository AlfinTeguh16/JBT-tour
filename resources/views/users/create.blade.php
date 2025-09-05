@extends('layouts.master')

@section('title', 'Tambah User')

@section('content')
<x-card>
  <x-slot name="header">
    <h2 class="text-xl font-semibold">Tambah User</h2>
  </x-slot>

  <form action="{{ route('users.store') }}" method="POST">
    @csrf
    <div class="space-y-4">
      <div>
        <label for="name" class="text-sm text-gray-500">Nama</label>
        <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded border-gray-300" required>
      </div>

      <div>
        <label for="email" class="text-sm text-gray-500">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded border-gray-300" required>
      </div>

      <div>
        <label for="password" class="text-sm text-gray-500">Password</label>
        <input type="password" name="password" class="w-full rounded border-gray-300" required>
      </div>

      <div>
        <label for="password_confirmation" class="text-sm text-gray-500">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="w-full rounded border-gray-300" required>
      </div>

      <div>
        <label for="role" class="text-sm text-gray-500">Role</label>
        <select name="role" class="w-full rounded border-gray-300" required>
          <option value="admin">Admin</option>
          <option value="staff">Staff</option>
          <option value="driver">Driver</option>
          <option value="guide">Guide</option>
        </select>
      </div>

      <x-button type="submit" variant="primary">Tambah User</x-button>
    </div>
  </form>
</x-card>
@endsection
