<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanFasilitas extends Model
{
    protected $table = 'peminjaman_fasilitas';
    protected $primaryKey = 'pinjam_id';

    protected $fillable = [
        'fasilitas_id',
        'warga_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'tujuan',
        'status',
        'total_biaya',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'total_biaya' => 'decimal:2',
    ];

    // RELASI
    public function fasilitas()
    {
        return $this->belongsTo(FasilitasUmum::class, 'fasilitas_id', 'fasilitas_id');
    }

    public function warga()
    {
        return $this->belongsTo(Warga::class, 'warga_id', 'warga_id');
    }

    // MEDIA: Bukti bayar (ref_table = peminjaman_fasilitas)
    public function media()
    {
        return $this->hasMany(Media::class, 'ref_id', 'pinjam_id')
            ->where('ref_table', 'peminjaman_fasilitas')
            ->orderBy('sort_order');
    }

    // Accessor bukti bayar (ambil semua file bukti)
    public function getBuktiBayarAttribute()
    {
        return $this->media;
    }
}
