@extends('layout.app')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>{{ $title }}</h4>
                <div class="card-header-action">
                <a href="/materi" class="btn btn-md btn-primary">Kembali</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.kelas.update', $video->video_id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="">Judul Materi</label>
                        <input type="text" name="name_video" class="form-control @error('name_kelas') is-invalid @enderror" value="{{ $video->name_video}}">
                        @error('name_kelas')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Kelas</label>
                        <select class="form-control" data-toggle="select" name="kelas_id" required>
                            <option selected hidden>Pilih Kelas</option>
                            @foreach ($kelasV as $data)
                            <option value="{{ $data->id }}" {{ $video->kelas_id == $data->id ? 'selected' : '' }}>{{ $data->nama_kelas}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Mata Pelajaran</label>
                        <select class="form-control" data-toggle="select" name="id_mapel" required>
                            <option selected hidden>Pilih Mapel</option>
                            @foreach ($mapel as $data)
                            <option value="{{ $data->id }}" {{ $video->id_mapel == $data->id ? 'selected' : '' }}>{{ $data->nama_mapel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Embed Video</label>
                        <input type="text" name="url_video" class="form-control @error('name_kelas') is-invalid @enderror" value="{{ $video->url_video}}">
                        @error('name_kelas')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-success">Perbaharui Materi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection