<?php

namespace Modules\Property\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Modules\Property\Repositories\PropertyRepositoryInterface;
use Modules\Property\Repositories\PropertyRepository;
use Modules\Property\Repositories\PropertyTypeRepositoryInterface;
use Modules\Property\Repositories\PropertyTypeRepository;

class PropertyServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Property';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'property';

    /**
    // protected array $commands = [];

    /**
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        PropertyRepositoryInterface::class => PropertyRepository::class,
        PropertyTypeRepositoryInterface::class => PropertyTypeRepository::class,
    ];

    /**
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    /**
     * Define module schedules.
     * 
     * @param $schedule
     */
    // protected function configureSchedules(Schedule $schedule): void
    // {
    //     $schedule->command('inspire')->hourly();
    // }
}
