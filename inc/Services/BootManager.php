<?php

namespace LaunchpadActionSchedulerTakeOff\Services;

use League\Flysystem\Filesystem;
use LaunchpadCLI\Entities\Configurations;

class BootManager
{
    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var Configurations
     */
    protected $configurations;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem, Configurations $configurations)
    {
        $this->filesystem = $filesystem;
        $this->configurations = $configurations;
    }

    public function install() {
        $boot_file = $this->get_boot_filename();
        if(! $boot_file || ! $this->filesystem->has($boot_file)) {
            return;
        }
        $content = $this->filesystem->read($boot_file);

        if( ! preg_match('/(?<boot>boot\s*\(\s*__FILE__\s*\)\s*;\n)/', $content, $results) ) {
            return;
        }

        $boot = $results['boot'];

        $base_dir = "__DIR__ . DIRECTORY_SEPARATOR . '{$this->configurations->getCodeDir()}Dependencies'";

        $boot_replace = $boot . "require_once $base_dir . DIRECTORY_SEPARATOR . 'ActionScheduler' . DIRECTORY_SEPARATOR . 'action-scheduler.php';\n";

        $content = str_replace($boot, $boot_replace, $content);

        $this->filesystem->update($boot_file, $content);
    }


    protected function get_boot_filename() {
        $params_path = 'configs/parameters.php';
        if( ! $this->filesystem->has($params_path)) {
            return '';
        }

        $content = $this->filesystem->read($params_path);

        if( ! preg_match('/[\'"]prefix[\'"]\s=>\s*[\'"](?<prefix>[^\'"]*)[\'"]/', $content, $results)) {
            return '';
        }

        $prefix = $results['prefix'];

        $prefix = trim($prefix, '_');
        $prefix = str_replace('_', '-', $prefix);

        return $prefix . '.php';
    }
}
