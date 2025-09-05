<x-form :action="$action" method="{{ $method ?? 'POST' }}">
  @csrf
  @if(($method ?? 'POST') !== 'POST')
    @method($method)
  @endif

  <input type="hidden" name="staff_id" value="{{ old('staff_id', auth()->id()) }}">

  <div class="grid gap-4 md:grid-cols-2">
    <div class="md:col-span-2">
      <label class="text-sm text-gray-600">Order *</label>
      @if(isset($order) && $order)
        <input type="hidden" name="order_id" value="{{ $order->id }}">
        <input type="text" class="w-full mt-1 rounded border-gray-300 bg-gray-50"
               value="#{{ $order->id }} — {{ $order->customer->name ?? 'Customer' }} ({{ $order->pickup_location }} → {{ $order->dropoff_location }})" disabled>
      @else
        <select name="order_id" class="w-full mt-1 rounded border-gray-300" required>
          <option value="">-- pilih dari orders pending --</option>
          @foreach(\App\Models\Order::where('status','pending')->orderByDesc('requested_at')->limit(100)->get() as $o)
            <option value="{{ $o->id }}" @selected(old('order_id', $assignment->order_id ?? '')==$o->id)">
              #{{ $o->id }} — {{ $o->customer->name ?? 'Customer' }} ({{ $o->pickup_location }} → {{ $o->dropoff_location }})
            </option>
          @endforeach
        </select>
      @endif
      @error('order_id') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
    </div>

    <div>
      <label class="text-sm text-gray-600">Driver</label>
      <select name="driver_id" class="w-full mt-1 rounded border-gray-300">
        <option value="">-- none --</option>
        @foreach($drivers as $d)
          <option value="{{ $d->id }}" @selected(old('driver_id', $assignment->driver_id ?? '')==$d->id)>{{ $d->name }}</option>
        @endforeach
      </select>
      @error('driver_id') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
    </div>

    <div>
      <label class="text-sm text-gray-600">Guide</label>
      <select name="guide_id" class="w-full mt-1 rounded border-gray-300">
        <option value="">-- none --</option>
        @foreach($guides as $g)
          <option value="{{ $g->id }}" @selected(old('guide_id', $assignment->guide_id ?? '')==$g->id)>{{ $g->name }}</option>
        @endforeach
      </select>
      @error('guide_id') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="md:col-span-2">
      <label class="text-sm text-gray-600">Vehicle</label>
      <select name="vehicle_id" class="w-full mt-1 rounded border-gray-300">
        <option value="">-- none --</option>
        @foreach($vehicles as $v)
          <option value="{{ $v->id }}" @selected(old('vehicle_id', $assignment->vehicle_id ?? '')==$v->id)>{{ $v->plate_no }} — {{ $v->brand }} {{ $v->model }}</option>
        @endforeach
      </select>
      @error('vehicle_id') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
    </div>

    <div>
      <label class="text-sm text-gray-600">Scheduled Start</label>
      <input type="datetime-local" name="scheduled_start"
             value="{{ old('scheduled_start', isset($assignment) && $assignment->scheduled_start ? \Illuminate\Support\Str::of($assignment->scheduled_start)->replace(' ','T') : '') }}"
             class="w-full mt-1 rounded border-gray-300">
    </div>

    <div>
      <label class="text-sm text-gray-600">Scheduled End</label>
      <input type="datetime-local" name="scheduled_end"
             value="{{ old('scheduled_end', isset($assignment) && $assignment->scheduled_end ? \Illuminate\Support\Str::of($assignment->scheduled_end)->replace(' ','T') : '') }}"
             class="w-full mt-1 rounded border-gray-300">
      @error('scheduled_end') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
    </div>

    <div>
      <label class="text-sm text-gray-600">Estimasi Jam</label>
      <input type="number" step="0.25" name="estimated_hours" value="{{ old('estimated_hours', $assignment->estimated_hours ?? '') }}" class="w-full mt-1 rounded border-gray-300">
    </div>

    <div>
      <label class="text-sm text-gray-600">Status *</label>
      <select name="status" class="w-full mt-1 rounded border-gray-300" required>
        @foreach(['assigned','in_progress','completed','cancelled'] as $st)
          <option value="{{ $st }}" @selected(old('status', $assignment->status ?? 'assigned')==$st)>{{ ucfirst(str_replace('_',' ',$st)) }}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="mt-6 flex gap-2">
    <x-button type="submit">Simpan</x-button>
    <x-button :href="route('assignments.index')" variant="secondary">Batal</x-button>
  </div>
</x-form>
