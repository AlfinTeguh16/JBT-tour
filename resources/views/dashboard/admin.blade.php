@extends('layouts.master')

@section('title', 'Dashboard Admin')

@section('content')
@php
    // Map warna status order
    $statusColors = [
        'pending'      => 'bg-yellow-100 text-yellow-800 ring-yellow-200',
        'assigned'     => 'bg-blue-100 text-blue-800 ring-blue-200',
        'in_progress'  => 'bg-indigo-100 text-indigo-800 ring-indigo-200',
        'completed'    => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
        'cancelled'    => 'bg-red-100 text-red-800 ring-red-200',
    ];
@endphp

<section class="space-y-8">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-500">Hi, {{ $user->name }} — pantau operasi transportasi hari ini.</p>
        </div>
        <div class="text-right">
            <div class="text-sm text-gray-500">{{ now()->format('l, d M Y H:i') }}</div>
            <div class="text-xs text-gray-400">Server time</div>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid gap-4 md:grid-cols-4">
        <a href="{{ route('customers.index') }}" class="block rounded-2xl border border-gray-200 p-5 hover:shadow-sm transition">
            <div class="text-sm text-gray-500">Total Customers</div>
            <div class="mt-2 text-3xl font-semibold">{{ number_format($summary['total_customers'] ?? 0) }}</div>
        </a>
        <a href="{{ route('vehicles.index') }}" class="block rounded-2xl border border-gray-200 p-5 hover:shadow-sm transition">
            <div class="text-sm text-gray-500">Total Vehicles</div>
            <div class="mt-2 text-3xl font-semibold">{{ number_format($summary['total_vehicles'] ?? 0) }}</div>
        </a>
        <a href="{{ route('orders.index') }}" class="block rounded-2xl border border-gray-200 p-5 hover:shadow-sm transition">
            <div class="text-sm text-gray-500">Total Orders</div>
            <div class="mt-2 text-3xl font-semibold">{{ number_format($summary['total_orders'] ?? 0) }}</div>
        </a>
        <a href="{{ route('dashboard.admin') }}" class="block rounded-2xl border border-gray-200 p-5 hover:shadow-sm transition">
            <div class="text-sm text-gray-500">Total Users</div>
            <div class="mt-2 text-3xl font-semibold">{{ number_format($summary['total_users'] ?? 0) }}</div>
        </a>
    </div>

    {{-- Status Orders --}}
    <div class="rounded-2xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Ringkasan Status Orders</h2>
            <a href="{{ route('orders.index') }}" class="text-sm text-blue-600 hover:underline">Lihat semua</a>
        </div>

        <div class="grid gap-3 sm:grid-cols-5">
            @foreach (['pending','assigned','in_progress','completed','cancelled'] as $st)
                @php
                    $count = method_exists($orderStatusCounts, 'get') ? ($orderStatusCounts->get($st, 0)) : ($orderStatusCounts[$st] ?? 0);
                @endphp
                <div class="rounded-xl border border-gray-100 p-4">
                    <div class="text-xs uppercase tracking-wide text-gray-500">{{ str_replace('_',' ',$st) }}</div>
                    <div class="mt-2 flex items-baseline gap-2">
                        <div class="text-2xl font-semibold">{{ number_format($count) }}</div>
                        <span class="px-2 py-1 text-xs rounded-full ring-1 {{ $statusColors[$st] ?? 'bg-gray-100 text-gray-800 ring-gray-200' }}">
                            {{ ucfirst(str_replace('_',' ', $st)) }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Orders Hari Ini --}}
        <div class="rounded-2xl border border-gray-200 p-5 lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Orders Hari Ini</h2>
                <a href="{{ route('orders.index') }}" class="text-sm text-blue-600 hover:underline">Kelola orders</a>
            </div>

            @if(($todayOrders ?? collect())->isEmpty())
                <div class="text-sm text-gray-500">Belum ada order hari ini.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-left text-gray-500 border-b">
                            <tr>
                                <th class="py-2 pr-4">Waktu</th>
                                <th class="py-2 pr-4">Customer</th>
                                <th class="py-2 pr-4">Pickup</th>
                                <th class="py-2 pr-4">Dropoff</th>
                                <th class="py-2 pr-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach ($todayOrders as $o)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 pr-4">{{ \Illuminate\Support\Carbon::parse($o->requested_at)->format('H:i') }}</td>
                                    <td class="py-2 pr-4 font-medium">
                                        <a class="hover:underline" href="{{ route('orders.show', $o->id) }}">
                                            {{ $o->customer->name ?? '-' }}
                                        </a>
                                    </td>
                                    <td class="py-2 pr-4">{{ $o->pickup_location ?? '-' }}</td>
                                    <td class="py-2 pr-4">{{ $o->dropoff_location ?? '-' }}</td>
                                    <td class="py-2 pr-4">
                                        @php $st = $o->status; @endphp
                                        <span class="px-2 py-1 text-xs rounded-full ring-1 {{ $statusColors[$st] ?? 'bg-gray-100 text-gray-800 ring-gray-200' }}">
                                            {{ ucfirst(str_replace('_',' ', $st)) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Utilisasi Kendaraan Hari Ini --}}
        <div class="rounded-2xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Utilisasi Kendaraan (Hari Ini)</h2>
                <a href="{{ route('vehicles.index') }}" class="text-sm text-blue-600 hover:underline">Lihat kendaraan</a>
            </div>

            @if(($vehicleUtilizationToday ?? collect())->isEmpty())
                <div class="text-sm text-gray-500">Belum ada penugasan kendaraan hari ini.</div>
            @else
                <ul class="space-y-3">
                    @foreach ($vehicleUtilizationToday as $row)
                        <li class="flex items-center justify-between rounded-xl border border-gray-100 p-3">
                            <div class="truncate">
                                <div class="font-medium">
                                    {{ $row->vehicle->plate_no ?? 'Tanpa Kendaraan' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $row->vehicle->brand ?? '-' }} {{ $row->vehicle->model ?? '' }}
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-semibold">{{ $row->jobs }}</div>
                                <div class="text-xs text-gray-500">jobs</div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    {{-- Upcoming Assignments --}}
    <div class="rounded-2xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Penugasan Aktif / Mendatang</h2>
            <a href="{{ route('assignments.index') }}" class="text-sm text-blue-600 hover:underline">Kelola penugasan</a>
        </div>

        @if(($upcomingAssignments ?? collect())->isEmpty())
            <div class="text-sm text-gray-500">Belum ada penugasan aktif.</div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-left text-gray-500 border-b">
                        <tr>
                            <th class="py-2 pr-4">Start</th>
                            <th class="py-2 pr-4">Customer</th>
                            <th class="py-2 pr-4">Driver</th>
                            <th class="py-2 pr-4">Guide</th>
                            <th class="py-2 pr-4">Vehicle</th>
                            <th class="py-2 pr-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($upcomingAssignments as $a)
                            <tr class="hover:bg-gray-50">
                                <td class="py-2 pr-4">
                                    {{ $a->scheduled_start ? \Illuminate\Support\Carbon::parse($a->scheduled_start)->format('d M Y H:i') : '-' }}
                                </td>
                                <td class="py-2 pr-4">
                                    <a class="hover:underline" href="{{ route('orders.show', $a->order_id) }}">
                                        {{ $a->order->customer->name ?? '-' }}
                                    </a>
                                </td>
                                <td class="py-2 pr-4">{{ $a->driver->name ?? '-' }}</td>
                                <td class="py-2 pr-4">{{ $a->guide->name ?? '-' }}</td>
                                <td class="py-2 pr-4">{{ $a->vehicle->plate_no ?? '-' }}</td>
                                <td class="py-2 pr-4">
                                    @php $st = $a->status; @endphp
                                    <span class="px-2 py-1 text-xs rounded-full ring-1 {{ $statusColors[$st] ?? 'bg-gray-100 text-gray-800 ring-gray-200' }}">
                                        {{ ucfirst(str_replace('_',' ', $st)) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Notifications --}}
    <div class="rounded-2xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Notifikasi Terbaru</h2>
            <a href="{{ route('notifications.index') }}" class="text-sm text-blue-600 hover:underline">Semua notifikasi</a>
        </div>

        @if(($notifications ?? collect())->isEmpty())
            <div class="text-sm text-gray-500">Tidak ada notifikasi.</div>
        @else
            <ul class="divide-y">
                @foreach ($notifications as $n)
                    <li class="py-3 flex items-start justify-between">
                        <div class="pr-4">
                            <div class="font-medium">{{ $n->title }}</div>
                            @if($n->body)
                                <div class="text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($n->body, 120) }}</div>
                            @endif
                        </div>
                        <div class="text-xs text-gray-500 whitespace-nowrap">
                            {{ \Illuminate\Support\Carbon::parse($n->created_at)->diffForHumans() }}
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</section>
@endsection
