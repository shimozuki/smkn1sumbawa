<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materi;
use App\Models\Video;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Kelas;
use App\Models\Ujian;
use App\Models\MataPelajaran;
use Illuminate\Support\Facades\DB;



class MateriCOntroller extends Controller
{
    public function index()
    {
        $cek_user = DB::table('gurus')->join('users', 'gurus.id_user', '=', 'users.id')->where('gurus.id_user', auth()->user()->id)->first();
        if (!empty($cek_user)) {
            $data = DB::table('video')->join('kelas', 'video.kelas_id', '=', 'kelas.id')->join('mata_pelajarans', 'video.id_mapel', '=', 'mata_pelajarans.id')->join('gurus', 'kelas.id_guru', '=', 'gurus.id')->where('gurus.id_user', auth()->user()->id)->select('video.id', 'video.name_video', 'kelas.nama_kelas', 'mata_pelajarans.nama_mapel')->get();
        }else{
            $data = DB::table('video')->join('kelas', 'video.kelas_id', '=', 'kelas.id')->join('mata_pelajarans', 'video.id_mapel', '=', 'mata_pelajarans.id')->join('siswas', 'kelas.id', '=', 'siswas.id_kelas')->where('siswas.id_user', auth()->user()->id)->select('video.id', 'video.name_video', 'kelas.nama_kelas', 'mata_pelajarans.nama_mapel')->get();
        }
       
        // echo $data;
        return view('pages.materi.index', compact('data'));
    }

    public function tambah()
    {
       
        $data = [
            'title' => 'Tambah Materi Praktikum',
        ];
        return view('pages.materi.tambahvideo', $data);
    }

    public function simpan(Request $request)
    {

        $validator = Validator($request->all(), [
            'name_kelas' => 'required',
            'type_kelas' => 'required',
            'description_kelas' => 'required',
            'thumbnail' => 'required|mimes:png,jpg,jpeg'
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.kelas.tambah')->withErrors($validator)->withInput();
        } else {
            $file = $request->file('thumbnail')->store('thumbnail_kelas', 'public');
            $obj = [
                'name_kelas' => $request->name_kelas,
                'type_kelas' => 0,
                'description_kelas' => $request->description_kelas,
                'thumbnail' => $file,
            ];
            Materi::insert($obj);
            return redirect()->route('admin.kelas')->with('status', 'Berhasil Menambah Kelas Baru');
        }
    }

    public function detail($id)
    {
        $video = DB::table('video')->join('kelas', 'video.kelas_id', '=', 'kelas.id')->where('video.id', $id)->select('video.name_video', 'video.url_video', 'video.id')->first();
        // echo $id;
        // dd($video);
        return view('pages.materi.detail', compact('video'));
    }

    public function hapus($id)
    {
        $dec_id = Crypt::decrypt($id);
        $kelas = Materi::find($dec_id);
        Storage::delete('public/'.$kelas->thumbnail);
        Video::where('kelas_id', '=', $dec_id)->delete();
        $kelas->delete();
        return redirect()->route('admin.kelas')->with('status', 'Berhasil Menghapus Kelas');
    }

    public function edit($id)
    {
        // $dec_id = Crypt::decrypt($id);
        $title = 'Edit Materi Praktikum';
        $video = DB::table('video')->join('kelas', 'video.kelas_id', '=', 'kelas.id')->where('video.id', $id)->select('video.name_video', 'video.url_video', 'video.id AS video_id', 'kelas.id AS kelas_id', 'video.id_mapel')->first();
        $kelasV = Kelas::all();
        $mapel = MataPelajaran::all();
        return view('pages.materi.edit', compact('title', 'video', 'kelasV', 'mapel'));
    }

    public function update(Request $request, $id)
    {
        // $dec_id = Crypt::decrypt($id);
        $validator = Validator($request->all(), [
            'name_video' => 'required',
            'url_video' => 'required',
            'kelas_id' => 'required',
            'id_mapel' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.kelas.edit', $id)->withErrors($validator)->withInput();
        } else {
            DB::table('video')
            ->where('id', $id)
            ->update([
                'name_video' => $request->input('name_video'),
                'url_video' => $request->input('url_video'),
                'kelas_id' => $request->input('kelas_id'),
                'id_mapel' => $request->input('id_mapel')
            ]);
            return redirect()->route('admin.kelas.detail',$id)->with('status', 'Berhasil Memperbarui Kelas');
        }
    }

    public function tambahvideo()
    {
        $kelas = Kelas::all();
        $mapel = MataPelajaran::all();

        return view('pages.materi.tambahvideo', compact('kelas','mapel'));
    }

    public function simpanvideo(Request $request)
    {

        $validator = Validator($request->all(), [
            'name_video' => 'required',
            'url_video' => 'required',
            'kelas_id' => 'required',
            'id_mapel' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.kelas.tambahvideo')->withErrors($validator)->withInput();
        } else {
            $obj = [
                'name_video' => $request->name_video,
                'kelas_id' => $request->kelas_id,
                'url_video' => $request->url_video,
                'id_mapel' => $request->id_mapel,
            ];
            // Video::insert($obj);
            DB::table('video')->insert($obj);
            return redirect()->route('admin.kelas')->with('status', 'Berhasil Menambah Materi Video');
        }
    }

    public function hapusvideo($id)
    {
        $dec_id = Crypt::decrypt($id);
        Video::where('id','=',$dec_id)->delete();
        return redirect()->route('admin.kelas')->with('status', 'Berhasil Menghapus Video Materi');
    }
}
