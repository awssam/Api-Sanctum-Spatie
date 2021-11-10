<?php

namespace Modules\Cronjob;

use Illuminate\Console\Command as CommandBase;
use Modules\Cronjob\Models\CronJob;
use Modules\Cronjob\Models\CronJobLog;

class Command extends CommandBase
{


    public $cronId;

    private $logModel = false;
      /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {   
        // dd($this->logDB)
        if(property_exists($this, 'cron')) $this->logModel = true;
        
        $this->_cronIsStarted();
        try {
            $this->logic();
            $this->_cronIsFinished();

        } catch (\Exception $e) {
            $this->line($e->getMessage(),'Exception');
            $this->_cronIsFailed();
        }

    }

    /**
     * Write a string as standard output.
     *
     * @param  string  $string
     * @param  string|null  $style
     * @param  int|string|null  $verbosity
     * @return void
     */
    public function line($string, $style = null, $verbosity = null)
    {   

        if($this->logModel) {
            CronJobLog::create([
                'cron_job_id' => $this->cronId,
                'output_type' => $string,
                'output_text' =>$style
            ]);
        }


        if ($style == 'Exception') $style = 'error';
        

        parent::line($string, $style, $verbosity);
    }

    public function _cronIsFinished()
    {
        if(!$this->logModel) return;
        $arguments = null;
        if(count($this->arguments()) > 1) {
            $options = implode(' ', $this->arguments());
        }
        $this->cron = CronJob::updateOrCreate([
            'signature'   => $this->signature,
            'options'   => $arguments,
        ],[
            'is_runing' => 0,
            'last_executed_failed' => 0
        ]);
        $this->cronId = $this->cron->id;

    }

    public function _cronIsFailed()
    {   
        if(!$this->logModel) return;

        $arguments = null;
        if(count($this->arguments()) > 1) {
            $options = implode(' ', $this->arguments());
        }
        $this->cron = CronJob::updateOrCreate([
            'signature'   => $this->signature,
            'options'   => $arguments,
        ],[
            'is_runing' => 0,
            'last_executed_failed' => 1
        ]);
        $this->cronId = $this->cron->id;

    }

    public function _cronIsStarted()
    {
        if(!$this->logModel) return;
        $arguments = null;
        if(count($this->arguments()) > 1) {
            $options = implode(' ', $this->arguments());
        }
        $this->cron = CronJob::updateOrCreate([
            'signature'   => $this->signature,
            'options'   => $arguments,
        ],[
            'is_runing' => 1,
            'last_executed_at' => date('Y-m-d H:i:s'),
        ]);
        $this->cronId = $this->cron->id;
    }

}