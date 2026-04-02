<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Shm extends Model
{
    use HasFactory;

    protected $table = 'shm';
    protected $primaryKey = 'shm_id';

    protected $fillable = [
        'kawasan_transmigrasi_id',
        'pola',
        'tahun_patan',
        'jumlah_kk',
        'target_shm',
        'realisasi_shm',
        'sisa_shm',
        'clear_shm',
        'bermasalah_shm',
        'nama_tipologi',
        'tipologi_bidang',
        'status_hpl',
        'status_upt',
        'luas',
        'target_tahunan',
        'bidang',
        'deskripsi',
    ];
    public function kawasan()
    {
        return $this->belongsTo(KawasanTransmigrasi::class,'kawasan_transmigrasi_id');
    }

    public function dokumen()
    {
        return $this->hasMany(ShmDokumen::class, 'shm_id');
    }

}
