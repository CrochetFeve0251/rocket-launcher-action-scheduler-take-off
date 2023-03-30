<?php

namespace RocketLauncherActionSchedulerTakeOff\Commands;

use RocketLauncherActionSchedulerTakeOff\Services\BootManager;
use RocketLauncherActionSchedulerTakeOff\Services\PluginManager;
use RocketLauncherBuilder\Commands\Command;

class InstallCommand extends Command
{

    /**
     * @var BootManager
     */
    protected $boot_manager;

    /**
     * @var PluginManager
     */
    protected $plugin_manager;

    public function __construct(BootManager $boot_manager, PluginManager $plugin_manager)
    {
        parent::__construct('action-scheduler:initialize', 'Initialize the Action Scheduler library');

        $this->boot_manager = $boot_manager;
        $this->plugin_manager = $plugin_manager;

        $this
            // Usage examples:
            ->usage(
            // append details or explanation of given example with ` ## ` so they will be uniformly aligned when shown
                '<bold>  $0 action-scheduler:initialize</end> ## Initialize the Action Scheduler library<eol/>'
            );
    }

    public function execute() {
        $this->plugin_manager->install();
        $this->boot_manager->install();
    }
}