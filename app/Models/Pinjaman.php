<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    use HasFactory;
    protected $table = 'pinjamans';

    protected $guarded = ['id'];

    public function scopeUser($query)
    {
        return $query->where('user_id', auth()->user()->id);
    }
}
