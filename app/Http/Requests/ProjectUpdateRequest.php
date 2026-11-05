<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\Validation\IdValidators;

class ProjectUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()?->hasPermission('projects.edit') ?? true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $projectId = $this->route('id') ?? $this->route('project');

        return [
            // Basic Project Information
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:5000',
            'project_code' => "nullable|string|max:100|unique:projects,project_code,{$projectId}",
            'type' => 'nullable|string|max:50',
            'status' => 'nullable|string|in:planning,bidding,contracted,construction,completed',
            'health_status' => 'nullable|string|in:healthy,risky,critical',

            // Location & Address
            'location' => 'nullable|string|max:255',
            'location_province' => 'nullable|string|max:100',
            'location_district' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'cadde_sokak' => 'nullable|string|max:255',
            'municipality' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',

            // Financial Information
            'budget' => 'nullable|numeric|min:0',
            'spent' => 'nullable|numeric|min:0',
            'contract_value' => 'nullable|numeric|min:0',

            // Timeline
            'start_date' => 'nullable|date|date_format:Y-m-d',
            'end_date' => 'nullable|date|date_format:Y-m-d|after_or_equal:start_date',

            // Project Progress
            'progress_percent' => 'nullable|integer|min:0|max:100',

            // Client Information
            'client_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:50',

            // Contractor Information
            'contractor_name' => 'nullable|string|max:255',
            'contractor_vkn' => [
                'nullable',
                'string',
                'max:50',
                function ($attribute, $value, $fail) {
                    if (!$value) return;
                    if (!IdValidators::validateVkn($value)) {
                        $fail('Geçersiz müteahhit VKN.');
                    }
                }
            ],

            // Tax & Financial Details
            'tax_number' => [
                'nullable',
                'string',
                'max:50',
                function ($attribute, $value, $fail) {
                    if (!$value) return;
                    if (!IdValidators::validateTcknOrVkn($value)) {
                        $fail('Geçersiz vergi numarası (VKN veya TCKN).');
                    }
                }
            ],
            'iban' => [
                'nullable',
                'string',
                'max:34',
                function ($attribute, $value, $fail) {
                    if (!$value) return;
                    if (!IdValidators::validateIban($value)) {
                        $fail('Geçersiz IBAN.');
                    }
                }
            ],

            // Legal Documents
            'contract_number' => 'nullable|string|max:100',
            'employer_name' => 'nullable|string|max:255',
            'project_manager' => 'nullable|string|max:255',
            'project_contact_person' => 'nullable|string|max:255',
            'project_contact_email' => 'nullable|email|max:255',
            'project_contact_phone' => 'nullable|string|max:50',

            // Building & Construction Permits
            'building_permit_number' => 'nullable|string|max:100',
            'permit_issue_date' => 'nullable|date|date_format:Y-m-d',
            'permit_expiry_date' => 'nullable|date|date_format:Y-m-d|after_or_equal:permit_issue_date',
            'construction_license_number' => 'nullable|string|max:100',
            'construction_license_expiry_date' => 'nullable|date|date_format:Y-m-d',

            // Land Registry
            'tapu_pafta' => 'nullable|string|max:100',
            'tapu_volume' => 'nullable|string|max:100',
            'land_registry_reference' => 'nullable|string|max:255',

            // Property Measurements
            'gross_area' => 'nullable|numeric|min:0',
            'net_area' => 'nullable|numeric|min:0',

            // Risk & Insurance
            'risk_classification' => 'nullable|string|max:50',
            'insurance_policy_number' => 'nullable|string|max:100',
            'insurance_expiry_date' => 'nullable|date|date_format:Y-m-d',

            // Related Data
            'program_id' => 'nullable|integer|exists:programs,id',
            'subcontractor_id' => 'nullable|integer|exists:subcontractors,id',
            'region' => 'nullable|string|max:255',

            // File Uploads (optional for updates)
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'attachments' => 'nullable|array|max:20',
            'attachments.*' => 'file|mimes:pdf,jpg,jpeg,png,dwg,doc,docx,xls,xlsx|max:5120',
            'building_permit_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,dwg|max:5120',
            'contractor_license_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,dwg|max:5120',
            'insurance_certificate_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,dwg|max:5120',
            'municipality_approval_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,dwg|max:5120',
            'other_documents' => 'nullable|array|max:10',
            'other_documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,dwg|max:5120',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.string' => 'Proje adı metin olmalıdır.',
            'name.max' => 'Proje adı en fazla 255 karakter olabilir.',

            'project_code.unique' => 'Bu proje kodu zaten kullanılıyor.',
            'project_code.max' => 'Proje kodu en fazla 100 karakter olabilir.',

            'status.in' => 'Seçilen proje durumu geçersizdir.',
            'health_status.in' => 'Seçilen sağlık durumu geçersizdir.',

            'budget.numeric' => 'Bütçe sayısal değer olmalıdır.',
            'budget.min' => 'Bütçe 0 veya daha büyük olmalıdır.',
            'spent.numeric' => 'Harcanan miktar sayısal değer olmalıdır.',
            'spent.min' => 'Harcanan miktar 0 veya daha büyük olmalıdır.',

            'start_date.date' => 'Başlama tarihi geçerli bir tarih olmalıdır.',
            'start_date.date_format' => 'Başlama tarihi YYYY-MM-DD formatında olmalıdır.',
            'end_date.date' => 'Bitiş tarihi geçerli bir tarih olmalıdır.',
            'end_date.date_format' => 'Bitiş tarihi YYYY-MM-DD formatında olmalıdır.',
            'end_date.after_or_equal' => 'Bitiş tarihi başlama tarihinden sonra veya eşit olmalıdır.',

            'progress_percent.integer' => 'İlerleme yüzdesi tam sayı olmalıdır.',
            'progress_percent.min' => 'İlerleme yüzdesi 0 veya daha büyük olmalıdır.',
            'progress_percent.max' => 'İlerleme yüzdesi 100 veya daha küçük olmalıdır.',

            'contractor_vkn.max' => 'VKN en fazla 50 karakter olabilir.',
            'tax_number.max' => 'Vergi numarası en fazla 50 karakter olabilir.',
            'iban.max' => 'IBAN en fazla 34 karakter olabilir.',

            'latitude.numeric' => 'Enlem sayısal değer olmalıdır.',
            'latitude.between' => 'Enlem -90 ile 90 arasında olmalıdır.',
            'longitude.numeric' => 'Boylam sayısal değer olmalıdır.',
            'longitude.between' => 'Boylam -180 ile 180 arasında olmalıdır.',

            'permit_expiry_date.after_or_equal' => 'İnşaat ruhsatı son kullanma tarihi çıkış tarihinden sonra olmalıdır.',
            'construction_license_expiry_date.date' => 'İnşaat lisansı son kullanma tarihi geçerli bir tarih olmalıdır.',

            'program_id.exists' => 'Seçilen program bulunamadı.',
            'subcontractor_id.exists' => 'Seçilen alt yüklenici bulunamadı.',

            'cover_image.image' => 'Kapak görseli bir resim dosyası olmalıdır.',
            'cover_image.mimes' => 'Kapak görseli jpeg, png, jpg veya gif formatında olmalıdır.',
            'cover_image.max' => 'Kapak görseli en fazla 5120 KB olabilir.',

            'attachments.array' => 'Ekler dizi formatında olmalıdır.',
            'attachments.max' => 'En fazla 20 dosya ekleyebilirsiniz.',
            'attachments.*.file' => 'Her ek geçerli bir dosya olmalıdır.',
            'attachments.*.mimes' => 'Ek dosyaları pdf, jpg, jpeg, png, dwg, doc, docx, xls veya xlsx olmalıdır.',
            'attachments.*.max' => 'Her ek en fazla 5120 KB olabilir.',

            'project_contact_email.email' => 'Proje iletişim e-postası geçerli bir e-posta adresi olmalıdır.',
            'contact_phone.max' => 'İletişim telefonu en fazla 50 karakter olabilir.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Normalize province/district fields
        if ($this->has('location_province') && !$this->has('province')) {
            $this->merge(['province' => $this->input('location_province')]);
        }

        if ($this->has('location_district') && !$this->has('district')) {
            $this->merge(['district' => $this->input('location_district')]);
        }

        // Trim whitespace from string fields
        $this->merge([
            'name' => trim($this->input('name', '')),
            'project_code' => trim($this->input('project_code', '')),
            'contractor_vkn' => trim($this->input('contractor_vkn', '')),
            'tax_number' => trim($this->input('tax_number', '')),
            'iban' => trim($this->input('iban', '')),
        ]);
    }
}
