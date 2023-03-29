<?php

namespace RocketLauncherActionSchedulerTakeOff\Commands;

use RocketLauncherActionSchedulerTakeOff\Services\BootManager;
use RocketLauncherBuilder\Commands\Command;

class InstallCommand extends Command
{

    /**
     * @var BootManager
     */
    protected $boot_manager;

    public function __construct(BootManager $boot_manager)
    {
        parent::__construct('action-scheduler:initialize', 'Initialize the Action Scheduler library');

        $this->boot_manager = $boot_manager;

        $this
            // Usage examples:
            ->usage(
            // append details or explanation of given example with ` ## ` so they will be uniformly aligned when shown
                '<bold>  $0 action-scheduler:initialize</end> ## Initialize the Action Scheduler library<eol/>'
            );
    }

    public function execute() {
        $this->boot_manager->install();
    }
}