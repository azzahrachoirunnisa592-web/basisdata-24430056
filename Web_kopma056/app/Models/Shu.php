<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shu extends Model
{
    protected $table = 'shu';
    protected $primaryKey = 'id_shu';

    protected $fillable = [
        'id_anggota', 'tahun', 'shu_jasa_simpanan', 'shu_jasa_usaha',
        'total_shu', 'status', 'tgl_pembagian',
    ];

    protected $casts = [
        'tgl_pembagian' => 'date',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota', 'id_anggota');
    }
}
