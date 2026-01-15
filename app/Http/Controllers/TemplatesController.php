<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori; // pastikan model Category ada dan relasi subCategories
use App\Models\SubKategori;
class TemplatesController extends Controller
{
    public function index()
    {
        $categories = Kategori::with('subCategories')->get();

        return view('admin.digital_produk.index', compact('categories'));
    }
}
