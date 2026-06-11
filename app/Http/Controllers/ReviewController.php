<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReviewRequest $request)
    {
        $userId = Auth::id();
        $review = Review::create([
            'user_id' => $userId,
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);
        return redirect()->back()->with('success', 'Terima Kasih! Ulasan Anda berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReviewRequest $request, Review $review)
    {
        $review->update($request->validated());
        return redirect()->back()->with('success', 'Ulasan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            Abort(403, 'Anda tridak memiliki izin untuk menghapus ulasan ini.');
        }
        $review->delete();
        return redirect()->back()->with('success', 'Ulasan berhasil dihapus!');
    }
}
