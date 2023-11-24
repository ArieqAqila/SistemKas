<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasMasuk extends Model
{
    use HasFactory;

    protected $table = 'kas_masuk';
    protected $primaryKey = 'id_masuk';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nominal_masuk',
        'tgl_masuk',
        'deskripsi_masuk',
    ];

    public $timestamps = false;
}
