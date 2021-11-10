<?php

namespace Modules\Cronjob;

use Illuminate\Console\Scheduling\Schedule as ScheduleBase;
use Illuminate\Console\Scheduling\Event;
use Symfony\Component\Process\Process;
use Illuminate\Contracts\Container\Container;
class Schedule extends ScheduleBase
{


/**
     * Add a new command event to the schedule.
     *
     * @param  string  $command
     * @param  array  $parameters
     * @return \Illuminate\Console\Scheduling\Event
     */
    public function exec($command, array $parameters = [])
    {
        if (count($parameters)) {
            $command .= ' '.$this->compileParameters($parameters);
        }
        $this->events[] = $event = new ScheduleEvent($this->eventMutex, $command, $this->timezone);
        return $event;
    }

 

}