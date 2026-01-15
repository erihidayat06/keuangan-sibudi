<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';
    protected $fillable = ['kategori', 'created_at', 'updated_at'];

    // timestamps already present in table so Eloquent will manage them
    public $timestamps = false; // karena kolom created_at/updated_at ada tetapi kita tidak paksa auto timestamps
    // Jika Anda ingin Eloquent auto-set created_at/updated_at, set $timestamps = true dan ubah kolom ke TIMESTAMP DEFAULT CURRENT_TIMESTAMP dll.

    public function subCategories()
    {
        return $this->hasMany(SubKategori::class, 'kategori_id', 'id');
    }
}
