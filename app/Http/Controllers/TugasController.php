<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class TugasController extends Controller
{
    public function index()
    {
        $cek_user = DB::table('gurus')->join('users', 'gurus.id_user', '=', 'users.id')->where('gurus.id_user', auth()->user()->id)->first();
        if (!empty($cek_user)) {
            $data = DB::table('tugas')->join('kelas', 'tugas.id_kelas', '=', 'kelas.id')->join('mata_pelajarans', 'tugas.id_mapel', '=', 'mata_pelajarans.id')->join('gurus', 'kelas.id_guru', '=', 'gurus.id')->where('gurus.id_user', auth()->user()->id)->select('tugas.id', 'tugas.judul_tugas', 'kelas.nama_kelas', 'mata_pelajarans.nama_mapel')->get();
        } else {
            $data = DB::table('tugas')->join('kelas', 'tugas.id_kelas', '=', 'kelas.id')->join('mata_pelajarans', 'tugas.id_mapel', '=', 'mata_pelajarans.id')->join('siswas', 'kelas.id', '=', 'siswas.id_kelas')->where('siswas.id_user', auth()->user()->id)->select('tugas.id', 'tugas.judul_tugas', 'kelas.nama_kelas', 'mata_pelajarans.nama_mapel')->get();
        }

        return view('pages.tugas.index', compact('data'));
    }

    public function hal()
    {
        $tugas =  DB::table('pengumpulann')
            ->join('siswas', 'pengumpulann.id_siswa', '=', 'siswas.id_user')
            ->join('tugas', 'pengumpulann.id_tugas', '=', 'tugas.id')
            ->join('users', 'pengumpulann.id_siswa', '=', 'users.id')
            ->join('mata_pelajarans', 'tugas.id_mapel', '=', 'mata_pelajarans.id')
            ->join('kelas', 'tugas.id_kelas', '=', 'kelas.id')
            ->join('gurus', 'kelas.id_guru', '=', 'gurus.id')
            ->where('gurus.id_user', auth()->user()->id)
            ->get();

        return view('pages.tugas.pengumpulan', compact('tugas'));
    }

    public function downloadFile($filename)
    {
        $filePath = public_path('storage/' . $filename);

        return response()->download($filePath);
    }

    public function detail($id)
    {
        $tugas = DB::table('tugas')->join('kelas', 'tugas.id_kelas', '=', 'kelas.id')->where('tugas.id', $id)->select('tugas.judul_tugas', 'tugas.soal_tugas', 'tugas.id')->first();
        $cektugas = DB::table('pengumpulann')->select('id_tugas')->where('id_siswa', auth()->user()->id)->first();
        if (!empty($cektugas)) {
            $muncul = "muncul";
        } else {
            $muncul = "";
        }
        return view('pages.tugas.detail', compact('tugas', 'muncul'));
    }

    public function tambah()
    {
        $kelas = Kelas::all();
        $mapel = MataPelajaran::all();
        return view('pages.tugas.tambah', compact('kelas', 'mapel'));
    }

    public function simpan(Request $request)
    {

        $validator = Validator($request->all(), [
            'judul_tugas' => 'required',
            'id_kelas' => 'required',
            'id_mapel' => 'required',
            'soal_tugas' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.tugas.tambah')->withErrors($validator)->withInput();
        } else {
            DB::table('tugas')->insert([
                'judul_tugas' => $request->input('judul_tugas'),
                'id_kelas' => $request->input('id_kelas'),
                'id_mapel' => $request->input('id_mapel'),
                'soal_tugas' => $request->input('soal_tugas')
            ]);
            return redirect()->route('admin.tugas')->with('status', 'Berhasil Menambah Blog Baru');
        }
    }

    public function hapus($id)
    {

        $dec_id = Crypt::decrypt($id);
        DB::table('tugas')->where('id', $dec_id)->delete();
        return redirect()->route('admin.tugas')->with('status', 'Berhasil Menghapus Blog');
    }

    public function edit($id)
    {
        $title = 'Edit Materi Praktikum';
        $tugas = DB::table('tugas')->where('id', $id)->first();
        $kelasV = Kelas::all();
        $mapel = MataPelajaran::all();


        return view('pages.tugas.edit', compact('title', 'tugas', 'kelasV', 'mapel'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator($request->all(), [
            'judul_tugas' => 'required',
            'soal_tugas' => 'required',
            'id_kelas' => 'required',
            'id_mapel' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.tugas.edit')->withErrors($validator)->withInput();
        } else {
            DB::table('tugas')
                ->where('id', $id)
                ->update([
                    'judul_tugas' => $request->input('judul_tugas'),
                    'soal_tugas' => $request->input('soal_tugas'),
                    'id_kelas' => $request->input('id_kelas'),
                    'id_mapel' => $request->input('id_mapel'),
                ]);
            return redirect()->route('admin.tugas.detail', $id)->with('status', 'Berhasil Memperbarui');
        }
    }

    public function kumpul($id)
    {
        $title = 'Pengunpulan Tugas';
        $tugas = DB::table('tugas')->where('id', $id)->first();
        $siswa = DB::table('users')->select('name', 'id')->where('id', auth()->user()->id)->first();
        $kelas = Kelas::all();
        $mapel = MataPelajaran::all();

        return view('pages.tugas.kumpul', compact('siswa', 'title', 'tugas', 'kelas', 'mapel'));
    }

    public function simpantugas(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_siswa' => 'required',
            'id_tugas' => 'required',
            'file' => 'required|mimes:pdf,docx,doc',
        ]);

        if ($validator->fails()) {
            DB::table('pengumpulann')->insert([
                'id_siswa' => $request->input('id_siswa'),
                'id_tugas' => $id,
                'file' => $request->file('file')->store('thumbnail_tugas', 'public'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return redirect()->route('admin.tugas.kumpul', $id)->withErrors($validator)->withInput();
        }

        DB::table('pengumpulann')->insert([
            'id_siswa' => $request->input('id_siswa'),
            'id_tugas' => $id,
            'file' => $request->file('file')->store('thumbnail_tugas', 'public'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.tugas')->with('status', 'Berhasil Mengumpulkan');
    }
}
