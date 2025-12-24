<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class detail_penilaian extends Model
{
    use HasFactory;

    protected $table = 'detail_penilaians';

    protected $fillable = [
        'penilaian_id',
        'kriteria_id',
        'sub_kriteria_id',
        'nilai_normalisasi',
        'nilai_terbobot'
    ];

    public function penilaian()
    {
        return $this->belongsTo(penilaian::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(kriteria::class);
    }

    public function subKriteria()
    {
        return $this->belongsTo(sub_kriteria::class);
    }


}
