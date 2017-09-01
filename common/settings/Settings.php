<?php
/**
 * Application settings file.  Loads and holds static copy of the default.php specified data.
 *
 * @author          Simon Mitchell <kartano@gmail.com>
 *
 * @namespace       Prometheus2\common\settings
 *
 * @version         1.0.0           2017-08-17 2017-08-17 Prototype
 */

namespace Prometheus2\common\settings;

use Prometheus2\common\logging\logger;

/**
 * Class Settings
 * @package Prometheus2\common\settings
 */
class Settings
{
    public static $config;
    /**
     * @var string $filename File from which configuration settings were loaded.
     */
    protected static $filename = '';

    /**
     * Get the static instance of the config data.
     *
     * @return array An array of settings.
     * @throws \Exception Thrown if there was no useful data in the default config file.
     */
    protected static function getData(): array
    {
        $filename = dirname(__FILE__) . '\\..\\..\\share\\config\\default.json';
        $config = json_decode(file_get_contents($filename), true);
        self::$config = $config;
        self::$config['db']['socket'] = intval(ini_get("mysqli.default_socket"));
        return self::$config;
    }

    /**
     * @param array ...$optionPathComponents The path from which to retrieve a setting.  I.E:
     *                                       Settings::get('db','host');
     *
     * @return mixed The value identified from the list of arguments.
     */
    public static function get(...$optionPathComponents)
    {
        $ret = self::getData();
        foreach ($optionPathComponents as $key) {
            if (!isset($ret[$key])) {
                return null;
            }
            $ret = $ret[$key];
        }
        return $ret;
    }

    /**
     * Set a value in the settings array.  THE VALUE IS NOT PERSISTENT BETWEEN EXECUTIONS!
     *
     * @param mixed $value                   The value to store in specified path.
     * @param array ...$optionPathComponents The path from which to retrieve a setting
     */
    public static function set($value, ...$optionPathComponents)
    {
        $currentOption = self::getData();
        foreach ($optionPathComponents as $key) {
            if (!is_array($currentOption)) {
                $currentOption[$key] = [];
            }
            $currentOption =   &$currentOption[$key];
        }
        $currentOption = $value;
    }

    /**
     * Returns an instance of the specified logger class type for this application.
     *
     * @return \prometheus2\common\logging\logger
     */
    public static function getLogger(): logger
    {
        static $logger = null;
        if ($logger === null) {
            $classType = self::get('log', 'class');
            $logger = new $classType;
        }
        return $logger;
    }
}
