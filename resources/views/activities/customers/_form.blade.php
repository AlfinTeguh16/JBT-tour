@csrf
<div class="grid gap-4 md:grid-cols-2">
  <div>
    <label class="text-sm text-gray-600">Nama *</label>
    <input type="text" name="name" value="{{ old('name', $customer->name ?? '') }}" class="w-full mt-1 rounded border-gray-300" required>
    @error('name') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
  </div>
  <div>
    <label class="text-sm text-gray-600">Email</label>
    <input type="email" name="email" value="{{ old('email', $customer->email ?? '') }}" class="w-full mt-1 rounded border-gray-300">
    @error('email') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
  </div>
  <div>
    <label class="text-sm text-gray-600">Phone</label>
    <input type="text" name="phone" value="{{ old('phone', $customer->phone ?? '') }}" class="w-full mt-1 rounded border-gray-300">
    @error('phone') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
  </div>
  <div class="md:col-span-2">
    <label class="text-sm text-gray-600">Address</label>
    <input type="text" name="address" value="{{ old('address', $customer->address ?? '') }}" class="w-full mt-1 rounded border-gray-300">
    @error('address') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
  </div>
</div>
<div class="mt-6 flex gap-2">
  <button class="px-4 py-2 rounded bg-emerald-600 text-white">Simpan</button>
  <a href="{{ route('customers.index') }}" class="px-4 py-2 rounded border">Batal</a>
</div>
