<?php

namespace App\Http\Requests\SubcontractorPortal;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateServiceEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('subcontractor')->check();
    }

    public function rules(): array
    {
        return [
            'entry_date' => ['required', 'date'],
            'service_type' => ['required', 'string', 'max:255'],
            'work_description' => ['required', 'string', 'max:1000'],
            'quantity' => ['nullable', 'numeric', 'min:0'],
            'unit' => ['nullable', 'string', 'max:50'],
            'worked_hours' => ['nullable', 'numeric', 'min:0'],
            'crew_size' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'in:draft,submitted'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'mimes:pdf,jpg,jpeg,png,doc,docx,xlsx', 'max:10240'],
        ];
    }
}
