<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_kategori',
        'username',
        'password',
        'nama_user',
        'tgl_lahir',
        'notelp',
        'alamat',
        'foto_profile',
        'hak_akses',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    protected $dates = ['tgl_lahir'];

    public $timestamps = false;


    public function tagihan()
    {
        return $this->hasOne(Tagihan::class, 'id_user', 'id_user');
    }

    public function konten()
    {
        return $this->hasMany(Konten::class, 'id_user', 'id_user');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }
}
