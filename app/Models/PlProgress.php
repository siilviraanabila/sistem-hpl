<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class PlProgress extends Model
{
    use HasFactory;

    protected $table = 'pl_progress';
    protected $primaryKey = 'progres_pl_id';

    protected $fillable = [
        'pl_id',
        'tahun',
        'jumlah_kasus',
        'status_penanganan',
        'tindak_lanjut',
        'rekomendasi',
    ];

    public function permasalahan()
    {
        return $this->belongsTo(
            PermasalahanLahan::class,
            'pl_id',      // FK di pl_progress
            'pl_id'       // PK di permasalahan_lahan
        );
    }
}
