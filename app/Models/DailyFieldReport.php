<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyFieldReport extends Model
{
    use HasFactory;

    protected $table = 'daily_field_reports';

    protected $casts = [
        'weather' => 'array',
        'report_date' => 'date',
    ];

    protected $fillable = [
        'project_id','report_date','author_id','notes','weather','status'
    ];

    public function media()
    {
        return $this->hasMany(\App\Models\FieldMedia::class, 'report_id');
    }

    public function labours()
    {
        return $this->hasMany(\App\Models\DailyFieldReportLabour::class, 'report_id');
    }

    public function equipments()
    {
        return $this->hasMany(\App\Models\DailyFieldReportEquipment::class, 'report_id');
    }
}
