<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImages extends Model
{
    /** @use HasFactory<\Database\Factories\ProductImagesFactory> */
    use HasFactory;
    protected $table = 'product_images';
    protected $primaryKey = 'id';
    protected $fillable = [
        'product_id',
        'subimage_name',
        'image_url',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
