<?php

namespace App\Http\Requests\SubcontractorPortal;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateMaterialEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('subcontractor')->check();
    }

    public function rules(): array
    {
        return [
            'delivery_date' => ['required', 'date'],
            'material_name' => ['required', 'string', 'max:255'],
            'specification' => ['nullable', 'string', 'max:1000'],
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'unit' => ['nullable', 'string', 'max:50'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:draft,submitted'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'mimes:pdf,jpg,jpeg,png,doc,docx,xlsx', 'max:10240'],
        ];
    }
}
