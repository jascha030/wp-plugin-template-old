<?php

namespace Jascha030;

use Jascha030\PluginLib\Plugin\ConfigurablePluginApiRegistry;

loadFile(__DIR__ . '/includes/plugin-header.php');
loadFile(__DIR__ . '/vendor/autoload.php', ', please try running `composer install` in the console');

$plugin = new ConfigurablePluginApiRegistry('PLUGIN_NAME', __DIR__ . '/config/config.php');

/**
 * Init main plugin class
 */
add_action(
    'plugins_loaded',
    static function () {
    }
);

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
