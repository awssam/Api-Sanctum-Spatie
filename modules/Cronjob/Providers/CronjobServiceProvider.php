<?php

namespace Modules\Cronjob\Providers;

use Illuminate\Support\ServiceProvider;

class CronjobServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Cronjob';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'cronjob';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {

        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
    }


}
