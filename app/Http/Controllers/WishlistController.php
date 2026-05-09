<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function toggle(Request $request)
    {
        $userId = Auth::id();

        $productId = $request->product_id;

        // cek apakah sudah ada di wishlist
        $wishlist = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($wishlist) {
            // jika ada, hapus 
            $wishlist->delete();
            return response()->json(['status' => 'removed', 'message' => 'Produk dihapus dari Wishlist.']);
        } else {
            // jika tidak ada, tambah 
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $productId
            ]);
            return response()->json(['status' => 'added', 'message' => 'Produk ditambahkan ke Wishlist.']);
        }
    }
}