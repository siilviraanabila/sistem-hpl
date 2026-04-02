<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ShmDokumen extends Model
{
    use HasFactory;

    protected $table = 'shm_dokumen';
    protected $primaryKey = 'id';

    protected $fillable = [
        'shm_id',
        'nama_dokumen',
        'path_file',
    ];
    public function shm()
    {
        return $this->belongsTo(Shm::class, 'shm_id');
    }
}
