<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'no_faktur',
        'id_anggota',
        'user_id',
        'tgl_transaksi',
        'total_belanja',
        'diskon',
        'total_bayar',
        'nominal_uang',
        'kembalian',
    ];

    protected $casts = [
        'tgl_transaksi' => 'datetime',
    ];

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'id_anggota', 'id_anggota');
    }

    public function kasir(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
    }
}
