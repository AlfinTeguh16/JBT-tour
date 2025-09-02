<x-form type="form" :action="$action ?? request()->url()" method="GET" class="mb-4">
  <div class="flex gap-2">
    <input type="text"
           name="q"
           value="{{ $q ?? request('q') }}"
           class="rounded border-gray-300 w-full"
           placeholder="{{ $placeholder ?? 'Cari...'}}">
    <x-button type="submit" variant="secondary">Cari</x-button>
    @if(!empty(($show_reset ?? true)) && (request('q') || ($q ?? false)))
      <x-button :href="($reset ?? request()->url())" variant="secondary">Reset</x-button>
    @endif
  </div>
</x-form>
