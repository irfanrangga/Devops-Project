<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $cartItems = Cart::with('product')
            ->where('user_id', $userId)
            ->get();

        $subtotal = 0;

        // LOGIKA BARU: Loop untuk menyuntikkan harga final ke setiap item
        foreach ($cartItems as $item) {
            if ($item->product) {
                // 1. Cek apakah ini Custom?
                $isCustom = (!empty($item->custom_file) && strtolower($item->custom_file) !== 'standard');
                
                // 2. Tentukan Harga Satuan
                $basePrice = $item->product->harga;
                $finalPrice = $isCustom ? ($basePrice + 5000) : $basePrice;

                // 3. Simpan harga final ini ke object item (agar bisa dibaca di View)
                $item->final_price = $finalPrice;

                // 4. Tambahkan ke Subtotal Global jika item dipilih
                if ($item->is_selected ?? true) {
                    $subtotal += $finalPrice * $item->quantity;
                }
            }
        }

        $tax = $subtotal * 0.11;
        $discount = 0;
        $total = $subtotal + $tax - $discount;

        return view('cart', compact('cartItems', 'subtotal', 'tax', 'total', 'discount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id'  => 'required|exists:products,id',
            'quantity'    => 'nullable|integer|min:1|max:9999',
            'material'    => 'required|string|max:100',
            'warna'       => 'nullable|string|max:100',
            'custom_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,gif,webp|max:5120',
            'note'        => 'nullable|string|max:500',
            'design_type' => 'nullable|string|in:standard,custom'
        ], [
            'custom_file.mimes' => 'File harus berformat JPG, PNG, PDF, GIF, atau WEBP',
            'custom_file.max' => 'Ukuran file tidak boleh lebih dari 5MB',
            'quantity.max' => 'Jumlah tidak boleh lebih dari 9999',
        ]);

        $userId = Auth::id();
        if(!$userId) {
            return redirect()->route('login')
                ->withErrors(['auth' => 'Silahkan login terlebih dahulu']);
        }

        try {
            $productId = $request->product_id;
            $qty = $request->quantity ?? 1;
            $material = $request->material;
            $warna = $request->warna;
            $note = $request->note;
            $designType = $request->input('design_type', 'standard');
            
            $filePath = 'Standard'; // Default

            // A. Jika User Upload File
            if ($request->hasFile('custom_file')) {
                $file = $request->file('custom_file');
                // Validasi MIME type tambahan
                $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf'];
                if (!in_array($file->getMimeType(), $allowedMimes)) {
                    return redirect()->back()->with('error', 'Tipe file tidak diizinkan. Gunakan JPG, PNG, GIF, WEBP, atau PDF.');
                }
                
                $filePath = $file->store('custom_uploads', 'public');
            } 
            // B. Jika User Pilih Radio Button "Custom" tapi tidak upload file
            elseif ($designType === 'custom') {
                $filePath = 'Custom Request'; 
            }

            // Cek Item Duplikat (Merge Quantity)
            $existingCart = Cart::where('user_id', $userId)
                ->where('product_id', $productId)
                ->where('material', $material)
                ->where('warna', $warna)
                ->where('custom_file', $filePath) 
                ->first();

            if ($existingCart) {
                $newQty = $existingCart->quantity + $qty;
                if ($newQty > 9999) {
                    return redirect()->back()->with('error', 'Jumlah total tidak boleh lebih dari 9999');
                }
                $existingCart->increment('quantity', $qty);
            } else {
                Cart::create([
                    'user_id'     => $userId,
                    'product_id'  => $productId,
                    'quantity'    => $qty,
                    'material'    => $material,   
                    'warna'       => $warna,      
                    'custom_file' => $filePath,
                    'note'        => $note,       
                    'is_selected' => true
                ]);
            }

            return redirect()->route('cart')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
        } catch (\Exception $e) {
            \Log::error('Cart Store Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan produk ke keranjang. Silakan coba lagi.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = Cart::where('user_id', Auth::id())->where('id', $id)->firstOrFail();

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        if ($request->ajax() || $request->wantsJson()) {
            // Hitung ulang harga item total untuk response JSON (Update Realtime)
            $isCustom = !empty($cartItem->custom_file) && strtolower($cartItem->custom_file) !== 'standard';
            
            $price = $isCustom ? ($cartItem->product->harga + 5000) : $cartItem->product->harga;
            
            $itemTotal = $price * $cartItem->quantity;

            return response()->json([
                'success' => true,
                'item_total' => $itemTotal
            ]);
        }

        return redirect()->back();
    }

    public function destroy($id)
    {
        $cartItem = Cart::where('user_id', Auth::id())->where('id', $id)->first();

        if ($cartItem) {
            $cartItem->delete();
        }
        return redirect()->back()->with('success', 'Item dihapus dari keranjang.');
    }
}