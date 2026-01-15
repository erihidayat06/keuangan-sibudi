<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\SubKategori;

class AdminTemplateController extends Controller
{
    public function index()
    {
        // Semua kategori (untuk dropdown) dan semua sub kategori untuk tabel
        $categories = Kategori::orderBy('kategori')->get();
        $subCategories = SubKategori::with('kategori')->orderBy('id')->get();

        return view('admin.produk_digital.index', compact('categories', 'subCategories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'sub_kategori' => 'required|string|max:150',
            'link' => 'nullable|string|max:255',
        ]);

        SubKategori::create($data);

        return redirect()->route('admin.produk_digital.index')->with('success', 'Produk digital berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $item = SubKategori::findOrFail($id);

        $data = $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'sub_kategori' => 'required|string|max:150',
            'link' => 'nullable|string|max:255',
        ]);

        $item->update($data);

        return redirect()->route('admin.produk_digital.index')->with('success', 'Produk digital berhasil diperbarui.');
    }

  public function destroy($id)
    {
        $item = SubKategori::findOrFail($id);
        $item->delete();

        return response()->json(['status' => 'ok'], 200);
    }


}
