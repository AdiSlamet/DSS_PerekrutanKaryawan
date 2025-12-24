<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\SoftDeletes;

class penilaian extends Model
{
    use HasFactory;

    protected $table = 'penilaians';

    protected $fillable = ['kandidat_id', 'periode', 'total_skor', 'klasifikasi'];

    public function kandidat()
    {
        return $this->belongsTo(kandidat::class);
    }

    public function detailPenilaian()
    {
        return $this->hasMany(detail_penilaian::class);
    }
}
