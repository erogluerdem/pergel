<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyFieldReportLabour extends Model
{
    use HasFactory;

    protected $table = 'daily_field_report_labours';
    protected $fillable = ['report_id','subcontractor','trade','count','hours'];
}
