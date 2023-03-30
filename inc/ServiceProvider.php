<?php

namespace RocketLauncherActionSchedulerTakeOff;

use League\Flysystem\Filesystem;
use RocketLauncherActionSchedulerTakeOff\Commands\InstallCommand;
use RocketLauncherActionSchedulerTakeOff\Services\BootManager;
use RocketLauncherActionSchedulerTakeOff\Services\PluginManager;
use RocketLauncherBuilder\App;
use RocketLauncherBuilder\Entities\Configurations;
use RocketLauncherBuilder\ServiceProviders\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    /**
     * Interacts with the filesystem.
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Configuration from the project.
     *
     * @var Configurations
     */
    protected $configs;


    /**
     * Instantiate the class.
     *
     * @param Configurations $configs configuration from the project.
     * @param Filesystem $filesystem Interacts with the filesystem.
     * @param string $app_dir base directory from the cli.
     */
    public function __construct(Configurations $configs, Filesystem $filesystem, string $app_dir)
    {
        $this->configs = $configs;
        $this->filesystem = $filesystem;
    }

    public function attach_commands(App $app): App
    {
        $boot_manager = new BootManager($this->filesystem, $this->configs);
        $plugin_manager = new PluginManager($this->filesystem);
        $app->add(new InstallCommand($boot_manager, $plugin_manager));

        return $app;
    }
}