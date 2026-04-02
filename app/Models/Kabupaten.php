<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Kabupaten extends Model
{
    use HasFactory;

    protected $table = 'kabupaten';
    protected $primaryKey = 'id';

    protected $fillable = [
        'provinsi_id',
        'nama_kabupaten',
    ];
    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }

    public function kecamatan()
    {
        return $this->hasMany(Kecamatan::class);
    }
}