<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    protected $table = 'tagihan';
    protected $primaryKey = 'id_tagihan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_user',
        'nominal_tertagih',
        'nominal_sumbangan',
        'status_tagihan',
        'tgl_tagihan',
    ];

    public $timestamps = false;



    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
