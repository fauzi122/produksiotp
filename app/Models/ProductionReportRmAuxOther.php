<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionReportRmAuxOther extends Model
{
    use HasFactory;
	protected $table = 'report_rm_aux_others';
    protected $guarded=[
        'id'
    ];
}
