<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasKeluar extends Model
{
    use HasFactory;

    protected $table = 'kas_keluar';
    protected $primaryKey = 'id_keluar';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nominal_keluar',
        'tgl_keluar',
        'deskripsi_keluar',
    ];

    public $timestamps = false;
}
