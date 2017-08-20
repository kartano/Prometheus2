<?php
/**
 * Bootstrap for web application.
 *
 * @author          Simon Mitchell <kartano@gmail.com>
 *
 * @namespace       NAMESPACE HERE
 *
 * @version         1.0.0           2017-08-16 2017-08-16 Prototype
 */

// Including global autoloader
require_once dirname(__FILE__) . '/../vendor/autoload.php';

// Init config data
$config = array();

$configFile = dirname(__FILE__) . '/../share/config/default.php';
if (is_readable($configFile)) {
    require_once $configFile;
}

/**
 * This is the autoloader used for the Prom2 specific modules.
 * If the requested class cannot be found within the vendor autoload for Composer,
 * it will fall back to here - where we can determine our class name.
 */
spl_autoload_register(function ($name) {
    $file=dirname(__FILE__);
    $file.='\\..\\';
    $name=strtolower(str_replace('Prometheus2\\','',$name)).'.php';
    $file.=$name;
    if (file_exists($file)) {
        require_once $file;
    } else {
        throw new \Exception("Unable to create instance of class: $name.");
    }
});
