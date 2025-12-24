<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class kandidat extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kandidats';

    protected $fillable = ['nama'];

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class);
    }

    public function getStatusAttribute()
    {
        return $this->penilaian()->exists() ? 'sudah dinilai' : 'belum dinilai';
    }
}
