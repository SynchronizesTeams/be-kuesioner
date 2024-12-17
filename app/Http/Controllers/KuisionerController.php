<?php

namespace App\Http\Controllers;

use App\Models\Kuisioner;
use App\Models\UserIp;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class KuisionerController extends Controller
{
    public function create(Request $request)
    {
    // $userIp = $request->ip();

    // $userIpRecord = UserIp::where('user_ip', $userIp)->first();

    // if (!$userIpRecord) {
    //     UserIp::create(['user_ip' => $userIp]);
    // }
    
    $request->validate([
        'nama_wali_siswa' => 'required|string|max:255',
        'nama_siswa' => 'required|string|max:255',
        'kelas' => 'required|string|max:10',
        'tampilan_produk' => 'required|string|max:10',
        'tampilan_stand' => 'required|string|max:10',
        'penjelasan_produk' => 'required|string|max:10',
        'hiburan' => 'required|string|max:10',
        'kritik_saran' => 'required|string|max:1000',
    ]);

    // if ($userIpRecord && $userIpRecord->count >= 2) {
    //     return response()->json('Anda hanya dapat mengisi 2 kali', 403);
    // }

    $newKuisioner = null;

    // Mulai transaksi database
    DB::transaction(function () use ($request, &$newKuisioner) {
        // Simpan data kuisioner
        $newKuisioner = Kuisioner::create([
            'nama_wali_siswa' => $request->nama_wali_siswa,
            'nama_siswa' => $request->nama_siswa,
            'kelas' => $request->kelas,
            'tampilan_produk' => $request->tampilan_produk,
            'tampilan_stand' => $request->tampilan_stand,
            'penjelasan_produk' => $request->penjelasan_produk,
            'hiburan' => $request->hiburan,
            'kritik_saran' => $request->kritik_saran,
            // 'user_ip' => $userIp
        ]);

        // if ($userIpRecord) {
        //     $userIpRecord->increment('count');
        // }
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

}
