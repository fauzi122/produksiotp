<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionReportRmAuxOtherProductionResult extends Model
{
    use HasFactory;
	protected $table = 'report_rm_aux_other_production_results';
    protected $guarded=[
        'id'
    ];
}
