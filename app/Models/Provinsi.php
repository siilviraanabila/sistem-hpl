<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Provinsi extends Model
{
    use HasFactory;

    protected $table = 'provinsi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_provinsi',
    ];
    public function kabupaten()
    {
        return $this->hasMany(Kabupaten::class);
    }
}
