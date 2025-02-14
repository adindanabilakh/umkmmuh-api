<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UMKM extends Model
{

    use HasFactory;
    protected $table = "u_m_k_m_s";
    protected $fillable = [
        'name',
        'type',
        'status',
        'address',
        'location_url',
        'email',
        'password'
    ];
}
