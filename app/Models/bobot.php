<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class bobot extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bobots';

    protected $fillable = ['kriteria_id', 'bobot'];

    public function kriteria()
    {
        return $this->belongsTo(kriteria::class);
    }
}
