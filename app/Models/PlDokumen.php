<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class PlDokumen extends Model
{
    use HasFactory;

    protected $table = 'pl_dokumen';
    protected $primaryKey = 'id';

    protected $fillable = [
        'pl_id',
        'nama_dokumen',
        'path_file',
    ];
    public function pl()
    {
        return $this->belongsTo(PermasalahanLahan::class, 'pl_id');
    }
}
