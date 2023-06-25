@extends('layout.app')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Data Tugas</h4>
                
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-items-center table-hover" id="table">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Judul Tugas</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Mata Pelajaran</th>
                                <th>File Tugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tugas as $item)
                            <tr>
                                <td></td>
                                <td>{{ $item->judul_tugas }}</td>
                                <td>{{$item->name}} ({{$item->nis}})</td>
                                <td>{{$item->nama_kelas}}</td>
                                <td>{{$item->nama_mapel}}</td>
                                <td>
                                <a href="{{ route('download.file', $item->file) }}" class="btn btn-warning">Download File</a>
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