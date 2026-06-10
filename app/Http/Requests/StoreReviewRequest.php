<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500'
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required' => 'Anda belum memberikan bintang penilaian.',
            'rating.min' => 'Rating minimal adalah 1 bintang.',
            'rating.max' => 'Rating maksimal adalah 5 bintang.',
            'comment.max' => 'Komentar tidak boleh lebih dari 500 karakter.'
        ];
    }
}
