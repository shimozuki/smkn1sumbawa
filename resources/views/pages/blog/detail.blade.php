@extends('layout.app')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>{{ $blog->name_blog }}</h4>
                @if (auth()->user()->level == 'admin' || auth()->user()->level == 'guru')
                <div class="card-header-action">
                    <a href="{{ route('admin.blog.edit',Crypt::encrypt($blog->id)) }}"
                        class="btn btn-warning">Edit</a>
                        <a href="{{ route('admin.blog.hapus', Crypt::encrypt($blog->id)) }}" class="btn btn-danger" onclick="confirmDelete()">Hapus</a>

                        <a href="/admin/blog" class="btn btn-md btn-primary">Kembali</a>
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
                                <td>{!! $blog->content_blog !!}</td>
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
            // Code to execute if the user clicks "OK"
            // For example, you can call a function to delete the data
            // or send an AJAX request to the server.
            alert("Data berhasil dihapus!");
            // Add the code to delete the data or perform any other action here
        } else {
            window.location.href = event.target.href;
        }
    }
</script>
@endsection