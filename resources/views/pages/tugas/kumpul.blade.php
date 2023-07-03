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
                <form action="{{ route('admin.tugas.simpan.kumpul', $tugas->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="">Nama Siswa</label>
                        <input type="text" name="" class="form-control @error('name_blog') is-invalid @enderror" value="{{ $siswa->name }}" readonly>
                        <input type="text" name="id_siswa" class="form-control @error('name_blog') is-invalid @enderror" value="{{ $siswa->id }}" hidden>
                        @error('name_blog')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Judul Tugas</label>
                        <input type="text" name="name_tugas" class="form-control @error('name_blog') is-invalid @enderror" value="{{ $tugas->judul_tugas }}" readonly>
                        <input type="text" name="id_tugas" class="form-control @error('name_blog') is-invalid @enderror" value="{{ $tugas->id}}" hidden>
                        @error('name_blog')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Kelas</label>
                        <select class="form-control" data-toggle="select" name="id_kelas" disabled>
                            @foreach ($kelas as $data)
                            <option value="{{ $data->id }}">{{ $data->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Mata Pelajaran</label>
                        <select class="form-control" data-toggle="select" name="id_mapel" disabled>
                            @foreach ($mapel as $data)
                            <option value="{{ $data->id }}">{{ $data->nama_mapel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Kumpulkan Tugas</label>
                        <input type="file" name="file" class="form-control @error('thumbnail_blog') is-invalid @enderror">
                        @error('thumbnail_blog')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-success">Simpan Materi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection