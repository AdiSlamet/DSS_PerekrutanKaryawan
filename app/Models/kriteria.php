<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Testing\Fluent\Concerns\Has;

class kriteria extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kriterias';

    protected $fillable = ['nama', 'jenis'];

    public function subkriteria()
    {
        return $this->hasMany(sub_kriteria::class);
    }

    public function bobot()
    {
        return $this->hasOne(Bobot::class);
    }
}
