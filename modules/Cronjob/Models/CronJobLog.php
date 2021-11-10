<?php

namespace Modules\Cronjob\Models;

use App\ObjectModel;

class CronJobLog extends ObjectModel
{

    protected $fillable = ['cron_job_id','output_type','output_text'];


    public function cronJob()
    {
    	$this->belongsTo(Cronjob::class);
    }
}
