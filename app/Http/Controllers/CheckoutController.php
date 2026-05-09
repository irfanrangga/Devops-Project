<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Exception;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class CheckoutController extends Controller
{
    public function __construct()
    {
        // Set Xendit Key dari .env
        Configuration::setXenditKey(env('XENDIT_SECRET_KEY'));
    }

    /**
     * Memproses Checkout (Pindah Data Cart -> Order & Create Xendit Invoice)
     */
    public function process(Request $request)
    {
        $userId = Auth::id();
        if(!$userId){
            return redirect()
                ->route('login')
                ->withErrors(['auth' => 'Silahkan login terlebih dahulu']);
        }
        // Support dua mode checkout:
        // - Dari keranjang: tanpa product_id -> ambil item yang is_selected di cart
        // - Direct purchase: ada product_id dari product-detail form

        $cartItems = collect();

        // 1. Logika Pengambilan Item
        if ($request->filled('product_id')) {
            $productId = $request->input('product_id');
            $quantity = max(1, (int) $request->input('quantity', 1));
            $material = $request->input('material');
            $warna = $request->input('warna');
            $designType = $request->input('design_type', 'standard');

            $product = \App\Models\Product::find($productId);
            if (!$product) {
                return redirect()->back()->with('error', 'Produk tidak ditemukan.');
            }

            $customFilePath = null;
            if ($designType === 'custom' && $request->hasFile('custom_file')) {
                $file = $request->file('custom_file');
                $customFilePath = $file->store('storage/custom_uploads', 'public');
            }

            $cartItems->push((object) [
                'product' => $product,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'material' => $material,
                'warna' => $warna,
                'design_type' => $designType,
                'custom_file' => $customFilePath,
                'note' => $request->input('note')
            ]);
        } else {
            $cartItems = Cart::with('product')
                ->where('user_id', Auth::id())
                ->where('is_selected', true)
                ->get();

            if ($cartItems->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada barang yang dipilih untuk dicheckout.');
            }
        }

        // 2. HITUNG TOTAL & HARGA SATUAN (Termasuk Biaya Custom)
        $subtotal = 0;
        
        foreach ($cartItems as $item) {
            $basePrice = $item->product->harga;
            
            $isCustom = (isset($item->design_type) && $item->design_type === 'custom') || 
                        (!empty($item->custom_file) && strtolower($item->custom_file) !== 'standard');

            $finalUnitPrice = $isCustom ? ($basePrice + 5000) : $basePrice;
            
            // SIMPAN harga final ini ke object item untuk dipakai saat create OrderItem
            $item->final_price = $finalUnitPrice;
            
            $qty = $item->quantity;
            $subtotal += ($finalUnitPrice * $qty);
        }

        // ============================================================
        // 3. INTEGRASI NODE.JS UNTUK ONGKIR
        // ============================================================
        $shippingCost = 0;
        $userLocation = $user->location ?? 'Jakarta'; 

        try {
            // Tembak API Node.js
            $response = Http::post('http://localhost:3000/v1/api/shipping/cost', [
                'city' => $userLocation,
            ]);

            if ($response->successful()) {
                $shippingCost = $response->json()['data']['cost'];
            } else {
                $shippingCost = 50000; // Fallback default
            }
        } catch (\Exception $e) {
            $shippingCost = 0; // Fallback error connection
        }

        // Hitung Grand Total
        $tax = $subtotal * 0.11;
        $grandTotal = $subtotal + $tax + $shippingCost;
        $userId = Auth::id();

        DB::beginTransaction();

        try {
            // A. Buat Order Header
            $orderNumber = 'INV-' . date('YmdHis') . '-' . $userId;
            
            $order = Order::create([
                'user_id'        => $userId,
                'number'         => $orderNumber,
                'total_price'    => $grandTotal,
                'payment_status' => '1', // Unpaid
                'order_status'   => 'pending',
                'shipping_address' => $userLocation, 
                'shipping_cost'    => $shippingCost,
            ]);

            // B. Simpan Item (Order Items)
            foreach ($cartItems as $cart) {
                $productId = $cart->product_id ?? $cart->product->id;
                $product = Product::lockForUpdate()->find($productId);
                if(!$product)
                {
                    throw new Exception("Produk tidak ditemukan");
                }

                if($product->stok < $cart->quantity) {
                    throw new Exception("Stok Barang '{$product->nama}' tidak mencukupi");
                }
                $product->decrement('stok', $cart->quantity);

                $savedPrice = $cart->final_price; 
                $customFile = $cart->custom_file ?? null;
                
                if (isset($cart->design_type) && strtolower($cart->design_type) == 'standard') {
                    $customFile = null;
                }
                // Hapus jika kosong/null text
                if (empty($customFile)) {
                    $customFile = null;
                }

                // Generate Note Otomatis
                $noteParts = [];
                if (!empty($cart->note)) $noteParts[] = $cart->note;
                $note = count($noteParts) ? implode("\n", $noteParts) : null;

                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $productId,
                    'product_name'  => $product->nama,
                    'product_price' => $savedPrice, // Gunakan harga final (+5000)
                    'bahan'         => $cart->material ?? $cart->bahan ?? null,
                    'warna'         => $cart->warna ?? null,
                    'quantity'      => $cart->quantity,
                    'custom_file'   => $customFile, // Gunakan variabel yang sudah disanitasi
                    'note'          => $note,
                    'subtotal'      => $savedPrice * $cart->quantity, // Hitung subtotal dengan harga final
                ]);
            }

            // C. Create Xendit Invoice
            $apiInstance = new InvoiceApi();
            $create_invoice_request = new \Xendit\Invoice\CreateInvoiceRequest([
                'external_id' => $orderNumber,
                'amount' => $grandTotal,
                'payer_email' => Auth::user()->email,
                'description' => 'Pembayaran Order ' . $orderNumber,
                'invoice_duration' => 43200, // 12 Jam
                'success_redirect_url' => route('home'),
                'failure_redirect_url' => route('payment.show', $order->id),
                'currency' => 'IDR'
            ]);

            $invoice = $apiInstance->createInvoice($create_invoice_request);

            $order->update([
                'snap_token' => $invoice['invoice_url']
            ]);

            // D. Hapus Cart
            if (! $request->filled('product_id')) {
                Cart::where('user_id', Auth::id())
                    ->where('is_selected', true)
                    ->delete();
            }

            DB::commit();

            return redirect()->route('payment.show', $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Checkout Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan Halaman Pembayaran / Detail Order
     */
    public function show($id)
    {
        $order = Order::with('items')->where('user_id', Auth::id())->findOrFail($id);

        // Auto-Expire saat halaman dibuka
        if ($order->payment_status == '1') {
            // Ubah ke addSeconds(10) jika ingin test cepat, atau addHours(12) untuk production
            $expiredTime = $order->created_at->addHours(12); 

            if (now() > $expiredTime) {
                $order->update([
                    'payment_status' => '3',       
                    'order_status'   => 'cancelled'
                ]);
                $order->refresh();
            }
        }
        
        return view('payment', compact('order'));
    }
}