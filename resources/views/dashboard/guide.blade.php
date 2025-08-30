@extends('layouts.master')

@section('title', 'Dashboard Guide')

@section('content')
@php
  $statusColors = [
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
      <h1 class="text-3xl font-bold text-gray-900">Dashboard Guide</h1>
      <p class="text-gray-500">Halo, {{ $user->name }} — pantau tugas & jam kerjamu.</p>
    </div>
    <div class="text-right">
      <div class="text-sm text-gray-500">{{ now()->format('l, d M Y H:i') }}</div>
      <div class="text-xs text-gray-400">Server time</div>
    </div>
  </div>

  {{-- Jam Kerja --}}
  <div class="grid gap-4 md:grid-cols-2">
    <div class="rounded-2xl border border-gray-200 p-5">
      <div class="text-sm text-gray-500">Jam Kerja Minggu Ini</div>
      <div class="mt-2 text-3xl font-semibold">{{ number_format($myHoursWeek ?? 0, 2) }} <span class="text-base font-normal">jam</span></div>
    </div>
    <div class="rounded-2xl border border-gray-200 p-5">
      <div class="text-sm text-gray-500">Jam Kerja Bulan Ini</div>
      <div class="mt-2 text-3xl font-semibold">{{ number_format($myHoursMonth ?? 0, 2) }} <span class="text-base font-normal">jam</span></div>
    </div>
  </div>

  <div class="grid gap-6 lg:grid-cols-2">
    {{-- Tugas Hari Ini --}}
    <div class="rounded-2xl border border-gray-200 p-5">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-900">Tugas Hari Ini</h2>
        <a href="{{ route('assignments.index') }}" class="text-sm text-blue-600 hover:underline">Lihat semua</a>
      </div>

      @if(($myToday ?? collect())->isEmpty())
        <div class="text-sm text-gray-500">Tidak ada tugas hari ini.</div>
      @else
        <ul class="space-y-3">
          @foreach ($myToday as $a)
            <li class="rounded-xl border border-gray-100 p-3">
              <div class="flex items-center justify-between">
                <div>
                  <div class="font-medium">{{ $a->order->customer->name ?? '-' }}</div>
                  <div class="text-xs text-gray-500">
                    {{ \Illuminate\Support\Carbon::parse($a->scheduled_start)->format('H:i') }}
                    — {{ $a->vehicle->plate_no ?? 'No Vehicle' }}
                  </div>
                  <div class="text-xs text-gray-500">
                    {{ $a->order->pickup_location ?? '-' }} → {{ $a->order->dropoff_location ?? '-' }}
                  </div>
                </div>
                <div>
                  @php $st = $a->status; @endphp
                  <span class="px-2 py-1 text-xs rounded-full ring-1 {{ $statusColors[$st] ?? 'bg-gray-100 text-gray-800 ring-gray-200' }}">
                    {{ ucfirst(str_replace('_',' ', $st)) }}
                  </span>
                </div>
              </div>
            </li>
          @endforeach
        </ul>
      @endif
    </div>

    {{-- Penugasan Mendatang --}}
    <div class="rounded-2xl border border-gray-200 p-5">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-900">Penugasan Mendatang</h2>
        <a href="{{ route('assignments.index') }}" class="text-sm text-blue-600 hover:underline">Lihat semua</a>
      </div>

      @if(($myUpcoming ?? collect())->isEmpty())
        <div class="text-sm text-gray-500">Tidak ada penugasan mendatang.</div>
      @else
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead class="text-left text-gray-500 border-b">
              <tr>
                <th class="py-2 pr-4">Mulai</th>
                <th class="py-2 pr-4">Customer</th>
                <th class="py-2 pr-4">Kendaraan</th>
                <th class="py-2 pr-4">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y">
              @foreach ($myUpcoming as $a)
                <tr class="hover:bg-gray-50">
                  <td class="py-2 pr-4">{{ $a->scheduled_start ? \Illuminate\Support\Carbon::parse($a->scheduled_start)->format('d M Y H:i') : '-' }}</td>
                  <td class="py-2 pr-4">{{ $a->order->customer->name ?? '-' }}</td>
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
  </div>

  {{-- Notifikasi --}}
  <div class="rounded-2xl border border-gray-200 p-5">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-lg font-semibold text-gray-900">Notifikasi</h2>
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
