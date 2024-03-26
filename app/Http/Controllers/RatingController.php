<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function submitRating(Request $request)
    {
        // Validasi request jika diperlukan
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'pengaduan_id' => 'required|exists:pengaduans,id'
        ]);

        // Ambil data rating dari form
        $rating = $request->input('rating');
        $pengaduanId = $request->input('pengaduan_id');

        // Temukan pengaduan yang sesuai
        $pengaduan = Pengaduan::find($pengaduanId);

        // Periksa apakah pengaduan ditemukan
        if ($pengaduan) {
            // Update rating pengaduan
            $pengaduan->rating = $rating;
            $pengaduan->save();

            // Redirect atau tampilkan pesan sukses
            return redirect()->back()->with('success', 'Rating berhasil disimpan.');
        } else {
            // Jika pengaduan tidak ditemukan, kembalikan dengan pesan error
            return redirect()->back()->with('error', 'Pengaduan tidak ditemukan.');
        }
    }
}
