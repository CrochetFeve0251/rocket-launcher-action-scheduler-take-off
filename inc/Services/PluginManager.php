<?php

namespace RocketLauncherActionSchedulerTakeOff\Services;

use League\Flysystem\Filesystem;

class PluginManager
{
    const PROJECT_FILE = 'composer.json';

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function install() {

        $as_path = './inc/Dependencies/ActionScheduler/';

        $as_library = "woocommerce/action-scheduler";

        if( ! $this->filesystem->has( self::PROJECT_FILE ) ) {
            return;
        }

        $content = $this->filesystem->read( self::PROJECT_FILE );

        $json = json_decode( $content, true );

        if( ! $json ) {
            return;
        }

        if( ! array_key_exists( 'extra', $json ) || ! array_key_exists( 'installer-paths', $json['extra'] ) ) {
            return;
        }

        if( ! array_key_exists( $as_path, $json['extra']['installer-paths'] ) ) {
            if( ! is_array( $json['extra']['installer-paths'][$as_path] ) ) {
                $json['extra']['installer-paths'][$as_path] = [];
            }
            array_unshift($json['extra']['installer-paths'][$as_path], $as_library);
        }

        if( array_key_exists('require-dev', $json) && array_key_exists($as_library, $json['require-dev'] ) ) {
            $content = json_encode($json, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) . "\n";

            $this->filesystem->update(self::PROJECT_FILE, $content);
            return;
        }

        $json['require-dev'][$as_library] = '^3.4';

        $content = json_encode($json, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) . "\n";

        $this->filesystem->update(self::PROJECT_FILE, $content);
    }
}
