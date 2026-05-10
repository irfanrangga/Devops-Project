<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\OrderItem;
use App\Models\Order;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DesignFileController extends Controller
{
    public function download($orderId, $itemId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $item = OrderItem::where('id', $itemId)
            ->where('order_id', $orderId)
            ->firstOrFail();

        if (empty($item->custom_file) || $item->custom_file === 'null' || strtolower($item->custom_file) === 'standard') {
            abort(404, 'File tidak ditemukan');
        }

        $filePath = $item->custom_file;
        if (str_contains($filePath, '..') || str_contains($filePath, '\\')) {
            abort(403, 'Akses ditolak');
        }

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan di storage');
        }

        $fullPath = Storage::disk('public')->path($filePath);
        $fileName = basename($filePath);
        $mimeType = Storage::disk('public')->mimeType($filePath);

        return response()->download($fullPath, $fileName, [
            'Content-Type' => $mimeType,
        ]);
    }

    /**
     * View design file di browser (untuk image)
     */
    public function view($orderId, $itemId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $item = OrderItem::where('id', $itemId)
            ->where('order_id', $orderId)
            ->firstOrFail();

        if (empty($item->custom_file) || $item->custom_file === 'null' || strtolower($item->custom_file) === 'standard') {
            abort(404, 'File tidak ditemukan');
        }

        $filePath = $item->custom_file;
        if (str_contains($filePath, '..') || str_contains($filePath, '\\')) {
            abort(403, 'Akses ditolak');
        }

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        $fullPath = Storage::disk('public')->path($filePath);
        $mimeType = Storage::disk('public')->mimeType($filePath);
        $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($fileExtension, $imageExtensions)) {
            abort(403, 'Format file tidak didukung untuk ditampilkan');
        }

        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    /**
     * Upload design file dari cart/checkout
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf,gif,webp|max:5120', // 5MB
        ]);

        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $path = $file->store('custom_uploads', 'public');

                return response()->json([
                    'success' => true,
                    'message' => 'File berhasil diupload',
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Tidak ada file yang dikirim',
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload file: ' . $e->getMessage(),
            ], 500);
        }
    }
}
