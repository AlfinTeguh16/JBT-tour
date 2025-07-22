@extends('layouts.master')

@section('title', 'CV. Cipta Arya - Edit Jurnal Umum')

@section('content')
<section class="p-10 bg-white shadow-lg border-gray-500 rounded-4xl w-full">
    <h1 class="font-bold text-3xl text-gray-800 mb-6">Edit Jurnal Umum</h1>

  <form action="{{ route('jurnal-umum.update', $jurnal->id) }}" method="POST">
    @csrf
    @method('PUT')

    <label for="transaksi">Pilih Jenis Transaksi:</label>
    <x-form type="select" id="transaksi" name="transaksi">
        <option value="kas" {{ old('transaksi', $jurnal->transaksi) == 'kas' ? 'selected' : '' }}>Transaksi Kas</option>
        <option value="modal-awal" {{ old('transaksi', $jurnal->transaksi) == 'modal-awal' ? 'selected' : '' }}>Transaksi Modal Awal</option>
        <option value="sewa-dibayar-dimuka" {{ old('transaksi', $jurnal->transaksi) == 'sewa-dibayar-dimuka' ? 'selected' : '' }}>Transaksi Sewa Dibayar Dimuka</option>
        <option value="peralatan-dan-perlengkapan" {{ old('transaksi', $jurnal->transaksi) == 'peralatan-dan-perlengkapan' ? 'selected' : '' }}>Transaksi Peralatan dan Perlengkapan</option>
        <option value="pendapatan" {{ old('transaksi', $jurnal->transaksi) == 'pendapatan' ? 'selected' : '' }}>Transaksi Pendapatan</option>
    </x-form>

    <div class="grid md:grid-cols-2 gap-4 mb-6">
      <x-form name="tanggal" label="Tanggal" type="date" value="{{ old('tanggal', $jurnal->tanggal->format('Y-m-d')) }}" required="true" />
      <x-form name="jumlah" label="Jumlah" type="text" class="only-number thousand-separator" value="{{ old('jumlah', number_format($jurnal->jumlah, 0, ',', '.')) }}" required="true" />
    </div>

    <div class="grid md:grid-cols-2 gap-4 mb-6">
      <x-form type="select" id="select-debet-kredit" label="Pilih Akun" name="select-debet-kredit">
          <option value="">Pilih Akun</option>
          <option value="kredit" {{ old('select-debet-kredit', $jurnal->tipe_akun) == 'kredit' ? 'selected' : '' }}>Akun Kredit</option>
          <option value="debet" {{ old('select-debet-kredit', $jurnal->tipe_akun) == 'debet' ? 'selected' : '' }}>Akun Debet</option>
      </x-form>

      <div id="form-kredit" class="{{ old('select-debet-kredit', $jurnal->tipe_akun) == 'kredit' ? '' : 'hidden' }}">
          <x-form name="akun_kredit" label="Akun Kredit" type="text"
              value="{{ old('akun_kredit', $jurnal->akun_kredit) }}" placeholder="Contoh: Pendapatan"/>
      </div>

      <div id="form-debet" class="{{ old('select-debet-kredit', $jurnal->tipe_akun) == 'debet' ? '' : 'hidden' }}">
          <x-form name="akun_debet" label="Akun Debet" type="text"
              value="{{ old('akun_debet', $jurnal->akun_debet) }}" placeholder="Contoh: Kas"/>
      </div>
    </div>

    <x-form name="keterangan" label="Keterangan" type="textarea" value="{{ old('keterangan', $jurnal->keterangan) }}" placeholder="Opsional" />

    <div class="flex justify-end mt-4">
      <x-button variant="back" href="{{ route('jurnal-umum.index') }}" class="mx-3">Batal</x-button>
      <x-button variant="primary" type="submit">Update</x-button>
    </div>
  </form>
</section>

<script>
  document.getElementById('select-debet-kredit').addEventListener('change', function () {
    const selected = this.value;
    const formDebet = document.getElementById('form-debet');
    const formKredit = document.getElementById('form-kredit');

    if (selected === 'debet') {
      formDebet.classList.remove('hidden');
      formKredit.classList.add('hidden');
    } else if (selected === 'kredit') {
      formKredit.classList.remove('hidden');
      formDebet.classList.add('hidden');
    } else {
      formDebet.classList.add('hidden');
      formKredit.classList.add('hidden');
    }
  });
</script>

@include('partials.js-format-number')
@endsection
