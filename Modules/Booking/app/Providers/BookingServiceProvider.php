<?php

namespace Modules\Booking\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Modules\Booking\Repositories\BookingRepository;
use Modules\Booking\Repositories\BookingRepositoryEloquent;

class BookingServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Booking';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'booking';

    // protected array $commands = [];

    /**
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        \Modules\Booking\Repositories\BookingRepositoryInterface::class => \Modules\Booking\Repositories\BookingRepository::class,
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
