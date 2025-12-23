<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FasilitasUmum extends Model
{
    protected $table = 'fasilitas_umum';
    protected $primaryKey = 'fasilitas_id';

    protected $fillable = [
        'nama',
        'jenis',
        'alamat',
        'rt',
        'rw',
        'kapasitas',
        'deskripsi',
    ];

    /* =====================
       RELASI MEDIA
    ===================== */
    public function media()
    {
        return $this->hasMany(Media::class, 'ref_id', 'fasilitas_id')
            ->where('ref_table', 'fasilitas_umum')
            ->orderBy('sort_order');
    }

    /* =====================
       FOTO UTAMA (ACCESSOR)
    ===================== */
    public function getFotoUtamaAttribute()
    {
        return $this->media->firstWhere(fn ($m) =>
            str_starts_with($m->mime_type, 'image/')
        );
    }

    /* =====================
       FILE SOP (ACCESSOR)
    ===================== */
    public function getSopFilesAttribute()
    {
        return $this->media->filter(fn ($m) =>
            !str_starts_with($m->mime_type, 'image/')
        );
    }
}
