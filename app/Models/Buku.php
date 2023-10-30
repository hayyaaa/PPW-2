<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Buku extends Model
{
    protected $table = 'buku';
    protected $guarded = [];
    protected $dates = ['tgl_terbit'];
}