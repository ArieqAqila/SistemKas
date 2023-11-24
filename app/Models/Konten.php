<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konten extends Model
{
    use HasFactory;

    protected $table = 'konten';
    protected $primaryKey = 'id_konten';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_user',
        'judul_konten',
        'isi_konten',
        'gambar',
        'tgl_konten'
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
