@extends('layouts.master')
@section('title','Detail Customer')

@section('content')
<h1 class="text-2xl font-bold mb-6">Detail Customer</h1>

<div class="bg-white rounded border p-5">
  <div class="grid gap-2 md:grid-cols-2">
    <div><span class="text-gray-500 text-sm">Nama</span><div class="font-medium">{{ $customer->name }}</div></div>
    <div><span class="text-gray-500 text-sm">Email</span><div class="font-medium">{{ $customer->email ?? '-' }}</div></div>
    <div><span class="text-gray-500 text-sm">Phone</span><div class="font-medium">{{ $customer->phone ?? '-' }}</div></div>
    <div class="md:col-span-2"><span class="text-gray-500 text-sm">Alamat</span><div class="font-medium">{{ $customer->address ?? '-' }}</div></div>
  </div>
</div>

<h2 class="text-lg font-semibold mt-8 mb-3">Orders</h2>
<div class="overflow-x-auto bg-white rounded border">
  <table class="min-w-full text-sm">
    <thead class="border-b text-gray-600"><tr>
      <th class="text-left p-3">Requested</th>
      <th class="text-left p-3">Pickup</th>
      <th class="text-left p-3">Dropoff</th>
      <th class="text-left p-3">Status</th>
    </tr></thead>
    <tbody class="divide-y">
      @forelse($customer->orders as $o)
      <tr>
        <td class="p-3"><a href="{{ route('orders.show',$o) }}" class="text-blue-600 hover:underline">{{ $o->requested_at }}</a></td>
        <td class="p-3">{{ $o->pickup_location }}</td>
        <td class="p-3">{{ $o->dropoff_location }}</td>
        <td class="p-3">{{ ucfirst(str_replace('_',' ',$o->status)) }}</td>
      </tr>
      @empty
      <tr><td colspan="4" class="p-3 text-gray-500">Belum ada order.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
