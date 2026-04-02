<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisPermasalahan extends Model
{
    protected $table = 'jenis_permasalahan';
    protected $primaryKey = 'jenis_pl_id';

    protected $fillable = [
        'nama_permasalahan',
    ];
}
