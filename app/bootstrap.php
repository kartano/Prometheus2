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
