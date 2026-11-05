<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyFieldReportEquipment extends Model
{
    use HasFactory;

    protected $table = 'daily_field_report_equipments';
    protected $fillable = ['report_id','equipment_type','identifier','working_hours','idle_hours'];
}
