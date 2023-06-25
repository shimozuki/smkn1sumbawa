@extends('layout.app')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Data Tugas</h4>
                @if (auth()->user()->level == 'admin' || auth()->user()->level == 'guru')
                <div class="card-header-action">
                    <a href="{{ route('admin.tugas.tambah') }}" class="btn btn-primary">
                        Tambah
                    </a>
                </div>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-items-center table-hover" id="table">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Kelas</th>
                                <th>Mata Pelajaran</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                            <tr>
                                <td></td>
                                <td>{{ $item->judul_tugas }}</td>
                                <td>{{$item->nama_kelas}}</td>
                                <td>{{$item->nama_mapel}}</td>
                                <td>
                                    <a href="{{ route('admin.tugas.detail', $item->id) }}"
                                        class="btn btn-warning">Detail</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection