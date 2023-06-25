@extends('layout.app')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>{{ $tugas->judul_tugas }}</h4>
                @if (auth()->user()->level == 'admin' || auth()->user()->level == 'guru')
                <div class="card-header-action">
                    <a href="{{ route('admin.tugas.edit', $tugas->id) }}"
                        class="btn btn-warning">Edit</a>
                        <a href="{{ route('admin.tugas.hapus', Crypt::encrypt($tugas->id)) }}" class="btn btn-danger" onclick="confirmDelete()">Hapus</a>

                        <a href="/admin/tugas" class="btn btn-md btn-primary">Kembali</a>
                </div>
                @endif
                @if (auth()->user()->level == 'siswa' && $muncul == null)
                <div class="card-header-action">
                    <a href="{{ route('admin.tugas.kumpul', $tugas->id) }}"
                        class="btn btn-warning">Kumpul Tugas</a>
                        <a href="/admin/tugas" class="btn btn-md btn-primary">Kembali</a>
                </div>
                @endif
               
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table>
                            <tr>
                                <td style="vertical-align: top">Materi</td>
                                <td style="vertical-align: top" class="py-2 px-3">:</td>
                                <td>{!! $tugas->soal_tugas !!}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function confirmDelete() {
    if (confirm("Apakah Anda yakin ingin menghapus?")) {
        // Kode yang akan dieksekusi jika pengguna menekan tombol OK
        // Misalnya, Anda dapat memanggil fungsi untuk menghapus data
        // atau mengirimkan permintaan AJAX ke server.
        alert("Data berhasil dihapus!");
    } else {
        // Kode yang akan dieksekusi jika pengguna menekan tombol Batal
        alert("Penghapusan dibatalkan.");
    }
}
</script>
@endsection