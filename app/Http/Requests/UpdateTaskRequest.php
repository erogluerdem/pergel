<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'required|exists:users,id',
            'due_date' => 'required|date|after_or_equal:today',
            'status' => 'required|in:pending,active,review,completed,cancelled',
            'completion_rate' => 'nullable|integer|min:0|max:100'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Görev başlığı zorunludur.',
            'title.max' => 'Görev başlığı maksimum 255 karakter olabilir.',
            'description.required' => 'Görev açıklaması zorunludur.',
            'description.max' => 'Görev açıklaması maksimum 2000 karakter olabilir.',
            'priority.required' => 'Öncelik seviyesi seçilmelidir.',
            'priority.in' => 'Geçersiz öncelik seviyesi.',
            'assigned_to.required' => 'Görev atanacak kişi seçilmelidir.',
            'assigned_to.exists' => 'Seçilen kullanıcı bulunamadı.',
            'due_date.required' => 'Bitiş tarihi zorunludur.',
            'due_date.date' => 'Geçerli bir tarih giriniz.',
            'due_date.after_or_equal' => 'Bitiş tarihi bugünden erken olamaz.',
            'status.required' => 'Durum seçilmelidir.',
            'status.in' => 'Geçersiz durum.',
            'completion_rate.integer' => 'Tamamlanma oranı sayı olmalıdır.',
            'completion_rate.min' => 'Tamamlanma oranı en az 0 olabilir.',
            'completion_rate.max' => 'Tamamlanma oranı en fazla 100 olabilir.'
        ];
    }
}
