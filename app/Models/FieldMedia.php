<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldMedia extends Model
{
    use HasFactory;

    protected $table = 'field_media';

    protected $fillable = ['project_id','report_id','uploader_id','original_name','path','mime','size','gps_lat','gps_lon'];
}
