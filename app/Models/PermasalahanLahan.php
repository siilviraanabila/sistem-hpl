<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class PermasalahanLahan extends Model
{
    use HasFactory;

    protected $table = 'permasalahan_lahan';
    protected $primaryKey = 'pl_id';

    protected $fillable = [
        'kawasan_transmigrasi_id',
        'status_lahan',
        'pola',
        'tahun_patan',
        'jumlah_kk',
        'jumlah_bidang',
        'jenis_pl_id',
        'deskripsi',
    ];

    public function kawasan()
    {
        return $this->belongsTo(KawasanTransmigrasi::class, 'kawasan_transmigrasi_id');
    }
    public function jenis()
    {
        return $this->belongsTo(JenisPermasalahan::class, 'jenis_pl_id');
    }

    public function progress()
    {
        return $this->hasMany(
            PlProgress::class,
            'pl_id',  // FK di pl_progress
            'pl_id'   // PK di permasalahan_lahan
        );
    }
    public function dokumen()
    {
        return $this->hasMany(PlDokumen::class, 'pl_id');
    }
}
