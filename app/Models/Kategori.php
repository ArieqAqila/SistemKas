<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_kategori',
        'nominal_kategori',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->hasMany(User::class, 'id_kategori', 'id_kategori');
    }
}
