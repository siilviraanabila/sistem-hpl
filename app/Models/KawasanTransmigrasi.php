<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class KawasanTransmigrasi extends Model
{
    use HasFactory;

    protected $table = 'kawasan_transmigrasi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'desa_id',
        'nama_kawasan',
        'nama_lokasi',
    ];
    public function desa()
    {
        return $this->belongsTo(Desa::class, 'desa_id');
    }

    public function shm()
    {
        return $this->hasMany(Shm::class, 'kawasan_transmigrasi_id');
    }

    public function permasalahan()
    {
        return $this->hasMany(PermasalahanLahan::class, 'kawasan_transmigrasi_id');
    }

    public function dokumen()
    {
        return $this->hasMany(PlDokumen::class, 'pl_id', 'pl_id');
    }

    public function progressPl()
    {
        return $this->hasOne(PlProgress::class, 'kawasan_transmigrasi_id');
    }
}