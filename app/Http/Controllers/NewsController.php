<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    // Get All Resource
    public function index()
    {
        $news = News::all();

        if ($news->isEmpty()) {
            return response()->json([
                'message' => "Data is empty",
                'kode status' => 404
            ], 404);
        } else {
            return response()->json([
                'message' => "Get All Resource",
                'data' => $news,
                'kode status' => 200
            ], 200);
        }
    }

    // Get Detail Resource
    public function show($id)
    {
        $news = News::find($id);
        if ($news) {
            return response()->json([
                'message' => "Get Defail Resource",
                'data' => $news,
                'kode status' => 200
            ], 200);
        } else {
            return response()->json([
                'message' => "Resource not found",
                'kode status' => 404
            ], 404);
        }
    }

    // search ss
    public function search(Request $request)
    {
        // Ambil parameter pencarian dari query string
        $query = $request->input('query');

        // Cari berita berdasarkan title, author, atau category
        $news = News::where('title', 'like', '%' . $query . '%')
            ->orWhere('author', 'like', '%' . $query . '%')
            ->orWhere('category', 'like', '%' . $query . '%')
            ->get();

        // Jika tidak ada data ditemukan
        if ($news->isEmpty()) {
            return response()->json([
                'message' => 'No news found',
            ], 404);
        }

        // Kembalikan respons JSON dengan data yang ditemukan
        return response()->json([
            'message' => 'News found successfully',
            'data' => $news,
        ], 200);
    }


    // Create Resource
    public function store(Request $request)
    {
        // Validasi data input
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'content' => 'required|string',
            'url' => 'required',
            'url_image' => 'required',
            'published_at' => 'required|date',
            'category' => 'required|string|max:255',
        ]);

        // Simpan data ke database
        $news = News::create($validatedData);

        // Kembalikan respons JSON
        return response()->json([
            'message' => 'News created successfully',
            'data' => $news,
        ], 201);
    }

    // update Resource
    public function update(Request $request, $id)
    {
        // Validasi data input
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'content' => 'required|string',
            'url' => 'required',
            'url_image' => 'required',
            'published_at' => 'required|date',
            'category' => 'required|string|max:255',
        ]);

        // Mencari berita berdasarkan ID
        $news = News::find($id);

        // Jika berita tidak ditemukan, kembalikan response 404
        if (!$news) {
            return response()->json([
                'message' => 'Resorce Not Found',
                'kode status' => 404
            ], 404);
        }

        // Perbarui data berita
        $news->update($validatedData);

        // Kembalikan respons JSON
        return response()->json([
            'message' => 'Resource is update successfully',
            'data' => $news,
            'kode status' => 200
        ], 200);
    }


    // Delete Resource
    public function destroy($id)
    {
        // Cari data berita berdasarkan ID
        $news = News::find($id);

        // Jika data tidak ditemukan, kembalikan respons 404
        if (!$news) {
            return response()->json([
                'message' => 'Resource not found',
                'kode status' => 404
            ], 404);
        }

        // Hapus data berita
        $news->delete();

        // Kembalikan respons sukses
        return response()->json([
            'message' => 'Resource is deleted successfully',
            'kode status' => 200
        ], 200);
    }
}
