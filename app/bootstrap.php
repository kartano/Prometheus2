<?php
/**
 * Bootstrap for web application.
 *
 * @author          Simon Mitchell <kartano@gmail.com>
 *
 * @namespace       NAMESPACE HERE
 *
 * @version         1.0.2           2017-09-23 00:16:00 SM:  Checks for required PHP modules.
 */

// Including global autoloader
require_once dirname(__FILE__) . '/../vendor/autoload.php';

ini_set( 'session.use_only_cookies', TRUE );
ini_set( 'session.use_trans_sid', FALSE );

/**
 * Global Prom2 Version no.
 */
define('PROM2_VERSION_NO','0.0.1alpha',true);

// Init config data
$config = [];

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
    $file = dirname(__FILE__);
    $file .= '\\..\\';
    // SM:  We don't need to prepend the filename with Prometherus 2.
    //      ALL CLASS FILES should be in lower case.
    $name = strtolower(str_replace('Prometheus2\\', '/../', $name)) . '.php';
    $file .= $name;
    if (file_exists($file)) {
        require_once $file;
    } else {
        throw new \Exception("Unable to create instance of class: $name.");
    }
});

/**
 * Execute migration scripts.
 */
try {
    $db=\Prometheus2\common\database\PromDB::createGod();
    $manager=new \Prometheus2\common\migration\MigrationManager();
    $manager->InstallScripts($db);
    @$db->close();
} catch (\Exception $exception) {
    Prometheus2\common\pagerendering\pagehelper::throwHTTPError($exception->getCode(), $exception->getMessage());
    exit(-1);
}

$modules=\Prometheus2\common\settings\Settings::get('app','required_php_modules');
foreach($modules as $module) {
    if (!extension_loaded($module)) {
        throw new \RuntimeException("Required PHP module $module is not isntalled.  Modules we need are: ".implode(PHP_EOL, $modules));
    }
}
