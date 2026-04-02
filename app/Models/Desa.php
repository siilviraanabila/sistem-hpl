<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Desa extends Model
{
    use HasFactory;

    protected $table = 'desa';
    protected $primaryKey = 'id';

    protected $fillable = [
        'kecamatan_id',
        'nama_desa',
    ];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function kawasan()
    {
        return $this->hasMany(KawasanTransmigrasi::class);
    }
}
