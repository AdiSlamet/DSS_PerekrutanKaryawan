<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class sub_kriteria extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sub_kriterias';

    protected $fillable = ['kriteria_id', 'nama', 'nilai'];

    public function kriteria()
    {
        return $this->belongsTo(kriteria::class);
    }
}
