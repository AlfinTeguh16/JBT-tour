@extends('layouts.master')

@section('title', 'CV. Cipta Arya - Tambah Jurnal Umum')

@section('content')
<section class="p-10 bg-white shadow-lg border-gray-500 rounded-4xl w-full">
  <h1 class="font-bold text-3xl text-gray-800 mb-6">Tambah Jurnal Umum</h1>

  <form action="{{ route('jurnal-umum.store') }}" method="POST">
    @csrf

    <label for="transaksi">Pilih Jenis Transaksi:</label>
    <x-form type="select" id="transaksi" name="transaksi">
        <option value="kas">Transaksi Kas</option>
        <option value="modal-awal">Transaksi Modal Awal</option>
        <option value="sewa-dibayar-dimuka">Transaksi Sewa Dibayar Dimuka</option>
        <option value="peralatan-dan-perlengkapan">Transaksi Peralatan dan Perlengkapan</option>
        <option value="pendapatan">Transaksi Pendapatan</option>
    </x-form>


    <div class="grid md:grid-cols-2 gap-4 mb-6">
      <x-form name="tanggal" label="Tanggal" type="date" value="{{ old('tanggal') }}" required="true" />
      <x-form name="jumlah" label="Jumlah" type="text" class="only-number thousand-separator" placeholder="Contoh: 1500000" required="true" />
    </div>

    <div class="grid md:grid-cols-2 gap-4 mb-6">
        <x-form type="select" id="select-debet-kredit" label="Pilih Akun" name="select-debet-kredit">
            <option value="">Pilih Akun</option>
            <option value="kredit">Akun Kredit</option>
            <option value="debet">Akun Debet</option>
        </x-form>
         <div id="form-kredit" class="hidden">
            <x-form name="akun_kredit" label="Akun Kredit" type="text"
                value="{{ old('akun_kredit') }}" placeholder="Contoh: Pendapatan" />
        </div>

        <div id="form-debet" class="hidden">
            <x-form name="akun_debet" label="Akun Debet" type="text"
                value="{{ old('akun_debet') }}" placeholder="Contoh: Kas" />
        </div>
    </div>





    <x-form name="keterangan" label="Keterangan" type="textarea" value="{{ old('keterangan') }}" placeholder="Opsional" />

    <div class="flex justify-end mt-4">
      <x-button variant="neutral" onclick="window.history.back()" class="mr-2">Batal</x-button>
      <x-button variant="primary" type="submit">Simpan</x-button>
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
      // Jika tidak memilih apapun
      formDebet.classList.add('hidden');
      formKredit.classList.add('hidden');
    }
  });
</script>

@include('partials.js-format-number')



@endsection
