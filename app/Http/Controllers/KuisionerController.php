<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use App\Models\KuesionerTamu;
use App\Models\Kuisioner;
use App\Models\User;
use App\Models\UserIp;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class KuisionerController extends Controller
{
    public function create(Request $request)
{
    $user = auth()->user();

    // Pastikan user belum mengisi kuisioner
    if ($user->is_ngisi) {
        return response()->json([
            'success' => false,
            'message' => 'Anda sudah mengisi kuisioner'
        ]);
    }

    $request->validate([
        'nama_wali_siswa'    => 'required|string|max:255',
        'tampilan_produk'    => 'required|string|max:10',
        'tampilan_stand'     => 'required|string|max:10',
        'penjelasan_produk'  => 'required|string|max:10',
        'hiburan'            => 'required|string|max:10',
        'kritik_saran'       => 'required|string|max:1000',
    ]);

    DB::beginTransaction();

    try {
        // --- Perhitungan nomor antrian per kelas ---
        // Kunci semua baris antrian untuk kelas user saat ini agar tidak ada race condition
        $lastAntrian = Antrian::where('kelas', $user->kelas)
                         ->lockForUpdate() // mengunci hasil SELECT untuk kelas tersebut
                         ->orderBy('no_antrian', 'desc')
                         ->first();

        // Nomor antrian berikutnya: jika belum ada, mulai dari 1
        $nextNoAntrian = $lastAntrian ? $lastAntrian->no_antrian + 1 : 1;

        // Aturan: maksimum 40 antrian per kelas
        if ($nextNoAntrian > 40) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Antrian sudah penuh'
            ], 400);
        }

        // --- Simpan data kuisioner ---
        $newKuisioner = Kuisioner::create([
            'siswa_id'          => $user->user_id,
            'nama_wali_siswa'   => $request->nama_wali_siswa,
            'nama_siswa'        => $user->name,
            'kelas'             => $user->kelas,
            'tampilan_produk'   => $request->tampilan_produk,
            'tampilan_stand'    => $request->tampilan_stand,
            'penjelasan_produk' => $request->penjelasan_produk,
            'hiburan'           => $request->hiburan,
            'kritik_saran'      => $request->kritik_saran,
        ]);

        // --- Buat entri antrian untuk kelas ini ---
        $antrian = Antrian::create([
            'user_id'    => $user->user_id,
            'kelas'      => $user->kelas,
            'no_antrian' => $nextNoAntrian,
        ]);

        // Tandai user sudah mengisi kuisioner dan simpan nomor antrian dari hasil perhitungan
        $user->update([
            'is_ngisi'   => true,
            'no_antrian' => $nextNoAntrian
        ]);

        DB::commit();

        return response()->json([
            'success'     => true,
            'message'     => 'Kuisioner berhasil dikirim',
            'data'        => $newKuisioner,
            'no_antrian'  => $nextNoAntrian
        ], 201);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Gagal menyimpan kuisioner',
            'error'   => $e->getMessage()
        ], 500);
    }
}


    public function getAntrian()
    {
    $user = auth()->user();

    if (!$user->is_ngisi) {
        return response()->json([
            'success' => false,
            'message' => 'Anda belum mengisi kuisioner'
        ]);
    }

    // Pastikan proses atomic
    DB::beginTransaction();

    try {
        // Lock semua row kuisioner dari kelas yang sama
        $kelas = $user->kelas;

        // Lock baris yang relevan untuk menghindari race condition
        $no_antrian = Kuisioner::where('kelas', $kelas)
            ->lockForUpdate() // ini penting
            ->count();

        if ($no_antrian >= 40) {
            DB::rollBack();
            return response()->json([
                'message' => 'Antrian sudah penuh'
            ], 400);
        }

        $user = User::where('user_id', $user->user_id)->lockForUpdate()->first();

        $user->no_antrian = $no_antrian;
        $user->save();

        DB::commit();

        return response()->json([
            'message' => 'Antrian berhasil diambil',
            'no_antrian' => $user->no_antrian
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Terjadi kesalahan saat mengambil antrian',
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function seeAntrian($user_id) {
        $user = User::where('user_id', '=', $user_id)->first();
        return response()->json([
            'success' => true,
            'no_antrian' => $user->no_antrian
        ]);
    }

    public function seeAntrianKelas($kelas) {
        $user = User::where('kelas', '=', $kelas)->where('no_antrian', '>', 0)->get();
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

    public function createTamu(Request $request)
    {
    $request->validate([
        'nama' => 'required|string|max:255',
        'instansi' => 'required|string|max:255',
        'tampilan_produk' => 'required|string|max:10',
        'tampilan_stand' => 'required|string|max:10',
        'penjelasan_produk' => 'required|string|max:10',
        'hiburan' => 'required|string|max:10',
        'kritik_saran' => 'required|string|max:1000',
    ]);

    $newKuisioner = null;

    // Mulai transaksi database
    DB::transaction(function () use ($request, &$newKuisioner) {
        // Simpan data kuisioner
        $newKuisioner = KuesionerTamu::create([
            'nama' => $request->nama,
            'instansi' => $request->instansi,
            'tampilan_produk' => $request->tampilan_produk,
            'tampilan_stand' => $request->tampilan_stand,
            'penjelasan_produk' => $request->penjelasan_produk,
            'hiburan' => $request->hiburan,
            'kritik_saran' => $request->kritik_saran,
        ]);


    });

    return response()->json([
            'message' => 'Kuisioner berhasil dikirim',
            'data' => $newKuisioner
        ], 201);
    }

    public function showTamu() {
        $data = Cache::remember('kuesioner_tamu', 600, function () {
            return KuesionerTamu::orderBy('id', 'asc')->get();
        });

        return response()->json([
            'success' => true,
            'data' => $data, // Menampilkan data pada halaman saat ini
        ]);
    }

    public function show() {
        $data = Kuisioner::get();
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
