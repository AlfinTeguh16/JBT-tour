@extends('layouts.master')

@section('title', 'Profile')

@section('content')
<x-card>
  <x-slot name="header">
    <h2 class="text-xl font-semibold">Update Profile</h2>
  </x-slot>

  <form action="{{ route('profile.update') }}" method="POST">
    @csrf
    <div class="space-y-4">
      <div>
        <label for="name" class="text-sm text-gray-500">Name</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded border-gray-300" required>
      </div>

      <div>
        <label for="email" class="text-sm text-gray-500">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded border-gray-300" required>
      </div>

      <div>
        <label for="phone" class="text-sm text-gray-500">Phone</label>
        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded border-gray-300">
      </div>

      <div>
        <label for="password" class="text-sm text-gray-500">New Password (optional)</label>
        <input type="password" name="password" class="w-full rounded border-gray-300">
      </div>

      <div>
        <label for="password_confirmation" class="text-sm text-gray-500">Confirm Password</label>
        <input type="password" name="password_confirmation" class="w-full rounded border-gray-300">
      </div>

      <x-button type="submit" variant="primary">Update Profile</x-button>
    </div>
  </form>
</x-card>
@endsection
