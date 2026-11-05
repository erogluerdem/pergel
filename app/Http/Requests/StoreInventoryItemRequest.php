<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasPermission('inventory.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:inventory_items,sku',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'min_quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'supplier' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,discontinued',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Ürün adı gereklidir.',
            'sku.required' => 'SKU kodu gereklidir.',
            'sku.unique' => 'Bu SKU kodu zaten kullanılıyor.',
            'category.required' => 'Kategori seçimi gereklidir.',
            'price.required' => 'Fiyat bilgisi gereklidir.',
            'price.numeric' => 'Fiyat sayısal bir değer olmalıdır.',
            'quantity.required' => 'Miktar bilgisi gereklidir.',
            'quantity.integer' => 'Miktar tam sayı olmalıdır.',
            'min_quantity.required' => 'Minimum stok miktarı gereklidir.',
        ];
    }
}
