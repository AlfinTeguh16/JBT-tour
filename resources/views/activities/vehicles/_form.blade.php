<x-form :action="$action" method="{{ $method ?? 'POST' }}">
  @csrf
  @if(($method ?? 'POST') !== 'POST')
    @method($method)
  @endif

  <div class="grid gap-4 md:grid-cols-2">
    <div>
      <label class="text-sm text-gray-600">Plat *</label>
      <input type="text" name="plate_no" value="{{ old('plate_no', $vehicle->plate_no ?? '') }}" class="w-full mt-1 rounded border-gray-300" required>
      @error('plate_no') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
    </div>
    <div>
      <label class="text-sm text-gray-600">Brand</label>
      <input type="text" name="brand" value="{{ old('brand', $vehicle->brand ?? '') }}" class="w-full mt-1 rounded border-gray-300">
    </div>
    <div>
      <label class="text-sm text-gray-600">Model</label>
      <input type="text" name="model" value="{{ old('model', $vehicle->model ?? '') }}" class="w-full mt-1 rounded border-gray-300">
    </div>
    <div>
      <label class="text-sm text-gray-600">Kapasitas</label>
      <input type="number" name="capacity" min="1" value="{{ old('capacity', $vehicle->capacity ?? '') }}" class="w-full mt-1 rounded border-gray-300">
    </div>
    <div class="md:col-span-2">
      <label class="text-sm text-gray-600">Status *</label>
      <select name="status" class="w-full mt-1 rounded border-gray-300" required>
        @foreach(['available'=>'Available','in_use'=>'In Use','maintenance'=>'Maintenance'] as $k=>$v)
          <option value="{{ $k }}" @selected(old('status',$vehicle->status ?? 'available')==$k)>{{ $v }}</option>
        @endforeach
      </select>
      @error('status') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
    </div>
  </div>

  <div class="mt-6 flex gap-2">
    <x-button type="submit">Simpan</x-button>
    <x-button :href="route('vehicles.index')" variant="secondary">Batal</x-button>
  </div>
</x-form>
