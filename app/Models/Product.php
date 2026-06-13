<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $fillable = ['nama', 'harga', 'kategori', 'stok', 'bahan', 'warna', 'file', 'rating'];

    public const Bahan = [
        'Fashion' => ['Katun Combed 24s', 'Polyester', 'Dry Fit'],
        'Percetakan' => ['Flexi China', 'Flexi Jerman', 'Vinyl', 'PVC', 'Kertas HVS', 'Kertas Doff', 'Kertas Art Paper'],
        'Desain Grafis' => ['Art Paper', 'Ivory', 'HVS', 'Matt Paper'],
        'Alat Tulis' => ['Kertas HVS', 'Kertas Doff', 'Kertas Art Paper'],
        'Merchandise' => ['Acrylic', 'Enamel', 'Metal'],
    ];
    // mengecek apakah user sudah like atau belum di halaman katalog.
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'wishlists')->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function productImages()
    {
        return $this->hasMany(ProductImages::class, 'product_id');
    }
}
