<?php

namespace App\Filament\Resources\ProductImages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductImagesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->label('Pilih Produk')
                    ->relationship('product', 'nama') // Sesuaikan dengan nama relasi dan atribut yang ingin ditampilkan
                    ->required(),
                TextInput::make('subimage_name')
                    ->label('Nama Sub Gambar')
                    ->placeholder('Contoh: Gambar Samping')
                    ->required(),
                FileUpload::make('image_url')
                    ->label('Upload Gambar Produk')
                    ->required()
                    ->image()
                    ->maxSize(5000) // Maksimal ukuran file dalam KB
                    ->disk('public') // Sesuaikan dengan disk yang Anda gunakan
                    ->directory('assets'), // Direktori penyimpanan file
            ]);
    }
}
