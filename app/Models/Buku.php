<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buku extends Model
{
    protected $table = 'buku';
    protected $guarded = [];
    protected $dates = ['tgl_terbit'];
    protected $fillable = ['judul', 'penulis', 'harga', 'tgl_terbit', 'filename', 'filepath'];

    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class);
    }

    public function rating()
    {
        return $this->hasMany(Rate::class);
    }

    public function averageRate()
    {
        return $this->rating->avg('rating');
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorite', 'buku_id', 'user_id');
    }

    public static function getPopularBooks($limit = 10)
    {
    return self::with('rating') // Eager load ratings relationship
        ->orderByDesc('averageRate') // Order by average rating in descending order
        ->limit($limit)
        ->get();
    }

    public function kategori()
    {
        return $this->belongsToMany(Kategori::class, 'buku_kategori');
    }

    // public function popularBooks() 
    // {
    //     $popularBooks = Buku::with('rating') 
    //                       ->orderByDesc('averageRate')
    //                       ->take(10)
    //                       ->get();
    
    //     return view('buku.popular', compact('popularBooks'));
    // }
}