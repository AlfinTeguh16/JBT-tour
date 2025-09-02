@if(session('success'))
  <div class="mb-4 px-4 py-3 rounded bg-emerald-50 text-emerald-800 border border-emerald-200">
    {{ session('success') }}
  </div>
@endif

@if(session('error'))
  <div class="mb-4 px-4 py-3 rounded bg-red-50 text-red-800 border border-red-200">
    {{ session('error') }}
  </div>
@endif

@if($errors->any())
  <div class="mb-4 px-4 py-3 rounded bg-yellow-50 text-yellow-800 border border-yellow-200">
    <ul class="list-disc list-inside">
      @foreach($errors->all() as $err)
        <li>{{ $err }}</li>
      @endforeach
    </ul>
  </div>
@endif
