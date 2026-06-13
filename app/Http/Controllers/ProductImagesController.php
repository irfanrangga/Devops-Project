<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductImagesController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'subimage_name' => 'required|string|max:255',
            'image_url' => 'required|url'
        ]);

        $productImage = new \App\Models\ProductImages();
        $productImage->product_id = $request->product_id;
        $productImage->subimage_name = $request->subimage_name;
        $productImage->image_url = $request->image_url;
        $productImage->save();

        return redirect()->back()->with('success', 'Gambar produk berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'subimage_name' => 'required|string|max:255',
            'image_url' => 'required|url'
        ]);

        $productImage = \App\Models\ProductImages::findOrFail($id);
        $productImage->subimage_name = $request->subimage_name;
        $productImage->image_url = $request->image_url;
        $productImage->save();

        return redirect()->back()->with('success', 'Gambar produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $productImage = \App\Models\ProductImages::findOrFail($id);
        $productImage->delete();

        return redirect()->back()->with('success', 'Gambar produk berhasil dihapus!');
    }
}
