<?php

namespace Modules\Cronjob;


use Illuminate\Console\Scheduling\Event;
use Illuminate\Contracts\Container\Container;
class ScheduleEvent extends Event
{



 


      /**
     * Run the given event.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     * @return void
     */
    public function run(Container $container)
    {
        dump('doing it');
        parent::run($container);
        // dd($this->runInBackground);
    }

}