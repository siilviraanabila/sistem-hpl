<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Hpl extends Model
{
    use HasFactory;

    protected $table = 'hpl';
    protected $primaryKey = 'hpl_id';

    protected $fillable = [
        'kawasan_transmigrasi_id',
        'status_hpl',
        'lokasi_kawasan',
        'no_sk_hpl',
        'tgl_hpl',
        'luas_sk',
        'sisa_luas',
        'no_sertifikat',
        'peta',
        'file_peta',
    ];

    public function kawasan()
    {
        return $this->belongsTo(KawasanTransmigrasi::class,'kawasan_transmigrasi_id');
    }

    public function dokumen()
    {
        return $this->hasMany(HplDokumen::class, 'hpl_id');
    }

    public function sertifikatGroup()
    {
        return $this->hasMany(Hpl::class, 'kawasan_transmigrasi_id', 'kawasan_transmigrasi_id')
            ->whereNotNull('no_sertifikat')
            ->where('no_sertifikat', '!=', '');
    }
}
