<?php

namespace App\Http\Controllers;

use App\Helpers\ApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
// use App\Models\Cart;
// use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        // REFACTOR: Change to API Call instead of Direct DB Query
        $response = ApiClient::get("/carts/");

        $cartItems = [];
        if($response->successful()) {
            $apiData = $response->object();

            $cartItems = $apiData->data ?? [];
            $subtotal = $apiData->data->subtotal ?? 0;
            $tax = $apiData->data->tax ?? 0;
            $total = $apiData->data->total ?? 0;
            $discount = 0;

            return view('cart', compact('cartItems', 'subtotal', 'tax', 'total', 'discount'));
        }

        // $subtotal = 0;

        // // LOGIKA BARU: Loop untuk menyuntikkan harga final ke setiap item
        // foreach ($cartItems as $item) {
        //     if (isset($item->product)) {
        //         // 1. Cek apakah ini Custom?
        //         $isCustom = (!empty($item->custom_file) && strtolower($item->custom_file) !== 'standard');
                
        //         // 2. Tentukan Harga Satuan
        //         $basePrice = $item->product->harga;
        //         $finalPrice = $isCustom ? ($basePrice + 5000) : $basePrice;

        //         // 3. Simpan harga final ini ke object item (agar bisa dibaca di View)
        //         $item->final_price = $finalPrice;

        //         // 4. Tambahkan ke Subtotal Global jika item dipilih
        //         if ($item->is_selected ?? true) {
        //             $subtotal += $finalPrice * $item->quantity;
        //         }
        //     }
        // }

        // $tax = $subtotal * 0.11;
        // $discount = 0;
        // $total = $subtotal + $tax - $discount;

        return view('cart', ['cartItems' => [], 'subtotal' => 0, 'tax' => 0, 'total' => 0, 'discount' => 0])
               ->with('error', 'Gagal mengambil data keranjang dari server.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'nullable|integer|min:1',
            'material'   => 'required|string',
            'color'      => 'nullable|string',
            'file'       => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'note'       => 'nullable|string',
            'design_type'=> 'nullable|string' 
        ]);

        $token = session('jwt_token');
        if (!$token) {
            return redirect()->route('login')
                ->withErrors('Silahkan login terlebih dahulu');
        }

        // Kode sebelumnya
        // $userId = session('user_id');
        // if(!$userId) {
        //     return redirect()->route('login')
        //         ->withErrors(['auth' => 'silahkan login terlebih dahulu']);
        // }

        // A. Jika User Upload File
        if ($request->hasFile('custom_file')) {
            $filePath = $request->file('custom_file')->store('custom_uploads', 'public');
        } 
        // B. Jika User Pilih Radio Button "Custom" tapi tidak upload file (misal request desain)
        elseif ($request->input('design_type') === 'custom') {
            $filePath = 'Custom Request'; 
        }

        $response = ApiClient::post("/carts/", [
            'product_id' => $request->product_id,
            'quantity'   => $request->quantity ?? 1,
            'material'   => $request->material,
            'color'      => $request->color,
            'custom_file' => $filePath ?? null,
            'note'       => $request->note,
            'design_type'=> $request->input('design_type'),
        ]);

        if ($response->successful()) {
            return redirect()->route('cart')->with('success', 'Produk berhasil ditambahkan!');
        }

        // // Cek Item Duplikat (Merge Quantity)
        // $existingCart = Cart::where('user_id', $userId)
        //     ->where('product_id', $productId)
        //     ->where('material', $material)
        //     ->where('color', $color)
        //     ->where('custom_file', $filePath) 
        //     ->first();

        // if ($existingCart) {
        //     $existingCart->increment('quantity', $qty);
        // } else {
        //     Cart::create([
        //         'user_id'     => $userId,
        //         'product_id'  => $productId,
        //         'quantity'    => $qty,
        //         'material'    => $material,   
        //         'color'       => $color,      
        //         'custom_file' => $filePath,
        //         'note'        => $note,       
        //         'is_selected' => true
        //     ]);
        // }

        return redirect()->back()->with('error', 'Gagal menambahkan produk ke keranjang!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $response = ApiClient::put("/carts/{$id}", [
            'quantity' => $request->quantity
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            if($response->successful()) {
                $cartItem = $response->json();
                return response()->json([
                    'success' => true,
                    'item_total' => $cartItem['item_total'] ?? 0
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui jumlah item',
            ]);
        }

        return redirect()->back();
    }

    public function destroy($id)
    {
        $response = ApiClient::delete("/carts/{$id}");

        if ($response->successful()) {
            return redirect()->back()->with('success', 'Item dihapus dari keranjang.');
        }

        return redirect()->back()->with('error', 'Gagal menghapus item dari keranjang.');
    }
}