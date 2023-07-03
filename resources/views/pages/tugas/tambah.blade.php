@extends('layout.app')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Tambah Materi</h4>
                <div class="card-header-action">
                    <a href="/admin/tugas" class="btn btn-md btn-primary">Kembali</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.tugas.simpan') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="">Judul Tugas</label>
                        <input type="text" name="judul_tugas" class="form-control @error('name_blog') is-invalid @enderror" value="{{ old('name_blog') }}">
                        @error('name_blog')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Kelas</label>
                        <select class="form-control" data-toggle="select" name="id_kelas" required>
                            <option selected hidden>Pilih Kelas</option>
                            @foreach ($kelas as $data)
                            <option value="{{ $data->id }}">{{ $data->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Mata Pelajaran</label>
                        <select class="form-control" data-toggle="select" name="id_mapel" required>
                            <option selected hidden>Pilih Mapel</option>
                            @foreach ($mapel as $data)
                            <option value="{{ $data->id }}">{{ $data->nama_mapel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Materi</label>
                        <textarea name="soal_tugas" class="ckeditor @error('content_blog') is-invalid @enderror" id="ckeditor">
                        {{ old('content_blog') }}
                        </textarea>
                        @error('content_blog')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-success">Simpan Tugas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection