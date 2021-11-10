<?php

namespace Modules\Cronjob\Models;

use App\ObjectModel;

class CronJob extends ObjectModel
{

    protected $fillable = ['signature','options','offset','limit','previous_count','next_count','is_runing','last_executed_at','last_executed_failed'];


    public function logs()
    {
    	$this->havMany(CronJobLog::class);
    }
}
