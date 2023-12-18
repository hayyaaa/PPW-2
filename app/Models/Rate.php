<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rate extends Model
{
    protected $table = 'rating';
    protected $fillable = ['id', 'buku_id', 'user_id', 'rating'];

    public function buku():BelongsTo
    {
        return $this->belongsTo(Buku::class);
    }
}
