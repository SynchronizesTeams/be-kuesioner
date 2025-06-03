<?php

namespace App\Http\Controllers;

use App\Models\Kuisioner;
use App\Models\User;
use App\Models\UserIp;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class KuisionerController extends Controller
{
    public function create(Request $request)
    {
    $user = auth()->user();
    if ($user->is_ngisi == true) { 
        return response()->json([
            'success' => false,
            'message' => 'Anda sudah mengisi kuisioner'
        ]);
    }

    $request->validate([
        'nama_wali_siswa' => 'required|string|max:255',
        'tampilan_produk' => 'required|string|max:10',
        'tampilan_stand' => 'required|string|max:10',
        'penjelasan_produk' => 'required|string|max:10',
        'hiburan' => 'required|string|max:10',
        'kritik_saran' => 'required|string|max:1000',
    ]);

    $newKuisioner = null;

    // Mulai transaksi database
    DB::transaction(function () use ($request, $user, &$newKuisioner) {
        // Simpan data kuisioner
        $newKuisioner = Kuisioner::create([
            'siswa_id' => $user->user_id,
            'nama_wali_siswa' => $request->nama_wali_siswa,
            'nama_siswa' => $user->name,
            'kelas' => $user->kelas,
            'tampilan_produk' => $request->tampilan_produk,
            'tampilan_stand' => $request->tampilan_stand,
            'penjelasan_produk' => $request->penjelasan_produk,
            'hiburan' => $request->hiburan,
            'kritik_saran' => $request->kritik_saran,
        ]);

        $user->is_ngisi = true;
        $user->save();

    });

    return response()->json([
            'message' => 'Kuisioner berhasil dikirim',
            'data' => $newKuisioner
        ], 201);
    }

    public function show(Request $request)
    {
        $data = Kuisioner::orderBy('id', 'asc')->paginate(10);

        return response()->json([
            'data' => $data->items(), // Menampilkan data pada halaman saat ini
            'current_page' => $data->currentPage(), // Halaman yang sedang aktif
            'total_pages' => $data->lastPage(), // Total halaman
            'total_items' => $data->total(), // Jumlah total item
        ]);
    }

    public function getAntrian() {
        $user = auth()->user();

        if ($user->is_ngisi == false) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum mengisi kuisioner'
            ]);
        }

        $kelas = $user->kelas;
        $count = Kuisioner::where('kelas', '=', $kelas)->count();

        if ($count > 40) {
            return response()->json([
                'message' => 'Antrian sudah penuh'
            ], 400);
        }

        $no_antrian = $count + 1;

        $user = User::where('user_id', '=', $user->user_id)->first();

        $user->update([
            'no_antrian' => $no_antrian
        ]);

        return response()->json([
            'message' => 'Antrian berhasil diambil',
            'no_antrian' => $no_antrian
        ]);
    }

    public function seeAntrian($user_id) {
        $user = User::where('user_id', '=', $user_id)->first();
        return response()->json([
            'success' => true,
            'no_antrian' => $user->no_antrian
        ]);
    }

    public function seeAntrianKelas($kelas) {
        $user = User::where('kelas', '=', $kelas)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan'
            ]);
        }
        
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }
}
