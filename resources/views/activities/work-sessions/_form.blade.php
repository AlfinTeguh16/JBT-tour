<x-form :action="$action" method="{{ $method ?? 'POST' }}">
  @csrf
  @if(($method ?? 'POST') !== 'POST')
    @method($method)
  @endif

  <div class="grid gap-4 md:grid-cols-2">
    <div class="md:col-span-2">
      <label class="text-sm text-gray-600">Assignment</label>
      <select name="assignment_id" class="w-full mt-1 rounded border-gray-300">
        <option value="">-- none --</option>
        @foreach($assignments as $a)
          <option value="{{ $a->id }}" @selected(old('assignment_id', $workSession->assignment_id ?? '')==$a->id)>
            #{{ $a->id }} — {{ $a->order->customer->name ?? 'Customer' }} ({{ $a->order->pickup_location }} → {{ $a->order->dropoff_location }})
          </option>
        @endforeach
      </select>
      @error('assignment_id') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
    </div>

    <div>
      <label class="text-sm text-gray-600">Started At *</label>
      <input type="datetime-local" name="started_at"
             value="{{ old('started_at', isset($workSession)? \Illuminate\Support\Str::of($workSession->started_at)->replace(' ','T') : '') }}"
             class="w-full mt-1 rounded border-gray-300" required>
      @error('started_at') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
    </div>

    <div>
      <label class="text-sm text-gray-600">Ended At</label>
      <input type="datetime-local" name="ended_at"
             value="{{ old('ended_at', isset($workSession) && $workSession->ended_at ? \Illuminate\Support\Str::of($workSession->ended_at)->replace(' ','T') : '') }}"
             class="w-full mt-1 rounded border-gray-300">
      @error('ended_at') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="md:col-span-2">
      <label class="text-sm text-gray-600">Hours (opsional, auto dihitung jika ended diisi)</label>
      <input type="number" step="0.25" name="hours_decimal" value="{{ old('hours_decimal', $workSession->hours_decimal ?? '') }}" class="w-full mt-1 rounded border-gray-300">
    </div>
  </div>

  <div class="mt-6 flex gap-2">
    <x-button type="submit">Simpan</x-button>
    <x-button :href="route('work-sessions.index')" variant="secondary">Batal</x-button>
  </div>
</x-form>
