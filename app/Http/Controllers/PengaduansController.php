<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PengaduansController extends Controller
{

    public function createPengaduan(Request $request)
    {
        // Verifikasi token
        $user = User::where('token', $request->bearerToken())->first();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Validasi input
        $request->validate([
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Aturan validasi untuk file gambar
            'jenis_pengaduan' => 'required',
        ]);

        // Simpan file gambar ke dalam direktori 'assets/laporan'
        $imageName = $request->file('image')->store('assets/laporan', 'public');

        // Buat data baru dalam tabel pengaduans
        $pengaduan = new Pengaduan();
        $pengaduan->name = $user->name;
        $pengaduan->description = $request->description;
        $pengaduan->image = $imageName;
        $pengaduan->jenis_pengaduan = $request->jenis_pengaduan;
        $pengaduan->user_nik = $user->nik;
        $pengaduan->user_id = $user->id;
        $pengaduan->status = 'belum diproses'; // Tambahkan status default
        $pengaduan->save();

        return response()->json(['message' => 'Pengaduan created successfully', 'data' => $pengaduan], 201);
    }

    public function getAllPengaduan()
    {
        // Mendapatkan semua data dari tabel pengaduans
        $pengaduans = Pengaduan::all();

        // Jika tidak ada data ditemukan, kirimkan response error
        if ($pengaduans->isEmpty()) {
            return response()->json(['error' => 'No data found'], 404);
        }

        return response()->json($pengaduans, 200);
    }

    public function getPengaduanById($id)
    {
        // Temukan pengaduan berdasarkan ID
        $pengaduan = Pengaduan::find($id);

        // Periksa apakah pengaduan ditemukan
        if (!$pengaduan) {
            return response()->json(['message' => 'Pengaduan not found'], 404);
        }

        // Jika ditemukan, kembalikan pengaduan bersama dengan informasi lainnya dalam respons JSON
        return response()->json([
            'id' => $pengaduan->id,
            'name' => $pengaduan->name,
            'user_nik' => $pengaduan->user_nik,
            'description' => $pengaduan->description,
            'image' => $pengaduan->image,
            'status' => $pengaduan->status,
            'rating' => $pengaduan->rating,
        ], 200);
    }
    public function addRating(Request $request,$id)
    {
        // Temukan pengaduan berdasarkan ID
        $pengaduan = Pengaduan::find($id);

        // Periksa apakah pengaduan ditemukan
        if (!$pengaduan) {
            return response()->json(['message' => 'Pengaduan not found'], 404);
        }

        $rating = $request->input('rating');
        // Update rating pengaduan
        $pengaduan->rating = $rating;

        // Simpan perubahan ke database
        $pengaduan->save();

        return response()->json(['message' => 'Rating added successfully', 'data' => $pengaduan], 200);
    }
}

