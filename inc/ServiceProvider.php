<?php

namespace LaunchpadActionSchedulerTakeOff;

use League\Flysystem\Filesystem;
use LaunchpadActionSchedulerTakeOff\Commands\InstallCommand;
use LaunchpadActionSchedulerTakeOff\Services\BootManager;
use LaunchpadActionSchedulerTakeOff\Services\PluginManager;
use LaunchpadCLI\App;
use LaunchpadCLI\Entities\Configurations;
use LaunchpadCLI\ServiceProviders\ServiceProviderInterface;

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