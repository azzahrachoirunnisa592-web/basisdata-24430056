<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    protected $table = 'pinjaman';
    protected $primaryKey = 'id_pinjaman';

    protected $fillable = [
        'id_anggota', 'jumlah_pinjaman', 'sisa_pinjaman', 'angsuran_per_bulan',
        'tgl_pinjaman', 'tgl_jatuh_tempo', 'status', 'keterangan',
    ];

    protected $casts = [
        'tgl_pinjaman'    => 'date',
        'tgl_jatuh_tempo' => 'date',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota', 'id_anggota');
    }
}
