<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class HplDokumen extends Model
{
    use HasFactory;

    protected $table = 'hpl_dokumen';
    protected $primaryKey = 'id';

    protected $fillable = [
        'hpl_id',
        'nama_dokumen',
        'path_file',
    ];
    public function hpl()
    {
        return $this->belongsTo(Hpl::class, 'hpl_id');
    }
}
