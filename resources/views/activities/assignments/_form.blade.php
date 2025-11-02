{{-- resources/views/activities/assignments/_form.blade.php --}}
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
        <select id="orderSelect" name="order_id" class="w-full mt-1 rounded border-gray-300" required>
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

    {{-- Driver select + usage --}}
    <div>
      <label class="text-sm text-gray-600">Driver</label>
      <select id="driverSelect" name="driver_id" class="w-full mt-1 rounded border-gray-300">
        <option value="">-- none --</option>
        @foreach($drivers as $d)
          <option value="{{ $d->id }}" @selected(old('driver_id', $assignment->driver_id ?? '')==$d->id)>{{ $d->name }}</option>
        @endforeach
      </select>
      @error('driver_id') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror

      <div id="driverInfo" class="mt-2 text-xs text-gray-600 hidden">
        <div id="driverInfoText"></div>
        <div id="driverWarning" class="mt-1 text-red-600 hidden text-sm"></div>
      </div>
    </div>

    {{-- Guide select + usage --}}
    <div>
      <label class="text-sm text-gray-600">Guide</label>
      <select id="guideSelect" name="guide_id" class="w-full mt-1 rounded border-gray-300">
        <option value="">-- none --</option>
        @foreach($guides as $g)
          <option value="{{ $g->id }}" @selected(old('guide_id', $assignment->guide_id ?? '')==$g->id)>{{ $g->name }}</option>
        @endforeach
      </select>
      @error('guide_id') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror

      <div id="guideInfo" class="mt-2 text-xs text-gray-600 hidden">
        <div id="guideInfoText"></div>
        <div id="guideWarning" class="mt-1 text-red-600 hidden text-sm"></div>
      </div>
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
      <input id="scheduledStart" type="datetime-local" name="scheduled_start"
             value="{{ old('scheduled_start', isset($assignment) && $assignment->scheduled_start ? \Illuminate\Support\Str::of($assignment->scheduled_start)->replace(' ','T') : '') }}"
             class="w-full mt-1 rounded border-gray-300">
    </div>

    <div>
      <label class="text-sm text-gray-600">Scheduled End</label>
      <input id="scheduledEnd" type="datetime-local" name="scheduled_end"
             value="{{ old('scheduled_end', isset($assignment) && $assignment->scheduled_end ? \Illuminate\Support\Str::of($assignment->scheduled_end)->replace(' ','T') : '') }}"
             class="w-full mt-1 rounded border-gray-300">
      @error('scheduled_end') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
    </div>

    <div>
      <label class="text-sm text-gray-600">Estimasi Jam</label>
      <input id="estimatedHours" type="number" step="0.25" name="estimated_hours" value="{{ old('estimated_hours', $assignment->estimated_hours ?? '') }}" class="w-full mt-1 rounded border-gray-300">
      <div class="text-xs text-gray-500 mt-1">Kosongkan untuk auto-hitung dari jadwal</div>
    </div>

    
  </div>

  <div id="pendingNote" class="mt-4 text-sm text-orange-600 hidden">
    <strong>Catatan:</strong> Jika driver/guide melebihi batas jam bulanan atau sedang memiliki tugas <em>in_progress</em>, assignment akan disimpan sebagai <em>pending</em>.
  </div>

  <div class="mt-6 flex gap-2">
    <x-button id="submitBtn" type="submit">Simpan</x-button>
    <x-button :href="route('assignments.index')" variant="secondary">Batal</x-button>
  </div>
  <div class="mt-4 text-sm text-gray-500 italic">
    Status penugasan akan <strong>ditentukan otomatis</strong>:
    <br>• <span class="text-emerald-600">Assigned</span> 
    <br>• <span class="text-orange-600">Pending</span>
  </div>
</x-form>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const scheduledStart = document.getElementById('scheduledStart');
  const scheduledEnd = document.getElementById('scheduledEnd');
  const estimatedHours = document.getElementById('estimatedHours');

  const driverSelect = document.getElementById('driverSelect');
  const guideSelect = document.getElementById('guideSelect');

  const driverInfo = document.getElementById('driverInfo');
  const driverInfoText = document.getElementById('driverInfoText');
  const driverWarning = document.getElementById('driverWarning');

  const guideInfo = document.getElementById('guideInfo');
  const guideInfoText = document.getElementById('guideInfoText');
  const guideWarning = document.getElementById('guideWarning');

  const pendingNote = document.getElementById('pendingNote');
  const submitBtn = document.getElementById('submitBtn');

  // helper: parse datetime-local value to JS Date
  function parseDateLocal(val) {
    if (!val) return null;
    // val format: "YYYY-MM-DDTHH:mm"
    return new Date(val);
  }

  function computeEstimatedFromSchedule() {
    const a = parseDateLocal(scheduledStart.value);
    const b = parseDateLocal(scheduledEnd.value);
    if (a && b && b > a) {
      const diffMs = b - a;
      const hours = Math.round((diffMs / (1000 * 60 * 60)) * 100) / 100; // 2 decimals
      estimatedHours.value = hours;
      return hours;
    }
    return null;
  }

  // fetch user hours for a role
  async function fetchUserHours(userId, role, year, month) {
    if (!userId) return null;
    const url = new URL("{{ route('assignments.user_hours') }}", window.location.origin);
    url.searchParams.append('user_id', userId);
    url.searchParams.append('role', role);
    url.searchParams.append('year', year);
    url.searchParams.append('month', month);

    const res = await fetch(url.href, {
      headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      credentials: 'same-origin'
    });

    if (!res.ok) return null;
    return await res.json();
  }

  // update UI for a single person (driver/guide)
  async function updatePersonInfo(selectEl, role) {
    const userId = selectEl.value;
    // determine month/year from scheduledStart (fallback to now)
    const date = parseDateLocal(scheduledStart.value) || new Date();
    const year = date.getFullYear();
    const month = date.getMonth() + 1; // JS month 0..11

    if (!userId) {
      if (role === 'driver') {
        driverInfo.classList.add('hidden');
        driverWarnClear();
      } else {
        guideInfo.classList.add('hidden');
        guideWarnClear();
      }
      updatePendingNoteVisibility();
      return;
    }

    const data = await fetchUserHours(userId, role, year, month);
    if (!data) {
      // error — show minimal
      if (role === 'driver') {
        driverInfoText.textContent = 'Gagal memuat data driver.';
        driverInfo.classList.remove('hidden');
      } else {
        guideInfoText.textContent = 'Gagal memuat data guide.';
        guideInfo.classList.remove('hidden');
      }
      updatePendingNoteVisibility();
      return;
    }

    const used = Number(data.used || 0);
    const limit = data.limit === null ? null : Number(data.limit);

    const est = Number(estimatedHours.value) || 0;
    const willTotal = used + est;

    const infoHtml = limit === null
      ? `Terpakai bulan ini: ${used} jam (tidak ada batas)`
      : `Terpakai bulan ini: ${used} jam — Batas: ${limit} jam — Jika ditambah: ${willTotal} jam`;

    if (role === 'driver') {
      driverInfoText.innerHTML = infoHtml;
      driverInfo.classList.remove('hidden');

      if (limit !== null && willTotal > limit) {
        driverWarning.textContent = 'Perhatian: penugasan ini akan melebihi batas jam bulanan driver dan akan disimpan sebagai PENDING.';
        driverWarning.classList.remove('hidden');
        submitBtn.disabled = false; // masih izinkan simpan; keputusan backend tetap
      } else {
        driverWarnClear();
      }
    } else {
      guideInfoText.innerHTML = infoHtml;
      guideInfo.classList.remove('hidden');

      if (limit !== null && willTotal > limit) {
        guideWarning.textContent = 'Perhatian: penugasan ini akan melebihi batas jam bulanan guide dan akan disimpan sebagai PENDING.';
        guideWarning.classList.remove('hidden');
        submitBtn.disabled = false;
      } else {
        guideWarnClear();
      }
    }

    updatePendingNoteVisibility();
  }

  function driverWarnClear() {
    driverWarning.classList.add('hidden');
    driverInfo.classList.remove('hidden');
  }
  function guideWarnClear() {
    guideWarning.classList.add('hidden');
    guideInfo.classList.remove('hidden');
  }

  function updatePendingNoteVisibility() {
    // show note if any warning visible or any selected user currently in_progress (we rely on backend for in_progress)
    const anyWarning = !driverWarning.classList.contains('hidden') || !guideWarning.classList.contains('hidden');
    if (anyWarning) {
      pendingNote.classList.remove('hidden');
    } else {
      pendingNote.classList.add('hidden');
    }
  }

  // events
  driverSelect.addEventListener('change', () => updatePersonInfo(driverSelect, 'driver'));
  guideSelect.addEventListener('change', () => updatePersonInfo(guideSelect, 'guide'));

  scheduledStart.addEventListener('change', () => {
    computeEstimatedFromSchedule();
    // update both persons because month may change
    updatePersonInfo(driverSelect, 'driver');
    updatePersonInfo(guideSelect, 'guide');
  });

  scheduledEnd.addEventListener('change', () => {
    computeEstimatedFromSchedule();
    updatePersonInfo(driverSelect, 'driver');
    updatePersonInfo(guideSelect, 'guide');
  });

  estimatedHours.addEventListener('input', () => {
    // when manual change estimated_hours, re-evaluate warnings
    updatePersonInfo(driverSelect, 'driver');
    updatePersonInfo(guideSelect, 'guide');
  });

  // initial load: if the form is prefilled (edit mode), update info
  if (driverSelect.value) updatePersonInfo(driverSelect, 'driver');
  if (guideSelect.value)  updatePersonInfo(guideSelect, 'guide');
});
</script>
