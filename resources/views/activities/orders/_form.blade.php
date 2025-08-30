<x-form :action="$action" method="{{ $method ?? 'POST' }}">
  @csrf
  @if(($method ?? 'POST') !== 'POST')
    @method($method)
  @endif

  <div class="grid gap-4 md:grid-cols-2">
    <div>
      <label class="text-sm text-gray-600">Customer *</label>
      <select name="customer_id" class="w-full mt-1 rounded border-gray-300" required>
        <option value="">-- pilih --</option>
        @foreach($customers as $c)
          <option value="{{ $c->id }}" @selected(old('customer_id', $order->customer_id ?? '') == $c->id)>{{ $c->name }}</option>
        @endforeach
      </select>
      @error('customer_id') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
    </div>

    <div>
      <label class="text-sm text-gray-600">Requested At *</label>
      <input type="datetime-local" name="requested_at"
             value="{{ old('requested_at', isset($order)? \Illuminate\Support\Str::of($order->requested_at)->replace(' ','T') : '') }}"
             class="w-full mt-1 rounded border-gray-300" required>
      @error('requested_at') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
    </div>

    <div>
      <label class="text-sm text-gray-600">Service Date</label>
      <input type="datetime-local" name="service_date"
             value="{{ old('service_date', isset($order) && $order->service_date ? \Illuminate\Support\Str::of($order->service_date)->replace(' ','T') : '') }}"
             class="w-full mt-1 rounded border-gray-300">
      @error('service_date') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
    </div>

    <div>
      <label class="text-sm text-gray-600">Pickup</label>
      <input type="text" name="pickup_location" value="{{ old('pickup_location', $order->pickup_location ?? '') }}" class="w-full mt-1 rounded border-gray-300">
    </div>

    <div>
      <label class="text-sm text-gray-600">Dropoff</label>
      <input type="text" name="dropoff_location" value="{{ old('dropoff_location', $order->dropoff_location ?? '') }}" class="w-full mt-1 rounded border-gray-300">
    </div>

    <div class="md:col-span-2">
      <label class="text-sm text-gray-600">Catatan</label>
      <textarea name="notes" class="w-full mt-1 rounded border-gray-300" rows="3">{{ old('notes', $order->notes ?? '') }}</textarea>
    </div>

    <div class="md:col-span-2">
      <label class="text-sm text-gray-600">Status *</label>
      <select name="status" class="w-full mt-1 rounded border-gray-300" required>
        @foreach(['pending','assigned','in_progress','completed','cancelled'] as $st)
          <option value="{{ $st }}" @selected(old('status', $order->status ?? 'pending')==$st)>{{ ucfirst(str_replace('_',' ',$st)) }}</option>
        @endforeach
      </select>
      @error('status') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
    </div>
  </div>

  <div class="mt-6 flex gap-2">
    <x-button type="submit">Simpan</x-button>
    <x-button :href="route('orders.index')" variant="secondary">Batal</x-button>
  </div>
</x-form>
