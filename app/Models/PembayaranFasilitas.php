<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranFasilitas extends Model
{
    protected $table = 'pembayaran_fasilitas';
    protected $primaryKey = 'bayar_id';

    protected $fillable = [
        'pinjam_id',
        'tanggal',
        'jumlah',
        'metode',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];

    /* =========================
       RELASI
       ========================= */

    public function peminjaman()
    {
        return $this->belongsTo(
            PeminjamanFasilitas::class,
            'pinjam_id',
            'pinjam_id'
        );
    }

    public function media()
    {
        return $this->hasMany(Media::class, 'ref_id', 'bayar_id')
            ->where('ref_table', 'pembayaran_fasilitas')
            ->orderBy('sort_order');
    }

    /* =========================
       ACCESSOR
       ========================= */

    public function getResiAttribute()
    {
        return $this->media;
    }
}
