<?php

loadFile(__DIR__ . '/includes/plugin-header.php');

use Jascha030\PluginLib\Exception\Psr11\DoesNotImplementHookableInterfaceException;
use Jascha030\PluginLib\Exception\Psr11\DoesNotImplementProviderInterfaceException;
use Jascha030\PluginLib\Plugin\ConfigurablePluginApiRegistry;
use Jascha030\PluginLib\Plugin\PluginApiRegistryInterface;

loadFile(
    __DIR__ . '/vendor/autoload.php',
    ', please try running `composer install` in the console'
);

/**
 * Init main plugin class
 */
add_action(
    'plugins_loaded',
    static function () {
        plugin(true);
    }
);

/**
 * Get the main plugin object
 *
 * @param bool $init
 *
 * @return PluginApiRegistryInterface
 */
function plugin(bool $init = false): PluginApiRegistryInterface
{
    static $plugin, $initialised;

    if (null === $plugin) {
        $plugin = new ConfigurablePluginApiRegistry('PLUGIN_NAME', __DIR__ . '/config/config.php');

        if ($init === true && !$initialised) {
            try {
                $plugin->run();
            } catch (DoesNotImplementProviderInterfaceException | DoesNotImplementHookableInterfaceException $e) {
                \wp_die($e->getMessage());
            }
        }
    }

    return $plugin;
}

/**
 * Get an item from the plugin container.
 *
 * @param string $id
 *
 * @return mixed
 */
function get(string $id)
{
    return plugin()->get($id);
}

/**
 * Includes a file, throws an exception when not found.
 *
 * @param string $fileName
 * @param string $errorText
 */
function loadFile(string $fileName, string $errorText = ''): void
{
    if (!is_file(__DIR__ . $fileName)) {
        throw new \RuntimeException("Couldn\'t find file: '{$fileName}'", $errorText);
    }

    include $fileName;
}
