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
                        <form action="{{ route('admin.tugas.hapus', Crypt::encrypt($tugas->id)) }}" method="POST" class="d-inline swal-confirm">
                          @csrf
                          @method('DELETE')
                          <button class="btn btn-danger swal-confirm" type="submit" data-id="{{ $blog->id }}">
                             Hapus
                          </button>
                      </form>
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
  $(document).ready(function () {
      $('.swal-confirm').click(function(event) {
          var form =  $(this).closest("form");
          var id = $(this).data("id");
          event.preventDefault();
          swal({
              title: `Yakin Hapus Data?`,
              text: "Data yang terhapus tidak dapat dikembalikan",
              icon: "warning",
              buttons: true,
              dangerMode: true,
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Ya, hapus',
          })
          .then((willDelete) => {
              if (willDelete) {
              form.submit();
              }
          });
      });
  });
</script>
@endsection