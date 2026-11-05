<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasPermission('tasks.create');
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
            'description' => 'nullable|string',
            'status' => 'required|in:pending,active,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'project_id' => 'nullable|exists:projects,id',
            'due_date' => 'nullable|date|after:today',
            'estimated_hours' => 'nullable|numeric|min:0',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240', // 10MB max per file
            'notes' => 'nullable|string',
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Görev başlığı gereklidir.',
            'status.required' => 'Görev durumu seçilmelidir.',
            'priority.required' => 'Öncelik seviyesi seçilmelidir.',
            'assigned_to.exists' => 'Seçilen kullanıcı bulunamadı.',
            'due_date.after' => 'Bitiş tarihi bugünden sonra olmalıdır.',
            'estimated_hours.numeric' => 'Tahmini süre sayısal değer olmalıdır.',
            'attachments.*.file' => 'Geçersiz dosya formatı.',
            'attachments.*.max' => 'Dosya boyutu maksimum 10MB olabilir.',
        ];
    }
}
